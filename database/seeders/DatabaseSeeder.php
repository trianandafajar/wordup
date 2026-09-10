<?php

namespace Database\Seeders;

use App\Enums\LessonProgressStatusEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserCourseProgress;
use App\Models\UserLessonProgress;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            EnglishContentSeeder::class,
        ]);
    }
}
