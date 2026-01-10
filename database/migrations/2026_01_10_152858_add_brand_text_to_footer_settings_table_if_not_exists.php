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
                if (!Schema::hasColumn('footer_settings', 'brand_text')) {
                    $table->string('brand_text')->default('V U S');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('footer_settings')) {
            Schema::table('footer_settings', function (Blueprint $table) {
                if (Schema::hasColumn('footer_settings', 'brand_text')) {
                    $table->dropColumn('brand_text');
                }
            });
        }
    }
};
