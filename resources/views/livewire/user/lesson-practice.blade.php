@extends('layouts.user', ['pageTitle' => 'Lesson Practice', 'showBottomNav' => false])

@section('content')
    <div class="w-full max-w-lg">

        <!-- Progress Bar -->
        <div class="flex items-center gap-3 mb-8">
            <button onclick="history.back()" class="text-gray-400 hover:text-gray-600">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="fill-current">
                    <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.42 18.59L7.83 13H20V11Z"/>
                </svg>
            </button>
            <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-brand-500 transition-all"></div>
            </div>
            <span class="text-sm font-semibold text-gray-500">1/{{ $questions->count() }}</span>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl bg-success-50 border border-success-200 p-4 text-success-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.lesson.submit', $lesson->id) }}">
            @csrf
            @foreach ($questions as $index => $question)
                <!-- Question Card -->
                <div class="bg-white rounded-3xl border-2 border-gray-200 p-6 mb-8">
                    <div class="text-center mb-6">
                        <button type="button" class="w-20 h-20 mx-auto bg-brand-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-brand-500/30">
                            @if ($lesson->type === 'listening')
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5V19L19 12L8 5Z"/>
                                </svg>
                            @else
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="fill-current">
                                    <path d="M18 2H6C4.89 2 4 2.9 4 4V20C4 21.1 4.89 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.11 2 18 2ZM6 4H11V12L8.5 10.5L6 12V4Z"/>
                                </svg>
                            @endif
                        </button>
                        <p class="mt-4 text-gray-600 font-medium">{{ $question->question_text }}</p>
                    </div>
                </div>

                <!-- Options -->
                <div class="space-y-3 mb-8">
                    @foreach ($question->options->shuffle() as $option)
                        <label class="block cursor-pointer">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                class="peer sr-only" required>
                            <div class="w-full p-4 bg-white border-2 border-gray-200 rounded-2xl text-left font-medium text-gray-800 transition-colors peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 hover:border-brand-500">
                                {{ $option->option_text }}
                            </div>
                        </label>
                    @endforeach
                </div>
            @endforeach

            <button type="submit" class="w-full py-4 bg-brand-500 text-white font-bold text-lg rounded-2xl hover:bg-brand-600 transition-colors">
                Cek Jawaban
            </button>
        </form>
    </div>
@endsection