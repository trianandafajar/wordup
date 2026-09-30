@extends('layouts.user', ['pageTitle' => 'Learn', 'activeMenu' => 'learn'])

@section('content')
@foreach ($units as $unitData)
@php
$allCompleted = $unitData['lessons']->every('status', 'completed');
$hasAvailable = $unitData['lessons']->firstWhere('status', 'available') !== null;
$unitColor = $allCompleted ? 'bg-brand-500' : ($hasAvailable ? 'bg-amber-500' : 'bg-gray-300 text-gray-600');
$unitTextColor = $hasAvailable || $allCompleted ? 'text-white' : 'text-gray-600';
@endphp
<div id="unit-{{ $loop->iteration }}" class="w-full max-w-lg mb-4 scroll-mt-24">
    <div
        class="rounded-2xl {{ $unitColor }} {{ $unitTextColor }} p-4 shadow-md flex items-center justify-between gap-4 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider opacity-80">Unit {{ $loop->iteration }}</span>
            <h3 class="text-lg font-bold leading-snug">{{ $unitData['unit']->title }}</h3>
            @if ($hasAvailable)
            <p class="text-xs opacity-90 mt-0.5">Sedang berjalan</p>
            @elseif ($allCompleted)
            <p class="text-xs opacity-90 mt-0.5">Selesai</p>
            @else
            <p class="text-xs opacity-80 mt-0.5">Terkunci</p>
            @endif
        </div>
        <div class="shrink-0 bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="fill-current">
                <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" />
            </svg>
        </div>
    </div>
</div>

<div class="flex flex-col items-center gap-0 w-full mb-8 relative" style="padding-bottom: 2rem;">
    @foreach ($unitData['lessons'] as $index => $lesson)
    @php
    $isFirst = $index === 0;
    $isLast = $index === $unitData['lessons']->count() - 1;
    $isAvailable = $lesson['status'] === 'available';
    $isCompleted = $lesson['status'] === 'completed';
    $isLocked = $lesson['status'] === 'locked';

    $position = $index % 4;
    $mlClass = $position === 1 ? 'self-start ml-8 sm:ml-16' : ($position === 3 ? 'self-end mr-8 sm:mr-16' : 'self-center
    ml-0');
    $lockedText = $lives <= 0 ? 'Nyawa habis, tunggu pemulihan' : 'Selesaikan lesson sebelumnya' ; @endphp <div
        class="{{ $mlClass }} mb-0" data-node="{{ $lesson['id'] }}" data-done="{{ $isCompleted ? '1' : '0' }}">
        <button @if (!$isLocked) onclick="window.location.href='{{ route('user.lesson.practice', $lesson['id']) }}'"
            @else disabled @endif
            class="relative group focus:outline-none w-14 h-14 lg:w-16 lg:h-16 rounded-full border-4 transition-all duration-300 flex items-center justify-center shrink-0 z-10 {{ $isLocked ? 'bg-gray-200 border-gray-300 opacity-60 cursor-not-allowed' : ($isAvailable ? 'bg-brand-500 border-brand-400 shadow-lg shadow-brand-500/30 cursor-pointer hover:scale-105' : 'bg-brand-500 border-brand-600 shadow-lg shadow-brand-500/30 cursor-default') }}">
            @if ($isLocked)
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-gray-400 fill-current">
                <path
                    d="M12 2C9.243 2 7 4.243 7 7V10H6C4.897 10 4 10.897 4 12V20C4 21.103 4.897 22 6 22H18C19.103 22 20 21.103 20 20V12C20 10.897 19.103 10 18 10H17V7C17 4.243 14.757 2 12 2ZM12 4C13.654 4 15 5.346 15 7V10H9V7C9 5.346 10.346 4 12 4ZM18 12V20H6V12H18ZM12 13C11.448 13 11 13.448 11 14V18C11 18.552 11.448 19 12 19C12.552 19 13 18.552 13 18V14C13 13.448 12.552 13 12 13Z" />
            </svg>
            @elseif ($isCompleted)
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
            </svg>
            @else
            @if ($lesson['type'] === 'listening')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M12 3C10.34 3 9 4.37 9 6V12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12V6C15 4.37 13.66 3 12 3ZM19 12C19 15.53 16.39 18.44 13 18.92V21H11V18.92C7.61 18.44 5 15.53 5 12H7C7 14.76 9.24 17 12 17C14.76 17 17 14.76 17 12H19Z" />
            </svg>
            @elseif ($lesson['type'] === 'speaking')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M12 14C13.66 14 15 12.66 15 11V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 12.66 10.34 14 12 14ZM17 11C17 13.76 14.76 16 12 16C9.24 16 7 13.76 7 11H5C5 14.53 7.61 17.43 11 17.92V21H13V17.92C16.39 17.43 19 14.53 19 11H17Z" />
            </svg>
            @elseif ($lesson['type'] === 'quiz')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M11 18H13V16H11V18ZM12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12 6C9.24 6 7 8.24 7 11H9C9 9.34 10.34 8 12 8C13.66 8 15 9.34 15 11C15 12.66 13.66 14 12 14V16H16V14C16 11.24 14.21 6 12 6Z" />
            </svg>
            @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M18 2H6C4.89 2 4 2.9 4 4V20C4 21.1 4.89 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.11 2 18 2ZM6 4H11V12L8.5 10.5L6 12V4Z" />
            </svg>
            @endif
            @endif

            @if ($isCompleted)
            <span
                class="absolute -top-1 -right-1 bg-amber-500 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 shadow-sm">
                +{{ $lesson['xp_reward'] }}
            </span>
            @endif

            @if ($isAvailable)
            <span class="absolute inset-0 rounded-full border-2 border-brand-400/60 animate-ping"></span>
            @endif

            <div
                class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:block z-20 pointer-events-none">
                <div
                    class="bg-gray-900 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                    <span class="font-bold">{{ $lesson['title'] }}</span>
                    @if ($isCompleted)
                    <span class="opacity-75">| Best: {{ $lesson['best_score'] }}%</span>
                    @elseif ($isAvailable)
                    <span class="opacity-75">| +{{ $lesson['xp_reward'] }} XP</span>
                    @elseif ($isLocked)
                    <span class="opacity-75">| {{ $lockedText }}</span>
                    @endif
                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-px">
                        <div class="w-2 h-2 bg-gray-900 rotate-45 transform"></div>
                    </div>
                </div>
            </div>
        </button>
</div>

@if (!$isLast)
<div class="h-10 w-full flex items-center justify-center"></div>
@php
$isChestLevel = ($lesson['order'] % 5 === 0);
$reward = $isChestLevel ? \App\Models\UserReward::where('user_id', auth()->id())
->where('unit_id', $unitData['unit']->id)
->where('level_milestone', $lesson['order'])
->first() : null;
$isOpened = $reward?->is_opened ?? false;
$isReady = $isCompleted && !$isOpened;
$sparks = [[-140,-110],[130,-120],[-160,10],[160,20],[-90,-170],[100,-165],[-60,90],[70,100]];
$chestClosedImg = asset('images/rewards/reward-chest-locked.png');
$chestOpenedImg = asset('images/rewards/reward-chest-opened.png');
@endphp

@if ($isChestLevel)
<div class="my-2 flex justify-center" x-data="{
        opened: {{ $isOpened ? 'true' : 'false' }},
        ready: {{ $isReady ? 'true' : 'false' }},
        showAnim: false,
        phase: 'idle',
        justClaimed: false,
        loading: false,
        rewards: [
            { text: '+50 XP', cls: 'text-amber-300' },
            { text: '+5 Energi', cls: 'text-emerald-300' }
        ],

        playAnim() {
            this.showAnim = true;
            this.phase = 'grow';
            setTimeout(() => { this.phase = 'shake'; }, 700);
            setTimeout(() => { this.phase = 'open'; }, 1500);
        },

        closeAnim() {
            if (this.phase !== 'open') return;
            this.showAnim = false;
            this.phase = 'idle';
            if (this.justClaimed) window.location.reload();
        },

        async openChest() {
            if (this.loading) return;

            if (this.opened) {
                this.justClaimed = false;
                this.playAnim();
                return;
            }
            if (!this.ready) return;

            this.loading = true;
            try {
                const response = await fetch('{{ route('user.reward.claim') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        unit_id: {{ $unitData['unit']->id }},
                        level_milestone: {{ $lesson['order'] }}
                    })
                });
                const data = await response.json();

                if (!response.ok) {
                    console.error('Reward error:', response.status, data);
                    return;
                }
                if (data.success) {
                    this.opened = true;
                    this.ready = false;
                    this.justClaimed = true;
                    this.playAnim();
                }
            } catch (error) {
                console.error('Fetch error:', error);
            } finally {
                this.loading = false;
            }
        }
    }">
    <button @click="openChest()"
        :class="{'animate-bounce': ready && !opened, 'cursor-pointer': ready || opened, 'opacity-50 cursor-not-allowed': !ready && !opened}"
        class="relative focus:outline-none transition-transform hover:scale-110">
        <img :src="opened ? '{{ $chestOpenedImg }}' : '{{ $chestClosedImg }}'"
            class="w-12 h-12 object-contain drop-shadow-md" alt="Harta Karun">
    </button>

    <div x-show="showAnim" style="display: none;" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="closeAnim()"
        class="fixed inset-0 z-100 flex items-center justify-center bg-black/80 backdrop-blur-sm cursor-pointer select-none">

        <div class="relative flex flex-col items-center">
            <div x-show="phase === 'open'" style="display: none;" class="chest-rays"></div>
            <div x-show="phase === 'open'" style="display: none;" class="chest-burst"></div>
            <template x-if="phase === 'open'">
                <div class="absolute inset-0 pointer-events-none">
                    @foreach ($sparks as $i => $p)
                    <span class="chest-spark"
                        style="--x: {{ $p[0] }}px; --y: {{ $p[1] }}px; animation-delay: {{ $i * 40 }}ms;"></span>
                    @endforeach
                </div>
            </template>
            <template x-if="phase === 'open' && justClaimed">
                <div class="pointer-events-none">
                    <template x-for="(r, i) in rewards" :key="i">
                        <span class="float-point" :class="r.cls"
                            :style="`--dx: ${(i - (rewards.length - 1) / 2) * 110}px; animation-delay: ${i * 200}ms`"
                            x-text="r.text"></span>
                    </template>
                </div>
            </template>
            <img :src="phase === 'open' ? '{{ $chestOpenedImg }}' : '{{ $chestClosedImg }}'" :class="{
                        'chest-grow': phase === 'grow',
                        'chest-shake': phase === 'shake',
                        'chest-pop': phase === 'open'
                    }" class="relative z-10 w-48 h-48 sm:w-60 sm:h-60 object-contain drop-shadow-2xl"
                alt="Harta Karun">

            <div x-show="phase === 'open' && justClaimed" style="display: none;"
                class="reward-up relative z-10 mt-6 text-center">
                <p class="text-amber-300 text-sm font-bold uppercase tracking-widest mb-3">Hadiah!</p>
                <div class="flex items-center justify-center gap-3">
                    <span
                        class="bg-white/10 border border-white/20 text-white font-extrabold text-lg px-4 py-2 rounded-2xl">+5
                        Energi</span>
                    <span
                        class="bg-white/10 border border-white/20 text-amber-300 font-extrabold text-lg px-4 py-2 rounded-2xl">+50
                        XP</span>
                </div>
            </div>

            <p x-show="phase === 'open'" style="display: none;"
                class="tap-hint relative z-10 mt-6 text-white/70 text-xs tracking-wide">Ketuk untuk lanjut</p>
        </div>
    </div>
</div>
@endif
@endif
@endforeach

<svg class="lesson-path absolute inset-0 w-full h-full pointer-events-none" width="100%" height="100%"></svg>
</div>
@endforeach
@endsection

@push('scripts')
<style>
    @keyframes chestGrow {
        0% {
            transform: scale(0.1) rotate(-12deg);
            opacity: 0;
        }

        55% {
            transform: scale(1.25) rotate(5deg);
            opacity: 1;
        }

        75% {
            transform: scale(0.9) rotate(-2deg);
        }

        100% {
            transform: scale(1) rotate(0);
        }
    }

    .chest-grow {
        animation: chestGrow 0.7s cubic-bezier(.22, 1, .36, 1) forwards;
    }
    @keyframes chestShake {

        0%,
        100% {
            transform: scale(1) rotate(0);
        }

        15% {
            transform: scale(1.05) rotate(-9deg);
        }

        30% {
            transform: scale(1.08) rotate(9deg);
        }

        45% {
            transform: scale(1.1) rotate(-7deg);
        }

        60% {
            transform: scale(1.12) rotate(7deg);
        }

        80% {
            transform: scale(1.15) rotate(-3deg);
        }
    }

    .chest-shake {
        animation: chestShake 0.8s ease-in-out forwards;
    }

    @keyframes chestPop {
        0% {
            transform: scale(1.15);
        }

        35% {
            transform: scale(1.4);
        }

        100% {
            transform: scale(1.15);
        }
    }

    .chest-pop {
        animation: chestPop 0.5s cubic-bezier(.22, 1, .36, 1) forwards;
    }
    @keyframes raysSpin {
        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    .chest-rays {
        position: absolute;
        left: 50%;
        top: 96px;
        width: 460px;
        height: 460px;
        transform: translate(-50%, -50%);
        background: repeating-conic-gradient(from 0deg, rgba(251, 191, 36, .45) 0deg 10deg, transparent 10deg 30deg);
        -webkit-mask-image: radial-gradient(circle, #000 15%, transparent 68%);
        mask-image: radial-gradient(circle, #000 15%, transparent 68%);
        animation: raysSpin 10s linear infinite;
        pointer-events: none;
    }
    @keyframes burst {
        0% {
            transform: translate(-50%, -50%) scale(0.2);
            opacity: 0.9;
        }

        100% {
            transform: translate(-50%, -50%) scale(2.2);
            opacity: 0;
        }
    }

    .chest-burst {
        position: absolute;
        left: 50%;
        top: 96px;
        width: 240px;
        height: 240px;
        border-radius: 9999px;
        background: radial-gradient(circle, rgba(253, 224, 71, .9), rgba(251, 191, 36, 0) 70%);
        animation: burst 0.7s ease-out forwards;
        pointer-events: none;
    }
    @keyframes spark {
        0% {
            transform: translate(-50%, -50%) scale(0);
            opacity: 1;
        }

        100% {
            transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(1);
            opacity: 0;
        }
    }

    .chest-spark {
        position: absolute;
        left: 50%;
        top: 96px;
        width: 10px;
        height: 10px;
        background: #fde047;
        border-radius: 9999px;
        box-shadow: 0 0 10px 3px rgba(253, 224, 71, .8);
        animation: spark 0.9s ease-out forwards;
    }
    @keyframes rewardUp {
        0% {
            opacity: 0;
            transform: translateY(24px) scale(0.6);
        }

        70% {
            opacity: 1;
            transform: translateY(-4px) scale(1.05);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .reward-up {
        animation: rewardUp 0.5s 0.25s cubic-bezier(.22, 1, .36, 1) both;
    }

    @keyframes hintBlink {

        0%,
        100% {
            opacity: .3;
        }

        50% {
            opacity: 1;
        }
    }

    .tap-hint {
        animation: hintBlink 1.4s ease-in-out infinite;
    }
    @keyframes floatPoint {
        0% {
            transform: translate(calc(-50% + var(--dx)), 0) scale(0.3);
            opacity: 0;
        }

        20% {
            transform: translate(calc(-50% + var(--dx)), -50px) scale(1.4);
            opacity: 1;
        }

        100% {
            transform: translate(calc(-50% + var(--dx)), -190px) scale(1);
            opacity: 0;
        }
    }

    .float-point {
        position: absolute;
        left: 50%;
        top: 96px;
        z-index: 20;
        font-size: 1.9rem;
        font-weight: 800;
        white-space: nowrap;
        text-shadow: 0 2px 0 rgba(0, 0, 0, .6), 0 0 14px rgba(0, 0, 0, .5);
        animation: floatPoint 1.8s ease-out both;
    }

    @media (min-width: 640px) {

        .chest-rays,
        .chest-burst,
        .chest-spark,
        .float-point {
            top: 120px;
        }
    }
</style>

<script>
    function drawLessonPaths() {
        document.querySelectorAll('.lesson-path').forEach(function (svg) {
            while (svg.firstChild) { svg.removeChild(svg.firstChild); }
            svg.removeAttribute('viewBox');
        });

        document.querySelectorAll('.lesson-path').forEach(function (svg) {
            const container = svg.parentElement;
            const nodes = container.querySelectorAll('[data-node]');
            const svgRect = svg.getBoundingClientRect();

            if (nodes.length < 2 || svgRect.width === 0) return;

            const ns = 'http://www.w3.org/2000/svg';
            svg.setAttribute('viewBox', `0 0 ${svgRect.width} ${svgRect.height}`);

            for (let i = 0; i < nodes.length - 1; i++) {
                const a = nodes[i].getBoundingClientRect();
                const b = nodes[i + 1].getBoundingClientRect();

                const x1 = a.left + a.width / 2 - svgRect.left;
                const y1 = a.bottom - svgRect.top;
                const x2 = b.left + b.width / 2 - svgRect.left;
                const y2 = b.top - svgRect.top;

                const midY = (y1 + y2) / 2;
                const color = nodes[i].dataset.done === '1' ? '#4caf50' : '#d1d5db';

                const path = document.createElementNS(ns, 'path');
                path.setAttribute('d', `M ${x1.toFixed(1)} ${y1.toFixed(1)} C ${x1.toFixed(1)} ${midY.toFixed(1)}, ${x2.toFixed(1)} ${midY.toFixed(1)}, ${x2.toFixed(1)} ${y2.toFixed(1)}`);
                path.setAttribute('stroke', color);
                path.setAttribute('stroke-width', '6');
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke-linecap', 'round');
                svg.appendChild(path);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', drawLessonPaths);
    window.addEventListener('resize', drawLessonPaths);
</script>
@endpush