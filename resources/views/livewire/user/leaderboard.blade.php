@extends('layouts.user', ['pageTitle' => 'Rank', 'activeMenu' => 'rank'])

@section('content')
@php
$allRanks = $allRanks ?? \App\Services\RankService::RANKS;
$currentIdx = $currentRankIndex ?? (new \App\Services\RankService)->getRankIndex($currentRank['key']);
@endphp

<div class="w-full" x-data="{ open: false }">

    @php $total = count($allRanks); @endphp
    <div class="flex items-center justify-center gap-4 sm:gap-6 overflow-hidden pt-6 pb-4"
        style="-webkit-mask-image:linear-gradient(to right,transparent,#000 12%,#000 88%,transparent);mask-image:linear-gradient(to right,transparent,#000 12%,#000 88%,transparent);">
        @foreach ([-2, -1, 0, 1, 2] as $offset)
        @php
        $i = (($currentIdx + $offset) % $total + $total) % $total;
        $rank = $allRanks[$i];
        @endphp

        @if ($offset === 0)
        <button type="button" @click="open = true"
            class="shrink-0 w-24 flex flex-col items-center gap-1.5 relative cursor-pointer">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-24 bg-brand-500/10 rounded-full animate-pulse">
            </div>
            <img src="{{ asset('images/rank-icon/' . $rank['icon']) }}" alt="{{ $rank['name'] }}"
                class="w-24 h-24 object-contain relative z-10">
            <span class="text-sm font-extrabold text-brand-600">{{ $rank['name'] }}</span>
            <span class="text-[10px] font-bold bg-white text-brand-700 px-2 py-0.5 rounded-full border border-brand-100">Aktif</span>
        </button>
        @else
        <button type="button" @click="open = true"
            class="shrink-0 w-16 flex flex-col items-center gap-1.5 cursor-pointer transition-opacity hover:opacity-100 {{ $i < $currentIdx ? 'opacity-50 grayscale' : 'opacity-60' }}">
            <img src="{{ asset('images/rank-icon/' . $rank['icon']) }}" alt="{{ $rank['name'] }}"
                class="w-14 h-14 object-contain">
            <span class="text-xs font-semibold text-gray-500">{{ $rank['name'] }}</span>
        </button>
        @endif
        @endforeach
    </div>

    <p class="text-center text-sm text-gray-500">
        Total XP: <span class="font-bold text-gray-900">{{ number_format($currentUser->xp_total) }}</span>
    </p>

    <div class="bg-white rounded-3xl border border-gray-200 divide-y divide-gray-100 overflow-hidden mt-4">
        <div class="p-4 flex items-center gap-4 bg-emerald-50">
            <span class="text-lg font-bold text-emerald-600 w-8 text-center">#{{ $currentUserLeagueRank }}</span>
            @if ($currentUser->avatar)
            <img src="{{ asset('storage/' . $currentUser->avatar) }}" alt="{{ $currentUser->name }}"
                class="w-12 h-12 rounded-full object-cover shadow-md">
            @else
            <div
                class="w-12 h-12 rounded-full {{ $currentUserLeague['icon'] }} text-white flex items-center justify-center text-lg font-bold shadow-md">
                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-900">{{ $currentUser->name }}</p>
                <p class="text-xs text-emerald-600 flex items-center gap-1">
                    {{ number_format($currentUser->league_week_xp > 0 ? $currentUser->league_week_xp :
                    $currentUser->xp_total) }} XP
                    <span class="bg-emerald-100 text-emerald-700 text-[10px] px-1.5 py-0.5 rounded">Mingguan</span>
                </p>
            </div>
            <span
                class="text-xs font-bold bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full border border-current">Kamu</span>
        </div>

        @forelse ($leagueUsers as $i => $user)
        @if ($user['id'] !== $currentUser->id)
        <div
            class="flex items-center gap-4 p-4 {{ $i < 3 ? 'text-emerald-600' : 'text-gray-400' }} hover:bg-gray-50 transition-colors">
            <span class="text-sm font-bold w-8 text-center">{{ $user['rank'] }}</span>
            @if ($user['avatar'])
            <img src="{{ asset('storage/' . $user['avatar']) }}" alt="{{ $user['name'] }}"
                class="w-10 h-10 rounded-full object-cover shadow-sm">
            @else
            <div
                class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold shadow-sm text-sm">
                {{ strtoupper(substr($user['name'], 0, 1)) }}
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800">{{ $user['name'] }}</p>
                <p class="text-xs text-gray-500">{{ number_format($user['xp']) }} XP
                    <span class="bg-emerald-100 text-emerald-700 text-[10px] px-1.5 py-0.5 rounded">Mingguan</span>
                </p>
            </div>
        </div>
        @endif
        @empty
        <p class="text-center text-gray-400 py-6">Belum ada user lain di league ini</p>
        @endforelse
    </div>

    {{-- Modal --}}
    <div x-show="open" x-transition.opacity style="display:none;"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-xl" @click.away="open = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-lg">Daftar Rank</h3>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach ($allRanks as $index => $rank)
                <div
                    class="flex items-center gap-4 p-3 rounded-2xl {{ $index === $currentIdx ? 'bg-brand-50 border border-brand-200' : 'bg-gray-50 border border-gray-100' }}">
                    <img src="{{ asset('images/rank-icon/' . $rank['icon']) }}" alt="{{ $rank['name'] }}"
                        class="w-12 h-12 object-contain {{ $index > $currentIdx ? 'grayscale opacity-50' : '' }}">
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 text-sm">{{ $rank['name'] }}</p>
                        <p class="text-xs text-gray-500">Butuh {{ number_format($rank['min_xp']) }} XP</p>
                    </div>
                    @if ($index <= $currentIdx) <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                        </svg>
                        @else
                        <span class="text-xs font-bold text-gray-400">Lock</span>
                        @endif
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">XP kamu: <span class="font-bold text-gray-900">{{
                        number_format($currentUser->xp_total) }}</span></p>
                @php $nextRank = $allRanks[$currentIdx + 1] ?? null; @endphp
                @if ($nextRank)
                <p class="text-xs text-gray-500 mt-1">Butuh {{ number_format($nextRank['min_xp'] -
                    $currentUser->xp_total) }} XP lagi untuk <span class="font-semibold text-gray-700">{{
                        $nextRank['name'] }}</span></p>
                @else
                <p class="text-xs text-brand-600 mt-1 font-bold">Rank tertinggi tercapai!</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection