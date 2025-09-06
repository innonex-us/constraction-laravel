<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? ($settings->site_name ?? 'Construction Co.') }}</title>
    <meta name="description" content="{{ $metaDescription ?? ($settings->headline ?? 'We build with excellence.') }}" />
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <style>
        :root{
            --brand: {{ $settings->primary_color ?? '#10b981' }};
            --brand-2: {{ $settings->secondary_color ?? '#0ea5e9' }};
        }
        body{ font-family: 'Outfit', system-ui, sans-serif; }
        .glass{ backdrop-filter: blur(10px); background: color-mix(in oklab, white 8%, transparent); }
        .neon{ text-shadow: 0 0 12px color-mix(in oklab, var(--brand) 50%, transparent); }
        .gradient{ background-image: radial-gradient(1200px 400px at 10% -10%, color-mix(in oklab, var(--brand) 35%, transparent), transparent),
            radial-gradient(900px 300px at 90% -10%, color-mix(in oklab, var(--brand-2) 35%, transparent), transparent);
        }
    </style>
</head>
<body class="min-h-dvh bg-slate-950 text-slate-100 gradient">
<header class="sticky top-0 z-50">
    <div class="glass border-b border-white/5">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                @if(!empty($settings?->logo_path))
                    <img src="{{ asset($settings->logo_path) }}" alt="Logo" class="h-8 w-auto" />
                @endif
                <span class="font-semibold tracking-wide">{{ $settings->site_name ?? 'Construction Co.' }}</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="/services" class="hover:text-emerald-300 transition">Services</a>
                <a href="/projects" class="hover:text-emerald-300 transition">Projects</a>
                <a href="/gallery" class="hover:text-emerald-300 transition">Gallery</a>
                <a href="/news" class="hover:text-emerald-300 transition">News</a>
                <a href="/page/about" class="hover:text-emerald-300 transition">About</a>
                <a href="/contact" class="hover:text-emerald-300 transition">Contact</a>
                <a href="/admin" class="ml-2 px-3 py-1.5 rounded-md bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/20">Admin</a>
            </nav>
        </div>
    </div>
</header>

<main>
    {{ $slot ?? '' }}
    @yield('content')
</main>

<footer class="mt-20 border-t border-white/5 bg-black/40">
    <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-slate-400">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <p>&copy; {{ now()->year }} {{ $settings->site_name ?? 'Construction Co.' }}. All rights reserved.</p>
            <p>Built with Laravel + Filament. Inspired by industry leaders.</p>
        </div>
    </div>
</footer>

<script>
    AOS.init({ duration: 800, once: true, easing: 'ease-out-cubic' });
</script>
@stack('scripts')
</body>
</html>
