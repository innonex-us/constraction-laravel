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
        Schema::create('safety_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->decimal('emr', 4, 2)->nullable();
            $table->decimal('trir', 5, 2)->nullable();
            $table->decimal('ltir', 5, 2)->nullable();
            $table->unsignedBigInteger('total_hours')->nullable();
            $table->unsignedInteger('osha_recordables')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_records');
    }
};
