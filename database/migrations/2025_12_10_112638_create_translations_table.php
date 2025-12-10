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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key'); // e.g., 'nav.home', 'home.hero.title'
            $table->string('locale', 10); // 'en' or 'sv'
            $table->text('value'); // The translated text
            $table->string('group')->default('messages'); // Translation group (messages, validation, etc.)
            $table->timestamps();

            // Unique constraint: same key, locale, and group
            $table->unique(['key', 'locale', 'group']);

            // Index for faster lookups
            $table->index(['locale', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
