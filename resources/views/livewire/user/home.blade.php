@extends('layouts.user', ['pageTitle' => 'Home', 'activeMenu' => 'home'])

@section('content')
<div class="w-full">
    <div
        class="bg-gradient-to-br from-brand-500 to-brand-600 text-white rounded-3xl p-6 shadow-lg shadow-brand-500/30 overflow-hidden relative">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="absolute -left-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="relative">
            <p class="text-sm opacity-90 flex items-center gap-1.5">
                Streak aktif:
                <span class="bg-white/20 px-2.5 py-0.5 rounded-full font-bold inline-flex items-center gap-1">
                    {{ $user->current_streak }} hari
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd"
                            d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </p>
            <h1 class="text-2xl font-extrabold mt-3">Lanjutkan Belajarmu!</h1>
            <p class="text-sm opacity-90 mt-1">Masih ada {{ max(0, $totalLessons - $completedLessons) }} pelajaran yang
                menunggumu.</p>
            <div class="mt-5 flex items-center gap-3">
                <a href="{{ route('user.learn') }}"
                    class="inline-flex items-center gap-2 bg-white text-brand-600 font-bold px-6 py-3 rounded-2xl hover:bg-brand-50 transition-colors shadow-md">
                    Lanjut Belajar
                </a>
                @if ($progressPercent > 0)
                <div class="flex-1">
                    <div class="flex justify-between text-[11px] font-semibold mb-1 opacity-90">
                        <span>Progress</span>
                        <span>{{ $progressPercent }}%</span>
                    </div>
                    <div class="h-2.5 bg-white/25 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full transition-all duration-500"
                            style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-3 mt-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center">
            <div class="text-orange-500 font-extrabold text-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 inline">
                    <path fill-rule="evenodd"
                        d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $user->current_streak }}</p>
            <p class="text-xs text-gray-500">Streak</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center">
            <div class="text-amber-500 font-extrabold text-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 inline">
                    <path fill-rule="evenodd"
                        d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z"
                        clip-rule="evenodd" />
                </svg>

            </div>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ number_format($user->xp_total) }}</p>
            <p class="text-xs text-gray-500">XP</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center">
            <div class="text-rose-500 font-extrabold text-xl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 inline">
                    <path
                        d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                </svg>
            </div>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $user->lives }}</p>
            <p class="text-xs text-gray-500">Lives</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-200 mt-6">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-lg">Progres Kursus</h2>
            <span class="text-sm font-bold text-brand-600">{{ $progressPercent }}%</span>
        </div>
        <div class="mt-4 h-4 w-full bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-brand-400 to-brand-500 rounded-full"
                style="width: {{ $progressPercent }}%"></div>
        </div>
        <div class="flex items-center justify-between mt-3 text-sm text-gray-500">
            <span>{{ $completedLessons }} dari {{ $totalLessons }} lesson selesai</span>
            <a href="{{ route('user.learn') }}" class="text-brand-600 font-semibold hover:text-brand-700">Detail →</a>
        </div>
    </div>

    <h2 class="font-bold text-gray-900 text-lg mt-8 mb-4">Unit Kamu</h2>
    <div class="space-y-3">
        @foreach ($units as $i => $unit)
        <div
            class="bg-white rounded-3xl p-5 border border-gray-200 flex items-center gap-4 {{ $unit['status'] === 'Terkunci' ? 'opacity-60' : '' }}">
            <div
                class="w-14 h-14 rounded-full {{ $unit['status'] === 'Selesai' ? 'bg-brand-500' : ($unit['status'] === 'Sedang berjalan' ? 'bg-amber-500' : 'bg-gray-300') }} text-white flex items-center justify-center shrink-0 shadow-md">
                @if ($unit['status'] === 'Selesai')
                <x-heroicon-s-star class="w-8 h-8" />
                @elseif ($unit['status'] === 'Sedang berjalan')
                <x-heroicon-s-book-open class="w-8 h-8" />
                @else
                <x-heroicon-s-lock-closed class="w-8 h-8" /> @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold uppercase text-gray-400 tracking-wide">Unit {{ $i + 1 }}</p>
                <p class="font-bold text-gray-900 truncate">{{ $unit['title'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $unit['status'] }}</p>
            </div>
            @if ($unit['status'] !== 'Terkunci')
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="text-gray-300 fill-current shrink-0">
                <path d="M9.29 15.88L13.17 12L9.29 8.12L10.71 6.7L16 12L10.71 17.3L9.29 15.88Z" />
            </svg>
            @endif
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-200 mt-6">
        <h2 class="font-bold text-gray-900 text-lg mb-4">Goal Harian</h2>
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <span
                    class="w-6 h-6 rounded-full {{ $user->xp_total >= 100 ? 'bg-brand-500' : 'bg-gray-200' }} text-white flex items-center justify-center text-xs font-bold shrink-0">
                    @if ($user->xp_total >= 100)
                    <x-heroicon-s-check class="w-4 h-4" />
                    @else 2 @endif
                </span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-700">Kumpulkan 100 XP</p>
                    <div class="h-2 bg-gray-100 rounded-full mt-1 overflow-hidden">
                        <div class="h-full bg-brand-500 rounded-full"
                            style="width: {{ min(100, ($user->xp_total / 100) * 100) }}%"></div>
                    </div>
                </div>
                <span class="text-xs font-bold text-brand-600">{{ min($user->xp_total, 100) }}/100</span>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="w-6 h-6 rounded-full {{ $completedLessons >= 3 ? 'bg-brand-500' : 'bg-gray-200' }} text-white flex items-center justify-center text-xs font-bold shrink-0">
                    @if ($completedLessons >= 3)
                    <x-heroicon-s-check class="w-4 h-4" />
                    @else {{ $completedLessons }} @endif
                </span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-700">Selesaikan 3 pelajaran</p>
                    <div class="h-2 bg-gray-100 rounded-full mt-1 overflow-hidden">
                        <div class="h-full bg-brand-500 rounded-full"
                            style="width: {{ ($completedLessons / 3) * 100 }}%"></div>
                    </div>
                </div>
                <span class="text-xs font-bold text-brand-600">{{ $completedLessons }}/3</span>
            </div>
        </div>
    </div>
</div>
@endsection
