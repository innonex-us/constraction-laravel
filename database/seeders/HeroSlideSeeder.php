<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Building Excellence Since 1999',
                'subtitle' => 'Trusted Construction Partner',
                'description' => 'From groundbreaking to ribbon cutting, we deliver construction projects that stand the test of time. Our commitment to quality and safety sets us apart.',
                'image' => 'https://images.unsplash.com/photo-1581091870686-8e2980a57f5b?q=80&w=1920&auto=format&fit=crop',
                'button_text' => 'View Our Projects',
                'button_url' => '/projects',
                'button_style' => 'primary',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Innovative Construction Solutions',
                'subtitle' => 'Modern Technology Meets Craftsmanship',
                'description' => 'We leverage cutting-edge technology and time-tested construction methods to deliver exceptional results on every project.',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1920&auto=format&fit=crop',
                'button_text' => 'Our Services',
                'button_url' => '/services',
                'button_style' => 'secondary',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Safety First, Quality Always',
                'subtitle' => 'Award-Winning Safety Record',
                'description' => 'With an EMR of 0.62, we prioritize safety without compromising on quality. Every project is completed on time and within budget.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1920&auto=format&fit=crop',
                'button_text' => 'Safety Record',
                'button_url' => '/safety',
                'button_style' => 'outline',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}