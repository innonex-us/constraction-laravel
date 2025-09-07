<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_nav')->default(false)->after('is_published');
            $table->integer('nav_order')->default(0)->after('show_in_nav');
            $table->index(['show_in_nav', 'nav_order']);
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['show_in_nav', 'nav_order']);
            $table->dropColumn(['show_in_nav', 'nav_order']);
        });
    }
};

