<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'last_activity_at') && ! Schema::hasColumn('users', 'last_activity_date')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('last_activity_at', 'last_activity_date');
            });
        }

        if (Schema::hasTable('streak_logs') && Schema::hasColumn('streak_logs', 'xp_earning_that_day') && ! Schema::hasColumn('streak_logs', 'xp_earned_that_day')) {
            Schema::table('streak_logs', function (Blueprint $table) {
                $table->renameColumn('xp_earning_that_day', 'xp_earned_that_day');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'last_activity_date') && ! Schema::hasColumn('users', 'last_activity_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('last_activity_date', 'last_activity_at');
            });
        }

        if (Schema::hasTable('streak_logs') && Schema::hasColumn('streak_logs', 'xp_earned_that_day') && ! Schema::hasColumn('streak_logs', 'xp_earning_that_day')) {
            Schema::table('streak_logs', function (Blueprint $table) {
                $table->renameColumn('xp_earned_that_day', 'xp_earning_that_day');
            });
        }
    }
};
