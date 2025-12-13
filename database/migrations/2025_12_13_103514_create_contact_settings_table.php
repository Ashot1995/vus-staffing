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
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->string('email')->default('info@vus-bemanning.se');
            $table->string('phone')->nullable();
            $table->text('address_en')->nullable();
            $table->text('address_sv')->nullable();
            $table->string('hours_weekdays_en')->nullable();
            $table->string('hours_weekdays_sv')->nullable();
            $table->string('hours_weekend_en')->nullable();
            $table->string('hours_weekend_sv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
