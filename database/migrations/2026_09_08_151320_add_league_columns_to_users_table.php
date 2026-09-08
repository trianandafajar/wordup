<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('league')->nullable()->index();
            $table->unsignedInteger('league_week_xp')->default(0);
            $table->timestamp('league_week_started_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['league', 'league_week_xp', 'league_week_started_at']);
        });
    }
};
