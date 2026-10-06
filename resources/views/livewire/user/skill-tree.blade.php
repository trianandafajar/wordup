@extends('layouts.user', ['pageTitle' => 'Learn', 'activeMenu' => 'learn'])

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Dynapuff:wght@400;500;600;700;800;900&display=swap');
</style>
@php
$amplitude = 56;
$wave = fn (int $i): int => (int) round(sin($i * M_PI / 4) * $amplitude);
$sparks = [[-140,-110],[130,-120],[-160,10],[160,20],[-90,-170],[100,-165],[-60,90],[70,100]];
$chestClosedImg = asset('images/rewards/reward-chest-locked.png');
$chestOpenedImg = asset('images/rewards/reward-chest-opened.png');

$activeUnitIndex = $units->search(fn ($u) => ! $u['lessons']->every('status', 'completed'));
if ($activeUnitIndex === false) {
$activeUnitIndex = max($units->count() - 1, 0);
}
@endphp

<div x-data="unitSlider({{ $activeUnitIndex }}, {{ $units->count() }})" @save-unit.window="save()"
    class="w-full max-w-lg mx-auto px-0">
    {{-- <div class="flex items-center justify-between mb-4 px-1">
        <button @click="goTo(current - 1)" :disabled="current === 0"
            class="w-9 h-9 rounded-full bg-white shadow flex items-center justify-center disabled:opacity-30 cursor-pointer disabled:cursor-not-allowed"
            aria-label="Unit sebelumnya">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 6l-6 6 6 6" />
            </svg>
        </button>

        <div class="flex items-center gap-2">
            @foreach ($units as $i => $u)
            <button @click="goTo({{ $i }})" aria-label="Unit {{ $i + 1 }}"
                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                :class="current === {{ $i }} ? 'w-6 bg-[#43A047]' : 'w-2 bg-gray-300'"></button>
            @endforeach
        </div>

        <button @click="goTo(current + 1)" :disabled="current === total - 1"
            class="w-9 h-9 rounded-full bg-white shadow flex items-center justify-center disabled:opacity-30 cursor-pointer disabled:cursor-not-allowed"
            aria-label="Unit berikutnya">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 6l6 6-6 6" />
            </svg>
        </button>
    </div> --}}

    <div x-ref="track" class="unit-track -mx-3 flex items-start overflow-x-auto snap-x snap-mandatory"
        :class="dragging ? 'cursor-grabbing' : 'cursor-grab'" :style="dragging ? 'scroll-snap-type: none' : ''"
        @scroll.passive="onScroll()" @mousedown="dragStart($event)" @mousemove.window="dragMove($event)"
        @mouseup.window="dragEnd()" @dragstart.prevent
        @click.capture="if (moved) { $event.stopPropagation(); $event.preventDefault(); }">

        @foreach ($units as $unitData)
        @php
        $allCompleted = $unitData['lessons']->every('status', 'completed');
        $hasAvailable = $unitData['lessons']->firstWhere('status', 'available') !== null;

        $bannerClass = ($allCompleted || $hasAvailable)
        ? 'bg-[#43A047] text-white'
        : 'bg-gray-200 text-gray-500';

        $slot = 0;
        @endphp

        <section id="unit-{{ $loop->iteration }}" class="w-full shrink-0 snap-center px-3">
            <div
                class="relative overflow-hidden rounded-2xl {{ $bannerClass }} px-5 py-4 shadow-lg shadow-black/10 flex items-center justify-between gap-4 mb-6">
                <div class="absolute -right-8 -bottom-10 w-40 h-40 rounded-full bg-white/10 pointer-events-none"></div>
                <div class="absolute right-16 -top-12 w-28 h-28 rounded-full bg-white/10 pointer-events-none"></div>

                <div class="relative min-w-0">
                    <span class="text-xs font-bold uppercase tracking-wider opacity-90">Unit {{ $loop->iteration
                        }}</span>
                    <h3 class="text-xl font-dynapuff font-extrabold leading-tight mt-0.5">{{ $unitData['unit']->title }}
                    </h3>

                    <p class="mt-1.5 flex items-center gap-1.5 text-sm font-medium opacity-95">
                        @if ($hasAvailable)
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10" fill="#fff" />
                            <path d="M10 8.5v7l6-3.5z" fill="#f97316" />
                        </svg>
                        Sedang berjalan
                        @elseif ($allCompleted)
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" fill="#fff" />
                            <path d="M7.5 12.5l3 3 6-6.5" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        Selesai
                        @else
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17 9h-1V7a4 4 0 00-8 0v2H7a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-8a2 2 0 00-2-2zm-7-2a2 2 0 014 0v2h-4V7z" />
                        </svg>
                        Terkunci
                        @endif
                    </p>
                </div>

                <div
                    class="relative shrink-0 w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <img src="{{ asset('images/mascots/' . (($loop->iteration % 4) + 1) . '.png?=v1') }}" alt="Mascot"
                        class="w-12 h-12 object-contain">
                </div>
            </div>

            <div class="relative flex flex-col items-center w-full pb-6">

                <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
                    <div class="absolute left-[6%] top-[10%] w-16 h-7 rounded-full bg-white/70 blur-[1px]"></div>
                    <div class="absolute left-[11%] top-[7%] w-8 h-6 rounded-full bg-white/70 blur-[1px]"></div>
                    <div class="absolute right-[6%] top-[38%] w-16 h-7 rounded-full bg-white/60 blur-[1px]"></div>
                    <div class="absolute right-[3%] top-[26%] w-8 h-8 text-brand-400/60">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22c0-6 1-10 6-14-1 6-2 10-6 14zM12 22C11 16 9 12 4 9c1 6 3 10 8 13z" />
                        </svg>
                    </div>
                    <div class="absolute left-[14%] top-[44%] w-8 h-8 text-brand-400/50">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22c0-6 1-10 6-14-1 6-2 10-6 14zM12 22C11 16 9 12 4 9c1 6 3 10 8 13z" />
                        </svg>
                    </div>
                    <div class="absolute right-[12%] top-[62%] w-7 h-7 text-brand-400/50">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22c0-6 1-10 6-14-1 6-2 10-6 14zM12 22C11 16 9 12 4 9c1 6 3 10 8 13z" />
                        </svg>
                    </div>
                    <div class="absolute left-[8%] top-[70%] w-10 h-6 rounded-[50%] bg-gray-300/60"></div>
                    <div class="absolute right-[5%] top-[80%] w-8 h-4 rounded-[50%] bg-gray-300/50"></div>
                    <div class="absolute left-[10%] top-[90%] w-7 h-7 text-brand-400/50">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22c0-6 1-10 6-14-1 6-2 10-6 14zM12 22C11 16 9 12 4 9c1 6 3 10 8 13z" />
                        </svg>
                    </div>
                </div>

                @foreach ($unitData['lessons'] as $index => $lesson)
                @php
                $isAvailable = $lesson['status'] === 'available';
                $isCompleted = $lesson['status'] === 'completed';
                $isLocked = $lesson['status'] === 'locked';
                $bestScore = $lesson['best_score'] ?? 0;

                $icon = asset('images/learn/vocabulary.png');
                if ($isLocked) {
                $icon = asset('images/learn/vocabulary-locked.png');
                } elseif ($isCompleted) {
                if ($bestScore == 100) {
                $icon = asset('images/learn/vocabulary-xp.png?=v2');
                } elseif ($bestScore >= 80) {
                $icon = asset('images/learn/vocabulary-completed.png');
                }
                }

                $offset = $wave($slot);
                $slot++;

                $showMascot = $index % 4 === 1;
                $mascotSide = $offset > 0 ? -1 : 1;
                $mascotX = $mascotSide * 112 - $offset;

                $randomMascot = asset('images/mascots/' . rand(1, 4) . '.png?=v1');

                $lockedText = $lives <= 0 ? 'Nyawa habis, tunggu pemulihan' : 'Selesaikan lesson sebelumnya' ;
                    $showXpBadge=($isCompleted && $bestScore==100); @endphp <div class="relative z-10 mb-3"
                    style="left: {{ $offset }}px;" data-node="{{ $lesson['id'] }}"
                    data-done="{{ $isCompleted ? '1' : '0' }}">
                    <button @if ($isCompleted)
                        onclick="window.dispatchEvent(new CustomEvent('open-lesson-modal', { detail: { url: '{{ route('user.lesson.practice', $lesson['id']) }}' } }))"
                        @elseif (!$isLocked)
                        onclick="window.location.href='{{ route('user.lesson.practice', $lesson['id']) }}'" @else
                        disabled @endif
                        class="relative group focus:outline-none w-20 h-20 lg:w-24 lg:h-24 flex items-center justify-center shrink-0 transition-transform duration-300 {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer hover:scale-105 active:scale-95' }}">

                        @if ($isAvailable)
                        <span class="absolute inset-3 rounded-full bg-brand-400/30 animate-ping"></span>
                        @endif

                        <img src="{{ $icon }}" alt="Lesson Icon"
                            class="relative w-full h-full object-contain drop-shadow-lg {{ $isLocked ? 'opacity-90' : '' }}">

                        @if ($showXpBadge)
                        <span
                            class="absolute top-1 right-0 bg-amber-500 text-white text-xs font-extrabold rounded-full px-2 py-0.5 shadow-md ring-2 ring-white/70">
                            +{{ $lesson['xp_reward'] }}
                        </span>
                        @endif

                        <div
                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block z-20 pointer-events-none">
                            <div
                                class="bg-gray-900 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                                <span class="font-bold">{{ $lesson['title'] }}</span>
                                @if ($isCompleted)
                                <span class="opacity-75">| Best: {{ $bestScore }}%</span>
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

                    @if ($showMascot)
                    <div class="absolute top-1/2 w-32 -ml-0 flex flex-col items-center pointer-events-none select-none"
                        style="left: {{ $mascotX }}px; transform: translateY(-50%);" aria-hidden="true">
                        <img src="{{ $randomMascot }}" alt="" class="w-32 h-32 object-contain drop-shadow-md">
                    </div>
                    @endif
            </div>

            @php
            $isChestLevel = ($lesson['order'] % 5 === 0);
            $reward = $isChestLevel ? \App\Models\UserReward::where('user_id', auth()->id())
            ->where('unit_id', $unitData['unit']->id)
            ->where('level_milestone', $lesson['order'])
            ->first() : null;
            $isOpened = $reward?->is_opened ?? false;
            $isReady = $isCompleted && !$isOpened;

            $chestOffset = $wave($slot);
            if ($isChestLevel) { $slot++; }
            @endphp

            @if ($isChestLevel)
            <div class="relative z-10 mb-3" style="left: {{ $chestOffset }}px;" x-data="{
                        opened: {{ $isOpened ? 'true' : 'false' }},
                        ready: {{ $isReady ? 'true' : 'false' }},
                        showAnim: false,
                        phase: 'idle',
                        justClaimed: false,
                        loading: false,
                        rewards: [
                            { text: '+50 XP', cls: 'text-emerald-300' },
                            { text: '+5 Energi', cls: 'text-lime-300' }
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
                            if (this.justClaimed) {
                                // simpan unit yang sedang dilihat supaya setelah reload tetap di unit ini
                                window.dispatchEvent(new CustomEvent('save-unit'));
                                window.location.reload();
                            }
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
                    :class="{'animate-bounce': ready && !opened, 'cursor-pointer': ready || opened, 'opacity-60 cursor-not-allowed': !ready && !opened}"
                    class="relative focus:outline-none transition-transform hover:scale-110">
                    <img :src="opened ? '{{ $chestOpenedImg }}' : '{{ $chestClosedImg }}'"
                        class="w-24 h-24 object-contain drop-shadow-lg" alt="Harta Karun">
                </button>

                <template x-teleport="body">
                    <div x-show="showAnim" style="display: none;" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" @click="closeAnim()"
                        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm cursor-pointer select-none">
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

                            <div x-show="phase === 'open'" style="display: none;"
                                class="reward-up relative z-10 mt-6 text-center">
                                <p class="font-dynapuff reward-title mb-4"
                                    :class="justClaimed ? 'text-emerald-300' : 'text-white/80'"
                                    x-text="justClaimed ? 'Hadiah!' : 'Sudah Diklaim'"></p>

                                <div class="flex items-center justify-center gap-4">
                                    <template x-for="(r, i) in rewards" :key="i">
                                        <span class="reward-chip font-dynapuff relative"
                                            :class="[r.cls, justClaimed ? '' : 'is-claimed']">
                                            <span x-text="r.text"></span>

                                            <span x-show="!justClaimed" style="display: none;" class="reward-check"
                                                :style="`animation-delay: ${0.35 + i * 0.15}s`">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-4 h-4">
                                                    <path d="M5.5 12.5l4.2 4.2L18.5 7.8" stroke="#fff"
                                                        stroke-width="3.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <p x-show="phase === 'open'" style="display: none;"
                                class="font-dynapuff tap-hint relative z-10 mt-6 text-white/70 text-xs tracking-wide">
                                Ketuk untuk lanjut</p>
                        </div>
                    </div>
                </template>
            </div>
            @endif
            @endforeach
    </div>
    </section>
    @endforeach
</div>
</div>

<div x-data="{ show: false, url: '' }" @open-lesson-modal.window="show = true; url = $event.detail.url">
    @php
    $reviewIcon = [
    'bg' => 'bg-brand-50',
    'svg' => '<img src="' . asset('images/logo.png') . '" alt="Logo" class="w-10 h-10 object-contain">',
    ];
    @endphp
    <x-modal show="show" title="Review Pelajaran" :icon="$reviewIcon">
        <p class="text-sm text-gray-600 text-center">
            Pelajaran ini sudah selesai. Kamu bisa mengulanginya, tapi tidak akan mendapat tambahan XP atau energi.
        </p>

        <x-slot:footer>
            <button @click="show = false" class="flex-1 px-4 py-2 bg-gray-200 rounded-xl font-semibold cursor-pointer">
                Batal
            </button>
            <button @click="window.location.href = url"
                class="flex-1 px-4 py-2 bg-brand-500 text-white rounded-xl font-semibold cursor-pointer">
                Mulai Ulang
            </button>
        </x-slot:footer>
    </x-modal>
</div>
@endsection

@push('scripts')
<style>
    .unit-track {
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .unit-track::-webkit-scrollbar {
        display: none;
    }

    .unit-track img {
        -webkit-user-drag: none;
        user-select: none;
    }

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
        background: repeating-conic-gradient(from 0deg, rgba(52, 211, 153, .45) 0deg 10deg, transparent 10deg 30deg);
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
        background: radial-gradient(circle, rgba(110, 231, 183, .9), rgba(52, 211, 153, 0) 70%);
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
        background: #6ee7b7;
        border-radius: 9999px;
        box-shadow: 0 0 10px 3px rgba(110, 231, 183, .8);
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

    .reward-title {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .reward-chip {
        display: inline-flex;
        align-items: center;
        padding: .55rem 1.1rem;
        font-size: 1.15rem;
        font-weight: 800;
        border-radius: 1rem;
        background: rgba(16, 185, 129, .15);
        border: 2px solid rgba(110, 231, 183, .45);
    }

    .reward-chip.is-claimed {
        background: rgba(255, 255, 255, .08);
        border-color: rgba(255, 255, 255, .25);
    }

    @keyframes checkPop {
        0% {
            transform: scale(0) rotate(-30deg);
            opacity: 0;
        }

        65% {
            transform: scale(1.3) rotate(8deg);
            opacity: 1;
        }

        100% {
            transform: scale(1) rotate(0);
            opacity: 1;
        }
    }

    .reward-check {
        position: absolute;
        top: -.6rem;
        right: -.6rem;
        width: 1.6rem;
        height: 1.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background: linear-gradient(180deg, #34d399, #059669);
        border: 2px solid #fff;
        animation: checkPop .45s cubic-bezier(.22, 1, .36, 1) both;
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
        text-shadow: 0 2px 0 rgba(0, 0, 0, .6), 0 0 14px rgba(16, 185, 129, .6);
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

    @media (prefers-reduced-motion: reduce) {

        .tap-hint,
        .chest-rays,
        .reward-check {
            animation: none;
        }
    }
</style>

<script>
    function unitSlider(initial, total) {
        return {
            current: initial,
            total: total,
            dragging: false,
            moved: false,
            startX: 0,
            startScroll: 0,
            dx: 0,

            init() {
                let start = initial;
                try {
                    const saved = sessionStorage.getItem('learn_unit');
                    if (saved !== null) {
                        const n = parseInt(saved, 10);
                        if (!isNaN(n) && n >= 0 && n < this.total) start = n;
                        sessionStorage.removeItem('learn_unit');
                    }
                } catch (e) {}

                this.$nextTick(() => this.goTo(start, false));
            },

            save() {
                try { sessionStorage.setItem('learn_unit', String(this.current)); } catch (e) {}
            },

            goTo(i, smooth = true) {
                i = Math.max(0, Math.min(this.total - 1, i));
                const t = this.$refs.track;
                t.scrollTo({ left: i * t.clientWidth, behavior: smooth ? 'smooth' : 'auto' });
                this.current = i;
            },

            onScroll() {
                const t = this.$refs.track;
                this.current = Math.round(t.scrollLeft / t.clientWidth);
            },

            dragStart(e) {
                if (e.button !== 0) return;
                this.dragging = true;
                this.moved = false;
                this.dx = 0;
                this.startX = e.pageX;
                this.startScroll = this.$refs.track.scrollLeft;
            },

            dragMove(e) {
                if (!this.dragging) return;
                this.dx = e.pageX - this.startX;
                if (Math.abs(this.dx) > 5) this.moved = true;
                this.$refs.track.scrollLeft = this.startScroll - this.dx;
            },

            dragEnd() {
                if (!this.dragging) return;
                this.dragging = false;

                const t = this.$refs.track;
                let target = Math.round(t.scrollLeft / t.clientWidth);
                if (this.dx < -60) target = this.current + 1;
                else if (this.dx > 60) target = this.current - 1;

                this.goTo(target);
                setTimeout(() => { this.moved = false; }, 0);
            },
        };
    }
</script>
@endpush