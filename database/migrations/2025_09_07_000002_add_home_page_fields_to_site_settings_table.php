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
            $table->text('subheadline')->nullable();
            $table->string('stat_years')->nullable();
            $table->string('stat_projects')->nullable();
            $table->string('stat_emr')->nullable();
            $table->string('cta_heading')->nullable();
            $table->text('cta_text')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->string('cta_button_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'subheadline',
                'stat_years',
                'stat_projects',
                'stat_emr',
                'cta_heading',
                'cta_text',
                'cta_button_text',
                'cta_button_url',
            ]);
        });
    }
};
