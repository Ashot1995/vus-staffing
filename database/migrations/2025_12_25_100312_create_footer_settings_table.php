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
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('brand_text')->default('V U S');
            $table->string('location_en')->default('Sweden');
            $table->string('location_sv')->default('Sverige');
            $table->string('email')->default('abdulrazek.mahmoud@vus-bemanning.se');
            $table->string('quick_links_title_en')->default('Quick links');
            $table->string('quick_links_title_sv')->default('Snabblänkar');
            
            // Quick Links (JSON array)
            $table->json('quick_links')->nullable();
            
            $table->text('copyright_en')->nullable();
            $table->text('copyright_sv')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
