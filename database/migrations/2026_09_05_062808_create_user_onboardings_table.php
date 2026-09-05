<?php

use App\Enums\LearningGoalEnum;
use App\Enums\ProficiencyLevelEnum;
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
        Schema::create('user_onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('target_language');
            $table->enum('learning_goal', LearningGoalEnum::getAllValues())->default(LearningGoalEnum::BEGINNER->value);
            $table->enum('proficiency_level', ProficiencyLevelEnum::getAllValues())->default(ProficiencyLevelEnum::BEGINNER->value);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_onboardings');
    }
};
