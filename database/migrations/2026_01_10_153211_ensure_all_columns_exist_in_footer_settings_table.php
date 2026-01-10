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
        if (Schema::hasTable('footer_settings')) {
            Schema::table('footer_settings', function (Blueprint $table) {
                // Add brand_text if missing
                if (!Schema::hasColumn('footer_settings', 'brand_text')) {
                    $table->string('brand_text')->default('V U S');
                }

                // Add location_en if missing
                if (!Schema::hasColumn('footer_settings', 'location_en')) {
                    $table->string('location_en')->default('Sweden');
                }

                // Add location_sv if missing
                if (!Schema::hasColumn('footer_settings', 'location_sv')) {
                    $table->string('location_sv')->default('Sverige');
                }

                // Add email if missing (should exist, but check anyway)
                if (!Schema::hasColumn('footer_settings', 'email')) {
                    $table->string('email')->default('abdulrazek.mahmoud@vus-bemanning.se');
                }

                // Add quick_links_title_en if missing
                if (!Schema::hasColumn('footer_settings', 'quick_links_title_en')) {
                    $table->string('quick_links_title_en')->default('Quick links');
                }

                // Add quick_links_title_sv if missing
                if (!Schema::hasColumn('footer_settings', 'quick_links_title_sv')) {
                    $table->string('quick_links_title_sv')->default('Snabblänkar');
                }

                // Add quick_links if missing
                if (!Schema::hasColumn('footer_settings', 'quick_links')) {
                    $table->json('quick_links')->nullable();
                }

                // Add copyright_en if missing
                if (!Schema::hasColumn('footer_settings', 'copyright_en')) {
                    $table->text('copyright_en')->nullable();
                }

                // Add copyright_sv if missing
                if (!Schema::hasColumn('footer_settings', 'copyright_sv')) {
                    $table->text('copyright_sv')->nullable();
                }

                // Add is_active if missing (should exist from previous migration, but check anyway)
                if (!Schema::hasColumn('footer_settings', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop columns in case they were there before
        // This migration is safe to run multiple times
    }
};
