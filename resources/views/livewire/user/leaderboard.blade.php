@extends('layouts.user', ['pageTitle' => 'Leaderboard', 'activeMenu' => 'rank'])

@section('content')
<div class="w-full">

    <!-- League Header -->
    <div class="text-center mb-8">
        <div class="inline-flex p-4 {{ $currentUserLeague['bg'] }} rounded-full {{ $currentUserLeague['color'] }} mb-3">
            <x-heroicon-s-trophy class="w-10 h-10" />
        </div>
        <h1 class="text-2xl font-extrabold text-gray-900">{{ $currentUserLeague['name'] }} League</h1>
        <p class="text-xs {{ $currentUserLeague['color'] }} mt-1 flex items-center justify-center gap-1">
            Posisi #{{ $currentUserLeagueRank }} dari {{ $totalInLeague }} user
        </p>
    </div>

    <!-- Your Position (League View) -->
    <div
        class="{{ $currentUserLeague['bg'] }} border-2 {{ $currentUserLeague['border'] }} rounded-2xl p-4 mb-8 flex items-center gap-4">
        <span class="text-lg font-bold {{ $currentUserLeague['color'] }} w-8 text-center">#{{ $currentUserLeagueRank
            }}</span>
        <div
            class="w-12 h-12 rounded-full {{ $currentUserLeague['icon'] }} text-white flex items-center justify-center text-lg font-bold shadow-md">
            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <p class="font-bold text-gray-900">{{ $currentUser->name }}</p>
            <p class="text-xs {{ $currentUserLeague['color'] }}">{{ number_format($currentUser->xp_total) }} XP</p>
        </div>
        <span
            class="text-xs font-bold {{ $currentUserLeague['bg'] }} {{ $currentUserLeague['color'] }} px-3 py-1 rounded-full border border-current">Kamu</span>
    </div>

    <!-- Leaderboard -->
    <div class="bg-white rounded-3xl border border-gray-200 divide-y divide-gray-100 overflow-hidden shadow-sm">
        @foreach (($usersByLeague[$currentUserLeague['key']] ?? collect()) as $i => $user)
        <div
            class="flex items-center gap-4 p-4 {{ $user['id'] === $currentUser->id ? 'bg-brand-50' : 'hover:bg-gray-50' }} transition-colors">
            <span class="text-sm font-bold {{ $i < 3 ? 'text-amber-500' : 'text-gray-400' }} w-8 text-center">{{ $i + 1
                }}</span>
            <div
                class="w-10 h-10 rounded-full {{ $currentUserLeague['bg'] }} {{ $currentUserLeague['color'] }} flex items-center justify-center font-bold shadow-sm text-sm">
                {{ strtoupper(substr($user['name'], 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800">{{ $user['name'] }}</p>
                <p class="text-xs text-gray-500">{{ number_format($user['xp']) }} XP</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Promotion Info -->
    <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center">
        <p class="text-emerald-600 font-bold text-sm">
            Top {{ $promotionCount }} akan dipromosikan ke league berikutnya!
        </p>
    </div>

</div>
@endsection