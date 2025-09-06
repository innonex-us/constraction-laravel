<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use App\Models\{Page, Service, Project, GalleryItem};

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('import:aac {base} {--max=40}', function (string $base) {
    $base = rtrim($base, '/');
    $host = parse_url($base, PHP_URL_HOST);
    $client = new Client([
        'timeout' => 30,
        'connect_timeout' => 10,
        'http_errors' => false,
        'headers' => ['User-Agent' => 'Laravel-Importer']
    ]);

    $queue = [$base];
    $visited = [];
    $maxPages = (int) $this->option('max') ?: 40;
    $count = 0;

    $this->info("Starting import from {$base}");

    while ($queue && $count < $maxPages) {
        $url = array_shift($queue);
        $norm = rtrim($url, '/');
        if (isset($visited[$norm])) continue;
        $visited[$norm] = true;
        $count++;

        try {
            $res = $client->get($url);
            $status = $res->getStatusCode();
            if ($status >= 400) { $this->warn("Skip {$url} (HTTP {$status})"); continue; }
            $html = (string) $res->getBody();
            if (strlen($html) < 512) throw new \RuntimeException('Empty body');
        } catch (\Throwable $e) {
            $this->warn("Fetch failed: {$url} -> {$e->getMessage()} (fallback curl)");
            $escaped = escapeshellarg($url);
            $html = shell_exec("curl -L --silent --show-error --max-time 60 " . $escaped);
            if (! $html) continue;
            $status = 200;
        }

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        $xp = new \DOMXPath($doc);

        // Extract menu and content links (internal only)
        $anchors = $xp->query('//nav//a[@href] | //div[contains(@class, "entry-content")]//a[@href]');
        foreach ($anchors ?: [] as $a) {
            $href = $a->getAttribute('href');
            if (! $href) continue;
            $u = parse_url($href);
            if (!empty($u['host']) && $u['host'] !== $host) continue; // external
            $next = isset($u['host']) ? $href : $base . '/' . ltrim($href, '/');
            $next = strtok($next, '#'); // remove hash
            // crude skip of feeds and attachments
            if (preg_match('~\.(pdf|zip|docx?)$~i', $next)) continue;
            if (! isset($visited[rtrim($next,'/')])) $queue[] = $next;
        }

        // Extract title and content
        $title = '';
        $h1 = $xp->query('//h1[contains(@class, "entry-title")]')->item(0);
        if ($h1) $title = trim($h1->textContent);
        if (! $title) {
            $titleTag = $xp->query('//title')->item(0);
            if ($titleTag) $title = trim($titleTag->textContent);
        }

        $contentNode = $xp->query('//div[contains(@class, "entry-content")]')->item(0);
        $contentHtml = '';
        if ($contentNode) {
            $contentHtml = '';
            foreach ($contentNode->childNodes as $child) {
                $contentHtml .= $doc->saveHTML($child);
            }
        }

        $firstImg = $xp->query('//div[contains(@class, "entry-content")]//img[@src]')->item(0);
        $img = $firstImg ? $firstImg->getAttribute('src') : null;
        // Also collect gallery items from images on the page
        $imgs = $xp->query('//div[contains(@class, "entry-content")]//img[@src]');
        $path = parse_url($url, PHP_URL_PATH) ?? '/';
        $categoryLabel = (stripos($path,'portfolio')!==false) ? 'Portfolio' : ((stripos($path,'construction-services')!==false)?'Services':'Pages');
        foreach ($imgs ?: [] as $im) {
            $src = $im->getAttribute('src');
            if (! $src) continue;
            if (! preg_match('~\.(jpe?g|png|webp)$~i', $src)) continue;
            $base = pathinfo(parse_url($src, PHP_URL_PATH) ?? '', PATHINFO_FILENAME) ?: 'image';
            $gt = $im->getAttribute('alt') ?: $im->getAttribute('title') ?: $base;
            $slugImg = Str::slug($base);
            GalleryItem::updateOrCreate(
                ['slug' => $slugImg],
                [
                    'title' => $gt,
                    'image' => $src,
                    'category' => $categoryLabel,
                    'is_published' => true,
                ]
            );
        }

        // $path is already set above

        // Decide model: Project if under /portfolio/, Service if under /construction-services/
        if (stripos($path, '/portfolio/') !== false && rtrim($path,'/') !== '/portfolio') {
            $slug = Str::slug(basename($path)) ?: Str::slug($title) ?: Str::random(8);
            Project::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title ?: $slug,
                    'excerpt' => Str::limit(strip_tags($contentHtml), 240),
                    'content' => trim($contentHtml) ?: null,
                    'featured_image' => $img,
                    'status' => 'completed',
                    'category' => 'Portfolio',
                    'is_featured' => false,
                ]
            );
            $this->line("[Project] {$title} ({$slug})");
            continue;
        }

        if (stripos($path, '/construction-services/') !== false || stripos($path, '/guniteshotcrete-services/') !== false) {
            $slug = Str::slug(basename($path)) ?: Str::slug($title) ?: Str::random(8);
            Service::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $title ?: Str::headline($slug),
                    'excerpt' => Str::limit(strip_tags($contentHtml), 240),
                    'content' => trim($contentHtml) ?: null,
                    'image' => $img,
                    'is_active' => true,
                ]
            );
            $this->line("[Service] {$title} ({$slug})");
            continue;
        }

        // Generic page
        // Map "/" to slug "home" to avoid empty
        $leaf = trim(basename($path), '/') ?: 'home';
        $slug = Str::slug($leaf) ?: Str::slug($title) ?: Str::random(8);
        Page::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title ?: Str::headline($slug),
                'content' => trim($contentHtml) ?: null,
                'hero_image' => $img,
                'is_published' => true,
            ]
        );
        $this->line("[Page] {$title} ({$slug})");
    }

    $this->info("Import done. Visited {$count} pages.");
})->purpose('Import pages/services/projects from the AAC WordPress site');
