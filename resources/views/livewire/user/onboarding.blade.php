@extends('layouts.user', ['pageTitle' => 'Onboarding', 'showBottomNav' => false])

@section('content')
<div class="w-full max-w-lg">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Pilih Bahasa Target</h1>
        <p class="mt-2 text-gray-600 text-sm">Apa yang ingin kamu pelajari?</p>
    </div>

    <div class="space-y-3">
        @foreach (['English' => 'EN', 'Japanese' => 'JP', 'Korean' => 'KR', 'Mandarin' => 'CN'] as $lang => $code)
        <button
            class="w-full flex items-center gap-4 p-4 bg-white border-2 border-gray-200 rounded-2xl hover:border-brand-500 transition-colors text-left cursor-pointer">
            <span
                class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 font-bold flex items-center justify-center text-sm">{{
                $code }}</span>
            <span class="font-semibold text-gray-800">{{ $lang }}</span>
        </button>
        @endforeach
    </div>

    <div class="mt-8">
        <button
            class="w-full py-3 bg-brand-500 text-white font-bold rounded-2xl hover:bg-brand-600 transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">Lanjut</button>
    </div>
</div>
@endsection