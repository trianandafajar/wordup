@extends('layouts.user', ['pageTitle' => 'Lesson Practice', 'showBottomNav' => false])

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="w-full max-w-lgF">

    <!-- Progress Bar -->
    <div class="flex items-center gap-3 mb-8">
        <button onclick="history.back()" class="text-gray-400 hover:text-gray-600">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="fill-current">
                <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.42 18.59L7.83 13H20V11Z" />
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

        @if ($lesson->explanation)
        <!-- Explanation Step (Materi) -->
        <div class="lesson-step" data-step="0" data-type="explanation">
            <div class="bg-white rounded-3xl border-2 border-brand-200 p-6 mb-8 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 bg-brand-100 text-brand-700 text-xs font-bold rounded-full">MATERI
                        LESSON</span>
                </div>
                <div class="prose prose-sm max-w-none text-gray-800 space-y-3">
                    {!! $lesson->explanation !!}
                </div>
            </div>
        </div>
        @endif

        @foreach ($questions as $index => $question)
        @php
        $stepIndex = $lesson->explanation ? $index + 1 : $index;
        @endphp
        <div class="lesson-step {{ $lesson->explanation || $index > 0 ? 'hidden' : '' }}" data-step="{{ $stepIndex }}"
            data-type="question">
            <!-- Question Card -->
            <div class="bg-white rounded-3xl border-2 border-gray-200 p-6 mb-8">
                <div class="text-center mb-6">
                    @if ($question->audio_url || $lesson->type === 'listening' || $question->type === 'listening')
                    <button type="button"
                        onclick="playAudio('{{ $question->audio_url ? Storage::url($question->audio_url) : '' }}', '{{ addslashes($question->question_text) }}')"
                        class="w-20 h-20 mx-auto bg-brand-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-brand-500/30 hover:bg-brand-600 transition-colors cursor-pointer group active:scale-95">
                        <svg width="36" height="32" viewBox="0 0 24 24" fill="currentColor"
                            class="ml-1 group-hover:scale-110 transition-transform">
                            <path d="M8 5V19L19 12L8 5Z" />
                        </svg>
                    </button>
                    <p class="text-xs text-brand-600 font-semibold mt-2">Dengarkan Audio</p>
                    @else
                    <div
                        class="w-20 h-20 mx-auto bg-brand-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="fill-current">
                            <path
                                d="M18 2H6C4.89 2 4 2.9 4 4V20C4 21.1 4.89 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.11 2 18 2ZM6 4H11V12L8.5 10.5L6 12V4Z" />
                        </svg>
                    </div>
                    @endif
                    <p class="mt-4 text-gray-600 font-medium text-lg">{{ $question->question_text }}</p>
                </div>
            </div>

            <!-- Options -->
            @if ($question->type === 'fill_in_the_blank')
            <div class="space-y-3 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Ketik jawabanmu:</label>
                    <input type="text" name="answers[{{ $question->id }}]" placeholder="Tulis jawaban..."
                        class="fill-input w-full p-4 bg-white border-2 border-gray-200 rounded-2xl text-left font-medium text-gray-800 focus:outline-none focus:border-brand-500 focus:bg-brand-50 transition-colors"
                        data-correct="{{ $question->answer?->correct_text ?? '' }}" required>
                </div>
                <p class="text-xs text-gray-400 italic">Tulis jawaban dengan benar (tidak peka huruf besar/kecil)</p>
            </div>
            @else
            <div class="space-y-3 mb-8">
                @foreach ($question->options as $option)
                <label class="block cursor-pointer option-label">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                        class="peer sr-only" required data-is-correct="{{ $option->is_correct ? 'true' : 'false' }}">
                    <div class="w-full p-4 bg-white border-2 border-gray-200 rounded-2xl text-left font-medium text-gray-800 transition-colors hover:border-brand-500 hover:bg-brand-50 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:shadow-md"
                        data-option-id="{{ $option->id }}">
                        {{ $option->option_text }}
                    </div>
                </label>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach

        <!-- Navigation -->
        <div class="lesson-nav flex items-center gap-3">
            <button type="button"
                class="lesson-prev hidden py-4 px-4 bg-gray-100 text-gray-600 font-bold text-sm rounded-2xl hover:bg-gray-200 transition-colors">
                Kembali
            </button>
            <button type="button"
                class="lesson-next flex-1 py-4 bg-gray-200 text-gray-400 font-bold text-lg rounded-2xl cursor-not-allowed transition-colors">
                Berikutnya
            </button>
            <button type="submit"
                class="lesson-submit hidden flex-1 py-4 bg-brand-500 text-white font-bold text-lg rounded-2xl hover:bg-brand-600 transition-colors">
                Cek Jawaban
            </button>
        </div>
    </form>
</div>

<!-- Audio Player Modal -->
<div class="audio-modal hidden fixed inset-0 z-50 flex items-center justify-center px-6">
    <div class="absolute inset-0 bg-black/50" onclick="closeAudioModal()"></div>
    <div class="relative bg-white rounded-3xl p-8 w-full max-w-sm text-center shadow-2xl">
        <button type="button" onclick="closeAudioModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="fill-current">
                <path
                    d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" />
            </svg>
        </button>
        <div
            class="w-20 h-20 mx-auto rounded-full bg-brand-500 flex items-center justify-center mb-4 animate-pulse-slow">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="text-white ml-1">
                <path d="M8 5V19L19 12L8 5Z" />
            </svg>
        </div>
        <p class="audio-modal-label text-gray-700 font-semibold mb-4">Dengarkan dengan saksama</p>
        <audio id="lessonAudio" controls class="w-full rounded-lg"></audio>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let lessonAudio = null;
    let lessonAudioSynth = null;

    function playAudio(url, text) {
        const modal = document.querySelector('.audio-modal');
        modal.classList.remove('hidden');

        if (url) {
            if (lessonAudioSynth) {
                speechSynthesis.cancel();
                lessonAudioSynth = null;
            }
            lessonAudio = new Audio(url);
            lessonAudio.play().catch(() => {
                speakFallback(text);
            });
        } else {
            speakFallback(text);
        }
    }

    function speakFallback(text) {
        if (!('speechSynthesis' in window)) {
            document.querySelector('.audio-modal-label').textContent = 'Audio tidak tersedia';
            return;
        }
        lessonAudioSynth = new SpeechSynthesisUtterance(text);
        lessonAudioSynth.lang = 'en-US';
        speechSynthesis.speak(lessonAudioSynth);
        document.querySelector('.audio-modal-label').textContent = 'Memutar audio...';
    }

    function closeAudioModal() {
        document.querySelector('.audio-modal').classList.add('hidden');
        if (lessonAudio) {
            lessonAudio.pause();
            lessonAudio = null;
        }
        if (lessonAudioSynth) {
            speechSynthesis.cancel();
            lessonAudioSynth = null;
        }
    }
</script>
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
            const isExplanation = currentStep.dataset.type === 'explanation';
            let hasAnswer = true;

            if (!isExplanation) {
                const selectedRadio = currentStep.querySelector('input[type="radio"]:checked');
                const textInput = currentStep.querySelector('input.fill-input');
                hasAnswer = !!selectedRadio || (textInput && textInput.value.trim() !== '');
            }

            prevBtn.classList.toggle('hidden', current === 0);

            if (current === total - 1) {
                if (isExplanation) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.add('hidden');
                } else {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                    submitBtn.disabled = !hasAnswer;
                    submitBtn.classList.toggle('cursor-not-allowed', !hasAnswer);
                }
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                if (isExplanation) {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                    nextBtn.classList.add('bg-brand-500', 'text-white');
                } else {
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
            const isExplanation = currentStep.dataset.type === 'explanation';

            if (!isExplanation) {
                const selectedRadio = currentStep.querySelector('input[type="radio"]:checked');
                const textInput = currentStep.querySelector('input.fill-input');
                let answerValue = null;

                if (selectedRadio) {
                    answerValue = selectedRadio.value;
                } else if (textInput && textInput.value.trim() !== '') {
                    answerValue = textInput.value.trim();
                } else {
                    return;
                }
                answered[current] = answerValue;
            }
            goTo(current + 1);
        });

        prevBtn.addEventListener('click', function () {
            goTo(current - 1);
        });

        // Per-question feedback (skip explanation steps)
        steps.forEach(function (step, stepIndex) {
            if (step.dataset.type === 'explanation') return;

            const radios = step.querySelectorAll('input[type="radio"]');
            const textInput = step.querySelector('input.fill-input');
            
            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const isCorrect = radio.dataset.isCorrect === 'true';
                    const optionDiv = radio.closest('label').querySelector('div');
                    const allDivs = step.querySelectorAll('div[data-option-id]');

                    allDivs.forEach(div => div.classList.remove('border-brand-500', 'bg-brand-50', 'border-red-500', 'bg-red-50', 'opacity-50'));

                    if (isCorrect) {
                        optionDiv.classList.add('border-brand-500', 'bg-brand-50');
                    } else {
                        optionDiv.classList.add('border-red-500', 'bg-red-50', 'opacity-70');
                    }

                    allDivs.forEach(div => {
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

            if (textInput) {
                textInput.addEventListener('input', function () {
                    updateNav();
                });
            }
        });

        updateProgress();
        updateNav();
    });
</script>
@endpush