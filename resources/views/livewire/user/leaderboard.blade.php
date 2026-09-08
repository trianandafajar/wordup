@extends('layouts.user', ['pageTitle' => 'Leaderboard', 'activeMenu' => 'rank'])

@section('content')
    <div class="w-full">

        <!-- League Header -->
        <div class="text-center mb-8">
            <div class="inline-flex p-4 bg-amber-100 rounded-full text-amber-600 mb-3">
                <x-heroicon-s-trophy class="w-10 h-10" />
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900">Leaderboard Mingguan</h1>
            <p class="text-xs text-amber-600 mt-1 flex items-center justify-center gap-1">Pertahankan posisimu! <x-heroicon-s-arrow-up class="w-4 h-4 inline" /></p>
        </div>

        <!-- Podium Top 3 -->
        @if (count($topUsers) >= 3)
            <div class="flex items-end justify-center gap-3 mb-8 px-4">
                <!-- 2nd Place -->
                <div class="flex flex-col items-center w-24">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-600 border-4 border-white shadow-md">
                            {{ strtoupper(substr($topUsers[1]['name'], 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-bold shadow">2</span>
                    </div>
                    <p class="text-xs font-bold text-gray-700 mt-2 truncate w-full text-center">{{ $topUsers[1]['name'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ number_format($topUsers[1]['xp']) }} XP</p>
                    <div class="w-full h-20 bg-gray-200 rounded-t-xl mt-2 flex items-center justify-center">
                        <x-heroicon-s-trophy class="w-10 h-10 text-amber-500" />
                    </div>
                </div>
                <!-- 1st Place -->
                <div class="flex flex-col items-center w-28">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center text-3xl font-bold text-amber-600 border-4 border-amber-300 shadow-lg shadow-amber-200">
                            {{ strtoupper(substr($topUsers[0]['name'], 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-7 h-7 bg-amber-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow">1</span>
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-2xl text-amber-500"><x-heroicon-s-star class="w-6 h-6" /></span>
                    </div>
                    <p class="text-xs font-bold text-amber-700 mt-2 truncate w-full text-center">{{ $topUsers[0]['name'] }}</p>
                    <p class="text-[10px] text-amber-600">{{ number_format($topUsers[0]['xp']) }} XP</p>
                    <div class="w-full h-28 bg-amber-100 rounded-t-xl mt-2 flex items-center justify-center">
                        <x-heroicon-s-trophy class="w-12 h-12 text-amber-500" />
                    </div>
                </div>
                <!-- 3rd Place -->
                <div class="flex flex-col items-center w-24">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-2xl font-bold text-orange-600 border-4 border-white shadow-md">
                            {{ strtoupper(substr($topUsers[2]['name'], 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow">3</span>
                    </div>
                    <p class="text-xs font-bold text-gray-700 mt-2 truncate w-full text-center">{{ $topUsers[2]['name'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ number_format($topUsers[2]['xp']) }} XP</p>
                    <div class="w-full h-14 bg-orange-100 rounded-t-xl mt-2 flex items-center justify-center">
                        <x-heroicon-s-trophy class="w-10 h-10 text-amber-500" />
                    </div>
                </div>
            </div>
        @endif

        <!-- Your Position -->
        <div class="bg-brand-50 border-2 border-brand-300 rounded-2xl p-4 mb-4 flex items-center gap-4">
            <span class="text-lg font-bold text-brand-600 w-8">#{{ $currentUserRank }}</span>
            <div class="w-12 h-12 rounded-full bg-brand-500 text-white flex items-center justify-center text-lg font-bold shadow-md">
                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-900">{{ $currentUser->name }}</p>
                <p class="text-xs text-brand-600">{{ number_format($currentUser->xp_total) }} XP</p>
            </div>
            <span class="text-xs font-bold bg-brand-100 text-brand-700 px-3 py-1 rounded-full"> Kamu</span>
        </div>

        <!-- Ranking List -->
        <div class="bg-white rounded-3xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
            @foreach ($topUsers as $i => $user)
                @if ($i >= 3) <!-- Skip top 3 as they are on podium -->
                    <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors">
                        <span class="text-sm font-bold text-gray-400 w-8 text-center">{{ $user['rank'] }}</span>
                        <div class="w-10 h-10 rounded-full bg-{{ ['pink-100 text-pink-600', 'purple-100 text-purple-600', 'blue-100 text-blue-600', 'teal-100 text-teal-600', 'indigo-100 text-indigo-600', 'rose-100 text-rose-600'][$i % 6] }} flex items-center justify-center font-bold shadow-sm text-sm">
                            {{ strtoupper(substr($user['name'], 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800">{{ $user['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($user['xp']) }} XP</p>
                        </div>
                        <div class="h-1.5 w-16 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-400 rounded-full" style="width: {{ ($user['xp'] / $topUsers[0]['xp']) * 100 }}%"></div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Promotion Info -->
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center">
            <p class="text-amber-600 font-bold text-sm">Top 10 akan dipromosikan ke Gold League</p>
            <p class="text-xs text-amber-500 mt-1 flex items-center justify-center gap-1">Pertahankan posisimu! <x-heroicon-s-arrow-up class="w-4 h-4 inline" /></p>
        </div>

    </div>
@endsection