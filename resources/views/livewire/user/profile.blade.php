@extends('layouts.user', ['pageTitle' => 'Profile', 'activeMenu' => 'profile'])

@section('content')
<div class="w-full">

    <!-- Avatar & Stats -->
    <div class="grid lg:grid-cols-12 gap-8">
        <!-- Left: Avatar & Name -->
        <div class="lg:col-span-6 text-center">
            <div
                class="w-32 h-32 bg-gradient-to-br from-brand-500 to-brand-600 rounded-full flex items-center justify-center mx-auto text-4xl font-bold text-white mb-4">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500 mb-6">{{ auth()->user()->email }}</p>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <p class="text-xs text-gray-500">Total XP</p>
                    <p class="text-xl font-bold text-amber-500 mt-1">{{ number_format($totalXp) }}</p>
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <p class="text-xs text-gray-500">Streak</p>
                    <p class="text-xl font-bold text-orange-500 mt-1">{{ $currentStreak }} Hari</p>
                </div>
            </div>

            <!-- Achievements -->
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-3">Perolehan</p>
                <div class="grid grid-cols-3 gap-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <x-heroicon-s-star class="w-4 h-4" /> {{ $completedLessonsCount }} Pelajaran
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <x-heroicon-s-fire class="w-4 h-4" /> {{ $currentStreak }} Hari
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <x-heroicon-s-sparkles class="w-4 h-4" /> {{ number_format($totalXp) }} XP
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Badges -->
        <div class="lg:col-span-6">
            <div class="grid grid-cols-2 gap-3 mb-6">
                @foreach ($badges as $badge)
                <div class="bg-white rounded-xl p-3 border border-gray-200 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full {{ $badge['color'] }} flex items-center justify-center font-bold">
                        @if ($badge['icon'] === 'book-open')
                        <x-heroicon-s-book-open class="w-5 h-5" />
                        @elseif ($badge['icon'] === 'sparkles')
                        <x-heroicon-s-sparkles class="w-5 h-5" />
                        @elseif ($badge['icon'] === 'fire')
                        <x-heroicon-s-fire class="w-5 h-5" />
                        @elseif ($badge['icon'] === 'trophy')
                        <x-heroicon-s-trophy class="w-5 h-5" />
                        @elseif ($badge['icon'] === 'user')
                        <x-heroicon-s-user class="w-5 h-5" />
                        @endif
                    </span>
                    <div>
                        <p class="font-bold text-gray-900">{{ $badge['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $badge['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white rounded-3xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-900 text-sm mb-4">Aktivitas Terbaru</h2>
                <div class="space-y-2 text-xs text-gray-500">
                    @foreach ($recentActivities as $activity)
                    <div class="flex items-center gap-3">
                        <span
                            class="w-3 h-3 rounded-full {{ $activity->status === 'completed' ? 'bg-brand-500' : 'bg-gray-400' }}"></span>
                        <span>{{ $activity->lesson->title }}</span>
                        <span class="text-gray-300">{{ $activity->completed_at ? $activity->completed_at->format('H:i')
                            : '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Nav is handled separately in layouts -->
</div>
@endsection