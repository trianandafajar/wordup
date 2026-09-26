<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;

#[Group('Admin Dashboard')]
class DashboardController extends Controller
{
    public function __invoke(): array
    {
        return [
            'data' => [
                'users_count' => User::role('user')->count(),
                'courses_count' => Course::count(),
                'lessons_count' => Lesson::count(),
                'recent_users' => User::role('user')->latest()->limit(5)->get([
                    'id', 'name', 'email', 'created_at',
                ]),
            ],
        ];
    }
}
