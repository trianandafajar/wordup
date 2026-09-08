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
                <div class="lesson-progress h-full bg-brand-500 rounded-full transition-all duration-300"></div>
            </div>
            <span class="lesson-counter text-sm font-semibold text-gray-500">1/{{ $questions->count() }}</span>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl bg-success-50 border border-success-200 p-4 text-success-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.lesson.submit', $lesson->id) }}" id="lessonForm">
            @csrf
            @foreach ($questions as $index => $question)
                <div class="lesson-step {{ $index > 0 ? 'hidden' : '' }}" data-step="{{ $index }}">
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
                        @foreach ($question->options as $option)
                            <label class="block cursor-pointer">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                    class="peer sr-only" required
                                    data-is-correct="{{ $option->is_correct ? 'true' : 'false' }}">
                                <div class="w-full p-4 bg-white border-2 border-gray-200 rounded-2xl text-left font-medium text-gray-800 transition-colors hover:border-brand-500 hover:bg-brand-50"
                                     data-option-id="{{ $option->id }}">
                                    {{ $option->option_text }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Navigation -->
            <div class="lesson-nav flex items-center gap-3">
                <button type="button" class="lesson-prev hidden py-4 px-4 bg-gray-100 text-gray-600 font-bold text-sm rounded-2xl hover:bg-gray-200 transition-colors">
                    Kembali
                </button>
                <button type="button" class="lesson-next flex-1 py-4 bg-gray-200 text-gray-400 font-bold text-lg rounded-2xl cursor-not-allowed transition-colors">
                    Berikutnya
                </button>
                <button type="submit" class="lesson-submit hidden flex-1 py-4 bg-brand-500 text-white font-bold text-lg rounded-2xl hover:bg-brand-600 transition-colors">
                    Cek Jawaban
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const steps = document.querySelectorAll('.lesson-step');
        const nav = document.querySelector('.lesson-nav');
        const total = steps.length;
        let current = 0;
        const answered = {};

        const progressBar = document.querySelector('.lesson-progress');
        const counter = document.querySelector('.lesson-counter');
        const prevBtn = nav.querySelector('.lesson-prev');
        const nextBtn = nav.querySelector('.lesson-next');
        const submitBtn = nav.querySelector('.lesson-submit');

        if (total === 0) {
            nav.classList.add('hidden');
            return;
        }

        function updateProgress() {
            const answeredCount = Object.keys(answered).length;
            const pct = total > 0 ? ((current + 1) / total) * 100 : 0;
            progressBar.style.width = pct + '%';
            counter.textContent = (current + 1) + '/' + total;
        }

        function updateNav() {
            const currentStep = steps[current];
            const selectedRadio = currentStep.querySelector('input[type="radio"]:checked');
            const hasAnswer = !!selectedRadio;

            prevBtn.classList.toggle('hidden', current === 0);

            if (current === total - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
                submitBtn.disabled = !hasAnswer;
                submitBtn.classList.toggle('cursor-not-allowed', !hasAnswer);
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                nextBtn.disabled = !hasAnswer;
                nextBtn.classList.toggle('cursor-not-allowed', !hasAnswer);
                if (hasAnswer) {
                    nextBtn.classList.remove('bg-gray-200', 'text-gray-400');
                    nextBtn.classList.add('bg-brand-500', 'text-white');
                } else {
                    nextBtn.classList.remove('bg-brand-500', 'text-white');
                    nextBtn.classList.add('bg-gray-200', 'text-gray-400');
                }
            }
        }

        function goTo(index) {
            steps[current].classList.add('hidden');
            current = index;
            steps[current].classList.remove('hidden');
            updateProgress();
            updateNav();
        }

        nextBtn.addEventListener('click', function () {
            if (this.disabled) return;
            const currentStep = steps[current];
            const selectedRadio = currentStep.querySelector('input[type="radio"]:checked');
            if (!selectedRadio) return;
            answered[current] = selectedRadio.value;
            goTo(current + 1);
        });

        prevBtn.addEventListener('click', function () {
            goTo(current - 1);
        });

        // Per-question feedback
        steps.forEach(function (step, stepIndex) {
            const radios = step.querySelectorAll('input[type="radio"]');
            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const isCorrect = radio.dataset.isCorrect === 'true';
                    const optionDiv = radio.closest('label').querySelector('div');
                    const allDivs = step.querySelectorAll('div[data-option-id]');

                    // Reset all options
                    allDivs.forEach(function (div) {
                        div.classList.remove('border-brand-500', 'bg-brand-50', 'border-red-500', 'bg-red-50', 'opacity-50');
                    });

                    if (isCorrect) {
                        optionDiv.classList.add('border-brand-500', 'bg-brand-50');
                    } else {
                        optionDiv.classList.add('border-red-500', 'bg-red-50', 'opacity-70');
                    }

                    // Highlight correct answer
                    allDivs.forEach(function (div) {
                        const optRadio = step.querySelector('input[value="' + div.dataset.optionId + '"]');
                        if (optRadio && optRadio.dataset.isCorrect === 'true') {
                            div.classList.remove('opacity-70');
                            div.classList.add('border-brand-500', 'bg-brand-50', 'opacity-100');
                        }
                    });

                    answered[stepIndex] = radio.value;
                    updateNav();
                });
            });
        });

        updateProgress();
        updateNav();
    });
</script>
@endpush