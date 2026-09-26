<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rank' => $this['rank'] ?? null,
            'id' => $this['id'] ?? null,
            'name' => $this['name'] ?? null,
            'xp' => $this['xp'] ?? 0,
            'total_xp' => $this['total_xp'] ?? 0,
            'avatar' => isset($this['avatar']) && $this['avatar'] ? asset('storage/'.$this['avatar']) : null,
        ];
    }
}
