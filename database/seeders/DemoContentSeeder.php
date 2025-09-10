<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\SiteSetting;
use App\Models\Page;
use App\Models\Post;
use App\Models\GalleryItem;
use App\Models\Badge;

class DemoContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::firstOrCreate([], [
            'site_name' => 'Lombii Construction',
            'headline' => 'Making a difference in how the world is built.',
            'subheadline' => 'From preconstruction to delivery, we provide end‑to‑end construction services across markets.',
            'stat_years' => '25+',
            'stat_projects' => '500+',
            'stat_emr' => '0.62',
            'cta_heading' => 'Ready to build something great?',
            'cta_text' => 'Let’s discuss your project and how we can help.',
            'cta_button_text' => 'Get in touch',
            'cta_button_url' => '/contact',
            'primary_color' => '#10b981',
            'secondary_color' => '#0ea5e9',
        ]);

        $services = [
            ['Preconstruction', 'Early planning, cost modeling, and constructability reviews.'],
            ['General Contracting', 'Full‑service project delivery with a trusted team.'],
            ['Design‑Build', 'Integrated teams to accelerate schedules and reduce risk.'],
            ['Construction Management', 'Owners’ advocate from concept through closeout.'],
            ['Sustainability', 'LEED, net‑zero, and resilient construction strategies.'],
            ['Self‑Perform', 'Concrete, interiors, and selective trades self‑perform.'],
        ];
        foreach ($services as $i => [$name, $excerpt]) {
            Service::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'excerpt' => $excerpt,
                    'content' => $excerpt . "\n\nWe bring best‑in‑class safety, quality, and innovation to every engagement.",
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }

        $projects = [
            ['City Hospital Expansion', 'New York, NY', 'Healthcare', 40.713, -74.006],
            ['Tech Campus Phase II', 'Austin, TX', 'Commercial', 30.267, -97.743],
            ['International Terminal Renovation', 'Seattle, WA', 'Aviation', 47.606, -122.332],
        ];
        foreach ($projects as $i => [$title, $loc, $cat, $lat, $lng]) {
            Project::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'location' => $loc,
                    'category' => $cat,
                    'excerpt' => 'A complex, multi‑phase program delivered ahead of schedule.',
                    'status' => 'completed',
                    'is_featured' => $i < 2,
                    'completed_at' => now()->subMonths(6 + $i * 3),
                    'featured_image' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=1600&auto=format&fit=crop',
                    'lat' => $lat,
                    'lng' => $lng,
                ]
            );
        }

        $testimonials = [
            ['Alex Carter', 'Director of Facilities', 'HealthCo', 'They exceeded expectations on safety and schedule.'],
            ['Priya Singh', 'VP Real Estate', 'TechCorp', 'A proactive partner from day one.'],
            ['Miguel Alvarez', 'City Engineer', 'Port Authority', 'Complex logistics handled flawlessly.'],
        ];
        foreach ($testimonials as $i => [$name, $title, $company, $content]) {
            Testimonial::updateOrCreate(
                ['author_name' => $name, 'company' => $company],
                [
                    'author_title' => $title,
                    'content' => $content,
                    'rating' => 5,
                    'order' => $i,
                ]
            );
        }

        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'content' => "We are a full‑service construction company committed to safety, quality, and innovation.",
                'is_published' => true,
            ]
        );

        // Posts
        $news = [
            ['Safety Milestone Reached', 'We achieved 1M work hours without a lost-time incident.'],
            ['Sustainability Initiative', 'Our new net-zero jobsite pilot reduces emissions by 40%.'],
            ['Community Impact', 'Local apprenticeship program expands with 25 new trainees.'],
        ];
        foreach ($news as [$title, $excerpt]) {
            Post::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'body' => $excerpt . "\n\nLearn more about our efforts to build better.",
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1,30)),
                    'featured_image' => 'https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1600&auto=format&fit=crop',
                ]
            );
        }

        // Gallery items
        $gallery = [
            ['Tower Crane at Sunset','Cranes','https://images.unsplash.com/photo-1475483768296-6163e08872a1?q=80&w=1600&auto=format&fit=crop'],
            ['Steelwork Assembly','Structural','https://images.unsplash.com/photo-1581093458791-9d09d7285f30?q=80&w=1600&auto=format&fit=crop'],
            ['Interior Fit-out','Interiors','https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1600&auto=format&fit=crop'],
            ['Concrete Pour','Self-Perform','https://images.unsplash.com/photo-1522413452208-996ff3f3e740?q=80&w=1600&auto=format&fit=crop'],
            ['Airport Terminal','Aviation','https://images.unsplash.com/photo-1508057198894-247b23fe5ade?q=80&w=1600&auto=format&fit=crop'],
            ['Healthcare Expansion','Healthcare','https://images.unsplash.com/photo-1587351020409-1e3d54c0f0cc?q=80&w=1600&auto=format&fit=crop'],
        ];
        foreach ($gallery as $i => [$title, $cat, $img]) {
            GalleryItem::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'category' => $cat,
                    'image' => $img,
                    'order' => $i,
                    'is_published' => true,
                ]
            );
        }

        // Safety sample
        \App\Models\SafetyRecord::updateOrCreate(
            ['year' => (int) now()->format('Y') - 1],
            [
                'emr' => 0.62,
                'trir' => 0.90,
                'ltir' => 0.15,
                'total_hours' => 1000000,
                'osha_recordables' => 3,
                'description' => 'Industry-leading safety performance built on continuous training and hazard mitigation.'
            ]
        );

        // Badges / certifications
        $badges = [
            ['OSHA Certified', 'https://dummyimage.com/120x48/0b1220/94a3b8&text=OSHA', null],
            ['LEED AP', 'https://dummyimage.com/120x48/0b1220/94a3b8&text=LEED', 'https://www.usgbc.org/leed'],
            ['AGC Member', 'https://dummyimage.com/120x48/0b1220/94a3b8&text=AGC', 'https://www.agc.org/'],
        ];
        foreach ($badges as $i => [$name, $img, $url]) {
            Badge::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'image' => $img,
                    'url' => $url,
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}
