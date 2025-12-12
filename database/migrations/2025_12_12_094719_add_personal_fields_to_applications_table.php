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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('surname')->nullable()->after('first_name');
            $table->date('date_of_birth')->nullable()->after('surname');
            $table->string('phone')->nullable()->after('date_of_birth');
            $table->text('address')->nullable()->after('phone');
            $table->string('personal_image_path')->nullable()->after('cv_path');
            $table->boolean('driving_license_b')->default(false)->after('personal_image_path');
            $table->boolean('driving_license_own_car')->default(false)->after('driving_license_b');
            $table->text('other')->nullable()->after('driving_license_own_car');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'surname',
                'date_of_birth',
                'phone',
                'address',
                'personal_image_path',
                'driving_license_b',
                'driving_license_own_car',
                'other',
            ]);
        });
    }
};
