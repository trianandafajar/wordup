<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->document['x-tagGroups'] = [
                [
                    'name' => 'Admin',
                    'tags' => [
                        'Admin Dashboard',
                        'Admin Users',
                        'Admin Courses',
                        'Admin Units',
                        'Admin Lessons',
                        'Admin Questions',
                        'Admin Question Options',
                        'Admin Question Answers',
                    ],
                ],
                [
                    'name' => 'User',
                    'tags' => [
                        'Auth',
                        'Course',
                        'Dashboard',
                        'Leaderboard',
                        'Lesson',
                        'Onboarding',
                        'PasswordReset',
                        'Profile',
                        'Question',
                        'QuestionAnswer',
                        'QuestionOption',
                        'SkillTree',
                        'Unit',
                        'User',
                    ],
                ],
            ];
        });
    }
}
