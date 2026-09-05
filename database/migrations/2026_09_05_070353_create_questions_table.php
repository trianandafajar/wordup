<?php

use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->enum('type', QuestionTypeEnum::getAllValues());
            $table->enum('difficulty_level', QuestionDifficultyEnum::getAllValues())
                ->default(QuestionDifficultyEnum::Beginner->value);
            $table->text('question_text');
            $table->string('audio_url', 2048)->nullable();
            $table->unsignedInteger('order');
            $table->timestamps();

            $table->unique(['lesson_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
