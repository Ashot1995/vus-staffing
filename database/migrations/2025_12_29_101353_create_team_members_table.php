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
        if (!Schema::hasTable('team_members')) {
            Schema::create('team_members', function (Blueprint $table) {
                $table->id();
                $table->string('member_key')->unique(); // team_member_1, team_member_2, team_member_3
                $table->string('name');
                $table->string('title')->nullable();
                $table->string('role')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            // Add missing columns if table exists
            Schema::table('team_members', function (Blueprint $table) {
                if (!Schema::hasColumn('team_members', 'member_key')) {
                    $table->string('member_key')->unique()->after('id');
                }
                if (!Schema::hasColumn('team_members', 'phone')) {
                    $table->string('phone')->nullable()->after('role');
                }
                if (!Schema::hasColumn('team_members', 'email')) {
                    $table->string('email')->nullable()->after('phone');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
