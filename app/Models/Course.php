<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'language_target', 'level', 'is_active'])]
class Course extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class)->orderBy('order');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserCourseProgress::class);
    }
}
