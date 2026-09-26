<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Group('Admin Users')]
class UserController extends Controller
{
    public function index(): array
    {
        return [
            'data' => User::role('user')
                ->latest()
                ->paginate(15)
                ->through(fn (User $user): array => $this->userData($user)),
        ];
    }

    public function show(User $user): array|JsonResponse
    {
        if (! $user->hasRole('user')) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return ['data' => $this->userData($user)];
    }

    public function update(Request $request, User $user): array|JsonResponse
    {
        if (! $user->hasRole('user')) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'xp_total' => ['sometimes', 'required', 'integer', 'min:0'],
            'current_streak' => ['sometimes', 'required', 'integer', 'min:0'],
            'longest_streak' => ['sometimes', 'required', 'integer', 'min:0'],
            'lives' => ['sometimes', 'required', 'integer', 'min:0'],
            'last_activity_date' => ['sometimes', 'nullable', 'date'],
            'email_verified_at' => ['sometimes', 'nullable', 'date'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'max:255'],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return ['data' => $this->userData($user->refresh())];
    }

    public function destroy(User $user): JsonResponse
    {
        if (! $user->hasRole('user')) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted.']);
    }

    /** @return array<string, mixed> */
    private function userData(User $user): array
    {
        return $user->only([
            'id', 'name', 'email', 'xp_total', 'current_streak', 'longest_streak',
            'lives', 'last_activity_date', 'email_verified_at', 'created_at', 'updated_at',
        ]);
    }
}
