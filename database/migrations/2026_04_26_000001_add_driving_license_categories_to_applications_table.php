<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('driving_license_category_1')->default(false)->after('driving_license_b');
            $table->boolean('driving_license_category_2')->default(false)->after('driving_license_category_1');
            $table->boolean('driving_license_category_3')->default(false)->after('driving_license_category_2');
            $table->boolean('driving_license_category_4')->default(false)->after('driving_license_category_3');
            $table->boolean('driving_license_category_5')->default(false)->after('driving_license_category_4');
            $table->boolean('driving_license_category_6')->default(false)->after('driving_license_category_5');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'driving_license_category_1',
                'driving_license_category_2',
                'driving_license_category_3',
                'driving_license_category_4',
                'driving_license_category_5',
                'driving_license_category_6',
            ]);
        });
    }
};
