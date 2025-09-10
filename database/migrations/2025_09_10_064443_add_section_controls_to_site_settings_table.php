<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Section visibility controls
            $table->boolean('show_services_section')->default(true);
            $table->boolean('show_projects_section')->default(true);
            $table->boolean('show_testimonials_section')->default(true);
            $table->boolean('show_clients_section')->default(true);
            $table->boolean('show_news_section')->default(true);
            $table->boolean('show_badges_section')->default(true);
            
            // Section headings
            $table->string('services_section_heading')->default('Services');
            $table->string('projects_section_heading')->default('Featured Projects');
            $table->string('testimonials_section_heading')->default('What clients say');
            $table->string('clients_section_heading')->default('Our Clients');
            $table->string('news_section_heading')->default('Latest News');
            $table->string('badges_section_heading')->default('Certifications & Affiliations');
            
            // Section limits
            $table->integer('services_limit')->default(6);
            $table->integer('projects_limit')->default(6);
            $table->integer('testimonials_limit')->default(6);
            $table->integer('news_limit')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_services_section',
                'show_projects_section',
                'show_testimonials_section',
                'show_clients_section',
                'show_news_section',
                'show_badges_section',
                'services_section_heading',
                'projects_section_heading',
                'testimonials_section_heading',
                'clients_section_heading',
                'news_section_heading',
                'badges_section_heading',
                'services_limit',
                'projects_limit',
                'testimonials_limit',
                'news_limit',
            ]);
        });
    }
};
