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
        Schema::create('prequalifications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('trade')->nullable();
            $table->string('license_number')->nullable();
            $table->unsignedSmallInteger('years_in_business')->nullable();
            $table->unsignedBigInteger('annual_revenue')->nullable();
            $table->unsignedBigInteger('bonding_capacity')->nullable();
            $table->decimal('emr', 4, 2)->nullable();
            $table->decimal('trir', 5, 2)->nullable();
            $table->string('safety_contact')->nullable();
            $table->string('insurance_carrier')->nullable();
            $table->string('coverage')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prequalifications');
    }
};
