<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{Service, Project, Testimonial, SiteSetting, Page, Post};

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
            ['City Hospital Expansion', 'New York, NY'],
            ['Tech Campus Phase II', 'Austin, TX'],
            ['International Terminal Renovation', 'Seattle, WA'],
        ];
        foreach ($projects as $i => [$title, $loc]) {
            Project::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'location' => $loc,
                    'excerpt' => 'A complex, multi‑phase program delivered ahead of schedule.',
                    'status' => 'completed',
                    'is_featured' => $i < 2,
                    'completed_at' => now()->subMonths(6 + $i * 3),
                    'featured_image' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=1600&auto=format&fit=crop',
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
    }
}
