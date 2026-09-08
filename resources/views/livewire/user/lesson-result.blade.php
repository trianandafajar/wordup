@extends('layouts.user', ['pageTitle' => 'Lesson Result', 'showBottomNav' => false])

@section('content')
    <div class="w-full max-w-lg text-center py-8">
        @if ($result['passed'])
            <div class="w-24 h-24 bg-brand-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-brand-500/30">
                <x-heroicon-s-star class="w-12 h-12" />
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Lesson Selesai!</h1>
            <p class="text-gray-500 mb-8">Kerja bagus, kamu berhasil menyelesaikan lesson ini.</p>
        @else
            <div class="w-24 h-24 bg-red-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-red-500/30">
                <x-heroicon-s-x-mark class="w-12 h-12" />
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Belum Lulus</h1>
            <p class="text-gray-500 mb-8">Kamu butuh minimal 80% untuk lulus. Coba lagi!</p>
        @endif

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-4 border border-gray-200">
                <p class="text-xs text-gray-500">Skor</p>
                <p class="text-2xl font-bold {{ $result['passed'] ? 'text-brand-600' : 'text-red-500' }} mt-1">{{ $result['score'] }}%</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-gray-200">
                <p class="text-xs text-gray-500">XP Didapat</p>
                <p class="text-2xl font-bold text-amber-500 mt-1">+{{ $result['xp_earned'] }} XP</p>
            </div>
        </div>

        <a href="{{ route('user.learn') }}" class="block w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-2xl hover:bg-brand-600 transition-colors">
            Lanjut
        </a>
    </div>
@endsection
