@extends('layouts.user', ['pageTitle' => 'Lesson Practice', 'showBottomNav' => false])

@section('content')
    <div class="w-full max-w-lg">

        <!-- Progress Bar -->
        <div class="flex items-center gap-3 mb-8">
            <button class="text-gray-400 hover:text-gray-600">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="fill-current">
                    <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.42 18.59L7.83 13H20V11Z"/>
                </svg>
            </button>
            <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-brand-500 w-1/4 transition-all"></div>
            </div>
            <span class="text-sm font-semibold text-gray-500">1/4</span>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-3xl border-2 border-gray-200 p-6 mb-8">
            <div class="text-center mb-6">
                <button class="w-20 h-20 mx-auto bg-brand-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-brand-500/30">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8 5V19L19 12L8 5Z"/>
                    </svg>
                </button>
                <p class="mt-4 text-gray-600">Dengarkan dan pilih terjemahan yang benar</p>
            </div>
        </div>

        <!-- Options -->
        <div class="space-y-3">
            @foreach (['Halo', 'Terima kasih', 'Selamat pagi', 'Selamat tinggal'] as $i => $opt)
                <button class="w-full p-4 bg-white border-2 border-gray-200 rounded-2xl text-left font-medium text-gray-800 hover:border-brand-500 hover:bg-brand-50 transition-colors">
                    {{ $opt }}
                </button>
            @endforeach
        </div>

        <div class="mt-8">
            <button class="w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-2xl hover:bg-brand-600 transition-colors">Cek Jawaban</button>
        </div>
    </div>
@endsection