<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnglishContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $seeders = [
                EnglishUnit1Seeder::class,
                EnglishUnit2Seeder::class,
                EnglishUnit3Seeder::class,
                EnglishUnit4Seeder::class,
                EnglishUnit5Seeder::class,
                EnglishUnit6Seeder::class,
                EnglishUnit7Seeder::class,
                EnglishUnit8Seeder::class,
                EnglishUnit9Seeder::class,
                EnglishUnit10Seeder::class,
            ];
            Course::query()->delete();

            Course::query()->updateOrCreate(
                ['title' => 'Complete English Mastery'],
                [
                    'description' => 'Master English from absolute beginner (A1) to expert (C2). Structured curriculum covering grammar, vocabulary, conversation, and professional communication.',
                    'language_target' => 'English',
                    'level' => 'beginner',
                    'is_active' => true,
                ]
            );

            foreach ($seeders as $seederClass) {
                (new $seederClass)->run();
            }
        });
    }
}
