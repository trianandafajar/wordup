@extends('layouts.user', ['pageTitle' => 'Lesson Practice', 'showBottomNav' => false])

@section('content')
@php
use Illuminate\Support\Facades\Storage;
$mascot = asset('images/mascots/2.png?=v1');
@endphp
<div class="w-full max-w-lgF pb-48">

    <div class="flex items-center gap-3 mb-8">
        <button type="button" onclick="history.back()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="fill-current">
                <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.42 18.59L7.83 13H20V11Z" />
            </svg>
        </button>
        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
            <div class="lesson-progress h-full bg-brand-500 rounded-full transition-all duration-300"></div>
        </div>
        <span class="lesson-counter text-sm font-dynapuff font-bold text-gray-500">1/{{ $questions->count() }}</span>
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
        <div class="lesson-step" data-step="0" data-type="explanation">
            <h1 class="text-2xl font-dynapuff font-extrabold text-gray-900 mb-5">Ayo belajar!</h1>
            <div class="flex items-start gap-3 mb-8">
                <img src="{{ $mascot }}" alt="Guru" class="w-24 h-24 shrink-0 object-contain">
                <div class="relative flex-1 bg-white rounded-2xl border-2 border-brand-200 p-4 shadow-sm">
                    <span
                        class="absolute -left-2 top-8 rotate-45 w-3.5 h-3.5 bg-white border-l-2 border-b-2 border-brand-200"></span>
                    <span
                        class="inline-block px-3 py-1 mb-3 bg-brand-100 text-brand-700 text-xs font-dynapuff font-bold rounded-full">Pelajaran</span>
                    <div class="lesson-prose max-w-none text-gray-800">
                        {!! $lesson->explanation !!}
                    </div>

                </div>
            </div>
        </div>
        @endif

        @foreach ($questions as $index => $question)
        @php
        $stepIndex = $lesson->explanation ? $index + 1 : $index;
        $hasAudio = $question->audio_url || $lesson->type === 'listening' || $question->type === 'listening';
        $isFill = $question->type === 'fill_in_the_blank';
        $heading = $isFill ? 'Tulis jawabanmu' : ($hasAudio ? 'Dengarkan lalu jawab' : 'Pilih jawaban yang tepat');
        $shortOptions = ! $isFill && $question->options->every(fn ($o) => mb_strlen($o->option_text) <= 14); @endphp
            <div class="lesson-step {{ $lesson->explanation || $index > 0 ? 'hidden' : '' }}"
            data-step="{{ $stepIndex }}" data-type="question">

            <h1 class="text-2xl font-dynapuff font-extrabold text-gray-900 mb-5">{{ $heading }}</h1>
            <div class="flex items-center gap-3 mb-8">
                <img src="{{ $mascot }}" alt="Guru" class="w-24 h-24 shrink-0 object-contain">
                <div class="relative flex-1 bg-white rounded-2xl border-2 border-gray-200 p-4 flex items-center gap-3">
                    <span
                        class="absolute -left-2 top-1/2 -translate-y-1/2 rotate-45 w-3.5 h-3.5 bg-white border-l-2 border-b-2 border-gray-200"></span>
                    @if ($hasAudio)
                    <button type="button"
                        onclick="playAudio('{{ $question->audio_url ? Storage::url($question->audio_url) : '' }}', '{{ addslashes($question->question_text) }}')"
                        aria-label="Dengarkan audio"
                        class="shrink-0 w-12 h-12 bg-emerald-400 text-white rounded-xl border-b-4 border-emerald-600 flex items-center justify-center hover:bg-emerald-500 active:border-b-0 active:translate-y-1 transition-all cursor-pointer">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M3 9V15H7L12 20V4L7 9H3ZM16.5 12C16.5 10.23 15.48 8.71 14 7.97V16.02C15.48 15.29 16.5 13.77 16.5 12ZM14 3.23V5.29C16.89 6.15 19 8.83 19 12C19 15.17 16.89 17.85 14 18.71V20.77C18.01 19.86 21 16.28 21 12C21 7.72 18.01 4.14 14 3.23Z" />
                        </svg>
                    </button>
                    @endif
                    <p class="font-dynapuff text-gray-800 font-semibold text-base leading-snug">{{
                        $question->question_text }}</p>
                </div>
            </div>

            @if ($isFill)
            <div class="mb-8">
                <input type="text" name="answers[{{ $question->id }}]" placeholder="Ketik jawaban di sini..."
                    autocomplete="off" autocapitalize="off" spellcheck="false"
                    class="fill-input w-full px-5 py-4 text-center text-xl font-dynapuff font-bold rounded-2xl border-2 border-b-4 border-gray-200 bg-white text-gray-800 placeholder-gray-300 placeholder:font-semibold placeholder:text-base transition-all focus:outline-none focus:border-emerald-400 focus:bg-emerald-50 focus:text-emerald-600 focus:-translate-y-0.5"
                    data-correct="{{ $question->answer?->correct_text ?? '' }}" required>
                <p class="text-xs text-gray-400 italic mt-3">Tidak peka huruf besar/kecil</p>
            </div>
            @else
            <div class="mb-8 grid gap-3 {{ $shortOptions ? 'grid-cols-2' : 'grid-cols-1' }}">
                @foreach ($question->options as $option)
                <label class="block cursor-pointer option-label">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="sr-only"
                        required data-is-correct="{{ $option->is_correct ? 'true' : 'false' }}">
                    <div data-option-id="{{ $option->id }}"
                        class="option-card flex items-center gap-3 {{ $shortOptions ? 'justify-center' : '' }} w-full p-4 border-2 border-b-4 rounded-2xl font-semibold transition-all active:translate-y-0.5 border-gray-200 bg-white text-gray-800">
                        <span
                            class="shrink-0 w-7 h-7 rounded-lg border-2 border-current flex items-center justify-center text-xs font-bold opacity-70">{{
                            $loop->iteration }}</span>
                        <span class="{{ $shortOptions ? '' : 'flex-1' }}">{{ $option->option_text }}</span>
                    </div>
                </label>
                @endforeach
            </div>
            @endif
</div>
@endforeach

<div class="lesson-nav fixed bottom-0 inset-x-0 z-40 border-t-2 border-gray-200 bg-white transition-colors">
    <div class="max-w-md mx-auto px-4 py-4">
        <div class="lesson-feedback hidden items-start gap-3 mb-4">
            <span
                class="feedback-icon shrink-0 w-11 h-11 rounded-full bg-white flex items-center justify-center"></span>
            <div class="min-w-0">
                <p class="feedback-title font-dynapuff font-extrabold text-xl leading-tight"></p>
                <p class="feedback-detail text-sm font-semibold mt-0.5"></p>
            </div>
        </div>
        <button type="button"
            class="lesson-action w-full py-4 font-dynapuff font-bold text-lg rounded-2xl border-b-4 transition-all">
            Periksa
        </button>
    </div>
</div>
</form>
</div>

<div class="audio-modal hidden fixed inset-0 z-50 flex items-center justify-center px-6">
    <div class="absolute inset-0 bg-black/50 cursor-pointer" onclick="closeAudioModal()"></div>
    <div class="relative bg-white rounded-3xl p-8 w-full max-w-sm text-center shadow-2xl">
        <button type="button" onclick="closeAudioModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 cursor-pointer">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="fill-current">
                <path
                    d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" />
            </svg>
        </button>
        <img src="{{ $mascot }}" alt="Guru" class="w-24 h-24 mx-auto mb-3 object-contain animate-pulse-slow">
        <p class="audio-modal-label font-dynapuff font-bold text-gray-700 mb-4">Dengarkan dengan saksama</p>
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
        const form = document.getElementById('lessonForm');
        const steps = document.querySelectorAll('.lesson-step');
        const nav = document.querySelector('.lesson-nav');
        const total = steps.length;
        let current = 0;
        const results = {}; // stepIndex -> { correct: true|false|null }

        const progressBar = document.querySelector('.lesson-progress');
        const counter = document.querySelector('.lesson-counter');
        const actionBtn = nav.querySelector('.lesson-action');
        const feedback = nav.querySelector('.lesson-feedback');
        const fbIcon = nav.querySelector('.feedback-icon');
        const fbTitle = nav.querySelector('.feedback-title');
        const fbDetail = nav.querySelector('.feedback-detail');

        if (total === 0) {
            nav.classList.add('hidden');
            return;
        }

        const ICON_OK = '<svg width="26" height="26" viewBox="0 0 24 24" fill="#16a34a"><path d="M9 16.2L4.8 12L3.4 13.4L9 19L21 7L19.6 5.6L9 16.2Z"/></svg>';
        const ICON_NO = '<svg width="24" height="24" viewBox="0 0 24 24" fill="#ef4444"><path d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"/></svg>';

        const CARD_STATES = {
            default: ['border-gray-200', 'bg-white', 'text-gray-800'],
            selected: ['border-emerald-400', 'bg-emerald-50', 'text-emerald-600'],
            correct: ['border-emerald-500', 'bg-emerald-50', 'text-emerald-700'],
            wrong: ['border-red-500', 'bg-red-50', 'text-red-600'],
        };
        const ALL_CARD_CLASSES = [].concat(...Object.values(CARD_STATES));

        function paint(card, state) {
            card.classList.remove(...ALL_CARD_CLASSES);
            card.classList.add(...CARD_STATES[state]);
        }

        const BTN_BASE = 'lesson-action w-full py-4 font-dynapuff font-bold text-lg rounded-2xl border-b-4 transition-all ';
        const BTN = {
            disabled: 'bg-gray-200 text-gray-400 border-gray-300 cursor-not-allowed',
            ready: 'bg-brand-500 text-white border-brand-700 cursor-pointer active:translate-y-1 active:border-b-0',
            wrong: 'bg-red-500 text-white border-red-700 cursor-pointer active:translate-y-1 active:border-b-0',
        };

        function setNavTheme(state) {
            nav.classList.remove('bg-white', 'border-gray-200', 'bg-emerald-100', 'border-emerald-200', 'bg-red-100', 'border-red-200');
            if (state === 'correct') nav.classList.add('bg-emerald-100', 'border-emerald-200');
            else if (state === 'wrong') nav.classList.add('bg-red-100', 'border-red-200');
            else nav.classList.add('bg-white', 'border-gray-200');
        }

        function getAnswer(step) {
            const radio = step.querySelector('input[type="radio"]:checked');
            if (radio) return radio.value;
            const input = step.querySelector('input.fill-input');
            return input && input.value.trim() !== '' ? input.value.trim() : null;
        }

        function render() {
            const step = steps[current];
            const isExplanation = step.dataset.type === 'explanation';
            const isLast = current === total - 1;
            const res = results[current];

            progressBar.style.width = ((current + 1) / total * 100) + '%';
            counter.textContent = (current + 1) + '/' + total;

            nav.classList.toggle('hidden', isExplanation && isLast);

            if (isExplanation) {
                setNavTheme('idle');
                feedback.classList.add('hidden'); feedback.classList.remove('flex');
                actionBtn.className = BTN_BASE + BTN.ready;
                actionBtn.textContent = 'Lanjut';
                actionBtn.disabled = false;
                return;
            }

            if (res) {
                const hasVerdict = res.correct !== null;
                setNavTheme(!hasVerdict ? 'idle' : res.correct ? 'correct' : 'wrong');
                feedback.classList.toggle('hidden', !hasVerdict);
                feedback.classList.toggle('flex', hasVerdict);
                actionBtn.className = BTN_BASE + (hasVerdict && !res.correct ? BTN.wrong : BTN.ready);
                actionBtn.textContent = isLast ? 'Selesai' : 'Lanjut';
                actionBtn.disabled = false;
            } else {
                setNavTheme('idle');
                feedback.classList.add('hidden'); feedback.classList.remove('flex');
                const hasAnswer = getAnswer(step) !== null;
                actionBtn.className = BTN_BASE + (hasAnswer ? BTN.ready : BTN.disabled);
                actionBtn.textContent = 'Periksa';
                actionBtn.disabled = !hasAnswer;
            }
        }

        function showFeedback(correct, detail) {
            fbIcon.innerHTML = correct ? ICON_OK : ICON_NO;
            fbTitle.textContent = correct ? 'Benar!' : 'Jawaban benar:';
            fbTitle.className = 'feedback-title font-dynapuff font-extrabold text-xl leading-tight ' + (correct ? 'text-emerald-700' : 'text-red-600');
            fbDetail.textContent = detail || '';
            fbDetail.className = 'feedback-detail text-sm font-semibold mt-0.5 ' + (correct ? 'text-emerald-600' : 'text-red-500');
        }

        function checkAnswer() {
            const step = steps[current];
            const radio = step.querySelector('input[type="radio"]:checked');
            const input = step.querySelector('input.fill-input');
            let correct = null;
            let detail = '';

            if (radio) {
                correct = radio.dataset.isCorrect === 'true';
                const cards = step.querySelectorAll('.option-card');
                cards.forEach(function (card) {
                    const r = step.querySelector('input[value="' + card.dataset.optionId + '"]');
                    if (r.dataset.isCorrect === 'true') {
                        paint(card, 'correct');
                        detail = card.querySelector('span:last-child').textContent.trim();
                    }
                });
                if (!correct) paint(radio.closest('label').querySelector('.option-card'), 'wrong');
            } else if (input) {
                const key = (input.dataset.correct || '').trim().toLowerCase();
              if (key !== '') {
    correct = input.value.trim().toLowerCase() === key;
    detail = input.dataset.correct;
    input.blur();
    paint(input, correct ? 'correct' : 'wrong');
}
            }

            step.classList.add('pointer-events-none');
            if (input) input.readOnly = true;
            results[current] = { correct: correct };
            if (correct !== null) showFeedback(correct, correct ? '' : detail);
            render();
        }

        function goTo(index) {
            steps[current].classList.add('hidden');
            current = index;
            steps[current].classList.remove('hidden');
            render();
            window.scrollTo({ top: 0 });
        }

        actionBtn.addEventListener('click', function () {
            if (actionBtn.disabled) return;
            const step = steps[current];
            const isExplanation = step.dataset.type === 'explanation';

            if (isExplanation) {
                goTo(current + 1);
            } else if (!results[current]) {
                checkAnswer();
            } else if (current === total - 1) {
                form.requestSubmit();
            } else {
                goTo(current + 1);
            }
        });

        steps.forEach(function (step) {
            if (step.dataset.type === 'explanation') return;

            step.querySelectorAll('input[type="radio"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    step.querySelectorAll('.option-card').forEach(c => paint(c, 'default'));
                    paint(radio.closest('label').querySelector('.option-card'), 'selected');
                    render();
                });
            });

            const input = step.querySelector('input.fill-input');
            if (input) {
                input.addEventListener('input', render);
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        actionBtn.click();
                    }
                });
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'text') return;
            if (results[current]) {
                if (e.key === 'Enter') actionBtn.click();
                return;
            }
            const n = parseInt(e.key, 10);
            if (n >= 1 && n <= 9) {
                const radios = steps[current].querySelectorAll('input[type="radio"]');
                if (radios[n - 1]) {
                    radios[n - 1].checked = true;
                    radios[n - 1].dispatchEvent(new Event('change'));
                }
            } else if (e.key === 'Enter' && !actionBtn.disabled) {
                actionBtn.click();
            }
        });

        render();
    });
</script>
@endpush