@extends('layouts.user', ['pageTitle' => 'Lesson Result', 'showBottomNav' => false])

@section('content')
@php
$passed = $result['passed'];
$bonus = $result['bonus_xp'] ?? 0;
$questions = $result['questions'] ?? [];
$correctCount = collect($questions)->where('is_correct', true)->count();
@endphp

<div class="w-full max-w-lg pb-28">
    <div class="flex flex-col items-center text-center pt-6 mb-6">
        <div
            class="relative bg-white border-2 {{ $passed ? 'border-brand-200' : 'border-red-200' }} rounded-2xl px-4 py-2 mb-3 shadow-sm">
            <p class="text-sm font-bold {{ $passed ? 'text-brand-700' : 'text-red-600' }}">
                {{ $passed ? ($result['score'] == 100 ? 'Sempurna! Tanpa kesalahan!' : 'Kerja bagus!') : 'Jangan
                menyerah, coba lagi!' }}
            </p>
            <span
                class="absolute left-1/2 -bottom-2 -translate-x-1/2 rotate-45 w-3.5 h-3.5 bg-white border-r-2 border-b-2 {{ $passed ? 'border-brand-200' : 'border-red-200' }}"></span>
        </div>
        <img src="{{ asset('images/mascots/' . ($passed ? '3' : '2') . '.png') }}" alt="Mascot"
            class="w-36 h-36 object-contain">

        <h1 class="mt-4 text-3xl font-dynapuff font-extrabold {{ $passed ? 'text-brand-600' : 'text-red-500' }}">
            {{ $passed ? 'Lesson Selesai!' : 'Belum Lulus' }}
        </h1>
        <p class="text-gray-500">
            {{ $passed ? 'Kamu berhasil menyelesaikan lesson ini.' : 'Kamu butuh minimal 80% untuk lulus.' }}
        </p>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-8">
        <div class="rounded-2xl border-2 border-amber-400 bg-amber-400 overflow-hidden text-center">
            <p class="text-[10px] font-extrabold uppercase tracking-wide text-white py-1">Total XP</p>
            <div class="bg-white py-3">
                <p class="text-xl font-dynapuff font-extrabold text-amber-500">+{{ $result['xp_earned'] }}</p>
                @if ($bonus > 0)
                <p class="text-[10px] text-emerald-600 font-extrabold">+{{ $bonus }} bonus streak</p>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border-2 border-sky-400 bg-sky-400 overflow-hidden text-center">
            <p class="text-[10px] font-extrabold uppercase tracking-wide text-white py-1">Benar</p>
            <div class="bg-white py-3">
                <p class="text-xl font-dynapuff font-extrabold text-sky-500">{{ $correctCount }}/{{ count($questions) }}
                </p>
            </div>
        </div>

        <div
            class="rounded-2xl border-2 {{ $passed ? 'border-brand-500 bg-brand-500' : 'border-red-500 bg-red-500' }} overflow-hidden text-center">
            <p class="text-[10px] font-extrabold uppercase tracking-wide text-white py-1">Skor</p>
            <div class="bg-white py-3">
                <p class="text-xl font-dynapuff font-extrabold {{ $passed ? 'text-brand-600' : 'text-red-500' }}">{{
                    $result['score'] }}%</p>
            </div>
        </div>
    </div>

    @if (! empty($questions))
    <div class="mb-8">
        <h2 class="font-dynapuff font-extrabold text-gray-900 text-lg mb-3">Detail Jawaban</h2>
        <div class="space-y-3">
            @foreach ($questions as $i => $q)
            <div
                class="rounded-2xl border-2 p-4 {{ $q['is_correct'] ? 'border-brand-200 bg-success-50' : 'border-red-200 bg-red-50' }}">
                <div class="flex items-start gap-3">
                    <span
                        class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-white {{ $q['is_correct'] ? 'bg-brand-500' : 'bg-red-500' }}">
                        @if ($q['is_correct'])
                        <x-heroicon-s-check class="w-4 h-4" />
                        @else
                        <x-heroicon-s-x-mark class="w-4 h-4" />
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $q['text'] }}</p>
                        @if ($q['is_correct'])
                        <p class="text-xs mt-1 font-bold text-brand-600">Jawaban benar</p>
                        @else
                        <p class="text-xs mt-1 font-semibold text-red-600">Jawabanmu: {{ $q['answer_given'] }}</p>
                        @if (! empty($q['correct_text']))
                        <p class="text-xs mt-0.5 font-bold text-emerald-700">Jawaban benar: {{ $q['correct_text'] }}</p>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="fixed bottom-0 inset-x-0 z-40 px-4 py-4 bg-white/90 backdrop-blur border-t border-gray-100">
        <div class="max-w-md px-4 mx-auto">
            <a href="{{ route('user.learn') }}"
                class="block w-full py-4 text-center bg-brand-500 text-white font-dynapuff font-extrabold text-lg rounded-2xl border-b-4 border-brand-700 hover:bg-brand-600 active:border-b-0 active:translate-y-1 transition-all">
                Lanjut
            </a>
        </div>
    </div>
</div>
@endsection