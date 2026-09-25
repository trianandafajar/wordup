<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'xp_total' => $this->xp_total,
            'current_streak' => $this->current_streak,
            'longest_streak' => $this->longest_streak,
            'lives' => $this->lives,
            'league' => $this->league,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
