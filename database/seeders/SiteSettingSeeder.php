<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Construction Co.',
                'primary_color' => '#10B981',
                'secondary_color' => '#0EA5E9',
                'headline' => 'Building the future with precision and care.',
                'subheadline' => 'From preconstruction to delivery, we provide end‑to‑end construction services across markets.',
                'hero_video_url' => null,
                'hero_image' => null,
                'stat_years' => '25+',
                'stat_projects' => '500+',
                'stat_emr' => '0.62',
                'cta_heading' => 'Ready to build something great?',
                'cta_text' => 'Let\'s discuss your project and how we can help.',
                'cta_button_text' => 'Get in touch',
                'cta_button_url' => '/contact',
                
                // Section controls
                'show_services_section' => true,
                'show_projects_section' => true,
                'show_testimonials_section' => true,
                'show_clients_section' => true,
                'show_news_section' => true,
                'show_badges_section' => true,
                
                // Section headings
                'services_section_heading' => 'Services',
                'projects_section_heading' => 'Featured Projects',
                'testimonials_section_heading' => 'What clients say',
                'clients_section_heading' => 'Our Clients',
                'news_section_heading' => 'Latest News',
                'badges_section_heading' => 'Certifications & Affiliations',
                
                // Section limits
                'services_limit' => 6,
                'projects_limit' => 6,
                'testimonials_limit' => 6,
                'news_limit' => 3,
                
                'theme' => 'default',
            ]
        );
    }
}
