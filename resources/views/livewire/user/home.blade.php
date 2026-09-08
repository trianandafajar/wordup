@extends('layouts.user', ['pageTitle' => 'Home', 'activeMenu' => 'home'])

@section('content')
    <div class="w-full">

        <!-- Hero: Lanjutkan belajar -->
        <div class="bg-brand-500 text-white rounded-3xl p-6 shadow-lg shadow-brand-500/20 overflow-hidden relative">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full"></div>
            <p class="text-sm opacity-90">Streak aktif: <span class="font-bold">5 hari <x-heroicon-s-fire class="w-4 h-4 inline" /></span></p>
            <h1 class="text-2xl font-extrabold mt-2">Lanjutkan Belajarmu!</h1>
            <p class="text-sm opacity-90 mt-1">Masih ada beberapa pelajaran yang menunggumu.</p>
            <button class="mt-5 inline-flex items-center gap-2 bg-white text-brand-600 font-bold px-6 py-3 rounded-2xl hover:bg-brand-50 transition-colors">
                <x-heroicon-s-arrow-up class="w-5 h-5" /> Lanjut Belajar
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-3 gap-3 mt-6">
            <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center">
                <div class="text-orange-500 font-extrabold text-xl"><x-heroicon-s-fire class="w-6 h-6 inline" /></div>
                <p class="text-lg font-bold text-gray-900 mt-1">5</p>
                <p class="text-xs text-gray-500">Streak</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center">
                <div class="text-amber-500 font-extrabold text-xl"><x-heroicon-s-sparkles class="w-6 h-6 inline" /></div>
                <p class="text-lg font-bold text-gray-900 mt-1">1,250</p>
                <p class="text-xs text-gray-500">XP</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center">
                <div class="text-rose-500 font-extrabold text-xl"><x-heroicon-s-heart class="w-6 h-6 inline" /></div>
                <p class="text-lg font-bold text-gray-900 mt-1">5</p>
                <p class="text-xs text-gray-500">Lives</p>
            </div>
        </div>

        <!-- Progres Kursus -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 mt-6">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-gray-900 text-lg">Progres Kursus</h2>
                <span class="text-sm font-bold text-brand-600">75%</span>
            </div>
            <div class="mt-4 h-4 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-brand-400 to-brand-500 w-3/4 rounded-full"></div>
            </div>
            <div class="flex items-center justify-between mt-3 text-sm text-gray-500">
                <span>3 dari 4 unit selesai</span>
                <button class="text-brand-600 font-semibold hover:text-brand-700">Detail →</button>
            </div>
        </div>

        <!-- Unit Progress Cards -->
        <h2 class="font-bold text-gray-900 text-lg mt-8 mb-4">Unit Kamu</h2>
        <div class="space-y-3">
            @foreach ([
                ['Unit 1', 'Greetings & Introductions', 'Selesai', 'bg-brand-500', true, 'star'],
                ['Unit 2', 'Basic Vocabulary', 'Sedang berjalan', 'bg-amber-500', true, 'book-open'],
                ['Unit 3', 'Present Tense', 'Terkunci', 'bg-gray-300', false, 'lock-closed'],
            ] as $i => $unit)
                <div class="bg-white rounded-3xl p-5 border border-gray-200 flex items-center gap-4 {{ !$unit[4] ? 'opacity-60' : '' }}">
                    <div class="w-14 h-14 rounded-full {{ $unit[3] }} text-white flex items-center justify-center shrink-0 shadow-md">
                        @if ($unit[5] == 'star') <x-heroicon-s-star class="w-8 h-8" />
                        @elseif ($unit[5] == 'book-open') <x-heroicon-s-book-open class="w-8 h-8" />
                        @elseif ($unit[5] == 'lock-closed') <x-heroicon-s-lock-closed class="w-8 h-8" /> @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400 tracking-wide">{{ $unit[0] }}</p>
                        <p class="font-bold text-gray-900 truncate">{{ $unit[1] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $unit[2] }}</p>
                    </div>
                    @if ($unit[4])
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="text-gray-300 fill-current shrink-0">
                            <path d="M9.29 15.88L13.17 12L9.29 8.12L10.71 6.7L16 12L10.71 17.3L9.29 15.88Z"/>
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Daily Goals -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 mt-6">
            <h2 class="font-bold text-gray-900 text-lg mb-4">Goal Harian</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-success-500 text-white flex items-center justify-center text-xs font-bold shrink-0"><x-heroicon-s-check class="w-4 h-4" /></span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700">Praktek XP 100</p>
                        <div class="h-2 bg-gray-100 rounded-full mt-1 overflow-hidden"><div class="h-full bg-success-500 w-full rounded-full"></div></div>
                    </div>
                    <span class="text-xs font-bold text-success-600">100/100</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-brand-500 text-white flex items-center justify-center text-xs font-bold shrink-0">2</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700">Selesaikan 3 pelajaran</p>
                        <div class="h-2 bg-gray-100 rounded-full mt-1 overflow-hidden"><div class="h-full bg-brand-500 w-2/3 rounded-full"></div></div>
                    </div>
                    <span class="text-xs font-bold text-brand-600">2/3</span>
                </div>
            </div>
        </div>

    </div>
@endsection