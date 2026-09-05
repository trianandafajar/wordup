<?php

use App\Enums\AttemptStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('attempt_number');
            $table->unsignedTinyInteger('score')->default(0);
            $table->enum('status', AttemptStatusEnum::getAllValues())
                ->default(AttemptStatusEnum::InProgress->value);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id', 'attempt_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_attempts');
    }
};
