<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'xp_total', 'current_streak', 'longest_streak', 'last_activity_date', 'lives', 'last_life_refill_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'date',
            'last_life_refill_at' => 'datetime',
        ];
    }

    public function onboarding(): HasOne
    {
        return $this->hasOne(UserOnboarding::class);
    }

    public function streakLogs(): HasMany
    {
        return $this->hasMany(StreakLog::class);
    }

    public function courseProgress(): HasMany
    {
        return $this->hasMany(UserCourseProgress::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(UserLessonProgress::class);
    }

    public function lessonAttempts(): HasMany
    {
        return $this->hasMany(LessonAttempt::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
}
