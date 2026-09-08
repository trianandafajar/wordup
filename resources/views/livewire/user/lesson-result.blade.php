@extends('layouts.user', ['pageTitle' => 'Lesson Result', 'showBottomNav' => false])

@section('content')
<div class="w-full max-w-lg text-center py-8">
    @if ($result['passed'])
    <div
        class="w-24 h-24 bg-brand-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-brand-500/30">
        <x-heroicon-s-star class="w-12 h-12" />
    </div>
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Lesson Selesai!</h1>
    <p class="text-gray-500 mb-8">Kerja bagus, kamu berhasil menyelesaikan lesson ini.</p>
    @else
    <div
        class="w-24 h-24 bg-red-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-red-500/30">
        <x-heroicon-s-x-mark class="w-12 h-12" />
    </div>
    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Belum Lulus</h1>
    <p class="text-gray-500 mb-8">Kamu butuh minimal 80% untuk lulus. Coba lagi!</p>
    @endif

    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-4 border border-gray-200">
            <p class="text-xs text-gray-500">Skor</p>
            <p class="text-2xl font-bold {{ $result['passed'] ? 'text-brand-600' : 'text-red-500' }} mt-1">{{
                $result['score'] }}%</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-200">
            <p class="text-xs text-gray-500">XP Didapat</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">
                +{{ $result['xp_earned'] }} XP
                @if (($result['bonus_xp'] ?? 0) > 0)
                <span class="block text-xs text-emerald-600 font-extrabold">+{{ $result['bonus_xp'] }} bonus
                    streak</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Detail Per Soal -->
    @if (! empty($result['questions']))
    <div class="text-left bg-white rounded-3xl border border-gray-200 p-6 mb-8">
        <h2 class="font-bold text-gray-900 text-sm mb-4 text-center">Detail Jawaban</h2>
        <div class="space-y-3">
            @foreach ($result['questions'] as $i => $q)
            <div class="flex items-start gap-3 p-3 rounded-xl {{ $q['is_correct'] ? 'bg-success-50' : 'bg-red-50' }}">
                <span
                    class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-white {{ $q['is_correct'] ? 'bg-brand-500' : 'bg-red-500' }}">
                    <x-heroicon-s-check class="w-4 h-4" />
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ $q['text'] }}</p>
                    <p class="text-xs mt-0.5">
                        @if ($q['is_correct'])
                        <span class="text-gray-500">Jawaban benar</span>
                        @else
                        <span class="text-red-600 font-semibold">Jawaban kamu: {{ $q['answer_given'] }}</span>
                        @if (! empty($q['correct_text']))
                        <span class="text-emerald-600 font-semibold ml-2">Kunci: {{ $q['correct_text'] }}</span>
                        @endif
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('user.learn') }}"
        class="block w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-2xl hover:bg-brand-600 transition-colors">
        Lanjut
    </a>
</div>
@endsection