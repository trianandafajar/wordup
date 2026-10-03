@extends('layouts.user', ['pageTitle' => 'Rank', 'activeMenu' => 'rank'])

@section('content')
@php
$allRanks = $allRanks ?? \App\Services\RankService::RANKS;
$currentIdx = $currentRankIndex ?? (new \App\Services\RankService)->getRankIndex($currentRank['key']);
@endphp

<div class="w-full" x-data="{ 
    open: false, 
    userDetailOpen: false, 
    lessonsDetailOpen: false, 
    selectedUser: null,
    lessons: [],
    fetchLessons(userId) {
        fetch('/api/user/' + userId + '/lessons')
            .then(res => res.json())
            .then(data => {
                this.lessons = data.lessons;
                this.userDetailOpen = false;
                this.lessonsDetailOpen = true;
            });
    },
    closeLessons() {
        this.lessonsDetailOpen = false;
        this.userDetailOpen = true;
    }
}">

    @php $total = count($allRanks); @endphp
    <div class="flex items-center justify-center gap-4 sm:gap-6 overflow-hidden pt-6 pb-4"
        style="-webkit-mask-image:linear-gradient(to right,transparent,#000 12%,#000 88%,transparent);mask-image:linear-gradient(to right,transparent,#000 12%,#000 88%,transparent);">
        @foreach ([-2, -1, 0, 1, 2] as $offset)
        @php
        $i = (($currentIdx + $offset) % $total + $total) % $total;
        $rank = $allRanks[$i];
        @endphp

        @if ($offset === 0)
        <button type="button" @click="open = true"
            class="shrink-0 w-24 flex flex-col items-center gap-1.5 relative cursor-pointer">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-24 bg-brand-500/10 rounded-full animate-pulse">
            </div>
            <img src="{{ asset('images/rank-icon/' . $rank['icon']) }}" alt="{{ $rank['name'] }}"
                class="w-24 h-24 object-contain relative z-10">
            <span class="text-sm font-extrabold text-brand-600">{{ $rank['name'] }}</span>
            <span
                class="text-[10px] font-bold bg-white text-brand-700 px-2 py-0.5 rounded-full border border-brand-100">Aktif</span>
        </button>
        @else
        <button type="button" @click="open = true"
            class="shrink-0 w-16 flex flex-col items-center gap-1.5 cursor-pointer transition-opacity hover:opacity-100 {{ $i < $currentIdx ? 'opacity-50 grayscale' : 'opacity-60' }}">
            <img src="{{ asset('images/rank-icon/' . $rank['icon']) }}" alt="{{ $rank['name'] }}"
                class="w-14 h-14 object-contain">
            <span class="text-xs font-semibold text-gray-500">{{ $rank['name'] }}</span>
        </button>
        @endif
        @endforeach
    </div>

    <p class="text-center text-sm text-gray-500">
        Total XP: <span class="font-bold text-gray-900">{{ number_format($currentUser->xp_total) }}</span>
    </p>

    @if($resetAt)
    <div class="text-center mt-2" x-data="{
        resetAt: {{ $resetAt * 1000 }},
        d: 0, h: 0, m: 0, s: 0,
        done: false,
        timer: null,
        update() {
            const diff = this.resetAt - Date.now();
            if (diff <= 0) { this.done = true; return; }
            this.d = Math.floor(diff / 86400000);
            this.h = Math.floor((diff % 86400000) / 3600000);
            this.m = Math.floor((diff % 3600000) / 60000);
            this.s = Math.floor((diff % 60000) / 1000);
        }
    }" x-init="update(); timer = setInterval(() => update(), 1000)">
        <p class="text-xs text-gray-400">XP mingguan akan direset dalam</p>
        <p class="text-sm font-bold text-gray-700 mt-0.5" x-show="!done">
            <span x-text="d"></span> hari
            <span x-text="String(h).padStart(2,'0')"></span>:<span x-text="String(m).padStart(2,'0')"></span>:<span
                x-text="String(s).padStart(2,'0')"></span>
        </p>
        <p class="text-sm font-bold text-emerald-600" x-show="done" style="display:none;">
            Segera direset...
        </p>
    </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-200 divide-y divide-gray-100 overflow-hidden mt-4">
        <button type="button" @click="selectedUser = { 
                id: {{ $currentUser->id }},
                name: '{{ $currentUser->name }}', 
                avatar: '{{ $currentUser->avatar }}', 
                total_xp: {{ $currentUser->xp_total }}, 
                completed_lessons: {{ $currentUser->courseProgress()->sum('completed_lessons') }}, 
                member_since: '{{ $currentUser->created_at->format('M Y') }}', 
                rank: {{ $currentUserLeagueRank }} 
            }; userDetailOpen = true"
            class="p-4 flex items-center gap-4 bg-emerald-50 w-full cursor-pointer text-left">
            <span class="text-lg font-bold text-emerald-600 w-8 text-center">#{{ $currentUserLeagueRank }}</span>
            @if ($currentUser->avatar)
            <img src="{{ asset('storage/' . $currentUser->avatar) }}" alt="{{ $currentUser->name }}"
                class="w-12 h-12 rounded-full object-cover shadow-md">
            @else
            <div
                class="w-12 h-12 rounded-full {{ $currentUserLeague['icon'] }} text-white flex items-center justify-center text-lg font-bold shadow-md">
                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-900">{{ $currentUser->name }}</p>
                <p class="text-xs text-emerald-600 flex items-center gap-1">
                    {{ number_format($currentUser->league_week_xp > 0 ? $currentUser->league_week_xp :
                    $currentUser->xp_total) }} XP
                </p>
            </div>
            <span
                class="text-xs font-bold bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full border border-current">Kamu</span>
        </button>

        @forelse ($leagueUsers as $i => $user)
        @if ($user['id'] !== $currentUser->id)
        <button type="button" @click="selectedUser = {{ json_encode($user) }}; userDetailOpen = true"
            class="w-full flex items-center gap-4 p-4 {{ $i < 3 ? 'text-emerald-600' : 'text-gray-400' }} hover:bg-gray-50 transition-colors cursor-pointer text-left">
            <span class="text-sm font-bold w-8 text-center">{{ $user['rank'] }}</span>
            @if ($user['avatar'])
            <img src="{{ asset('storage/' . $user['avatar']) }}" alt="{{ $user['name'] }}"
                class="w-10 h-10 rounded-full object-cover shadow-sm">
            @else
            <div
                class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold shadow-sm text-sm">
                {{ strtoupper(substr($user['name'], 0, 1)) }}
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800">{{ $user['name'] }}</p>
                <p class="text-xs text-gray-500">{{ number_format($user['xp']) }}
                    XP
                </p>
            </div>
        </button>
        @endif
        @empty
        <p class="text-center text-gray-400 py-6">Belum ada user lain di league ini</p>
        @endforelse
    </div>

    <div x-show="open" x-transition.opacity style="display:none;"
        class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/25 backdrop-blur-md backdrop-saturate-150">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-xl" @click.away="open = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-lg">
                    Daftar Rank
                </h3>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-3 overflow-y-auto">
                @foreach ($allRanks as $index => $rank)
                <div
                    class="flex items-center gap-4 p-3 rounded-2xl {{ $index === $currentIdx ? 'bg-brand-50 border border-brand-200' : 'bg-gray-50 border border-gray-100' }}">
                    <img src="{{ asset('images/rank-icon/' . $rank['icon']) }}" alt="{{ $rank['name'] }}"
                        class="w-12 h-12 object-contain {{ $index > $currentIdx ? 'grayscale opacity-50' : '' }}">
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 text-sm">{{ $rank['name'] }}</p>
                        <p class="text-xs text-gray-500">Butuh {{ number_format($rank['min_xp']) }} XP</p>
                    </div>
                    @if ($index <= $currentIdx) <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-500"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                        </svg>
                        @else
                        <span class="text-xs font-bold text-gray-400">Lock</span>
                        @endif
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600">XP kamu: <span class="font-bold text-gray-900">{{
                        number_format($currentUser->xp_total) }}</span></p>
                @php $nextRank = $allRanks[$currentIdx + 1] ?? null; @endphp
                @if ($nextRank)
                <p class="text-xs text-gray-500 mt-1">Butuh {{ number_format($nextRank['min_xp'] -
                    $currentUser->xp_total) }} XP lagi untuk <span class="font-semibold text-gray-700">{{
                        $nextRank['name'] }}</span></p>
                @else
                <p class="text-xs text-brand-600 mt-1 font-bold">Rank tertinggi tercapai!</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Overlay bersama: Detail Profil + Pelajaran Selesai --}}
    <div x-show="userDetailOpen || lessonsDetailOpen" x-transition.opacity style="display:none;"
        class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/25 backdrop-blur-md backdrop-saturate-150">

        {{-- Card Detail Profil --}}
        <div x-show="userDetailOpen" style="display:none;" class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-xl"
            @click.away="userDetailOpen = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-lg">Detail Profil</h3>
                <button type="button" @click="userDetailOpen = false"
                    class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4" x-show="selectedUser">
                <div class="flex justify-center">
                    <template x-if="selectedUser && selectedUser.avatar">
                        <img :src="'{{ asset('storage') }}/' + selectedUser.avatar" :alt="selectedUser.name"
                            class="w-24 h-24 rounded-full object-cover shadow-md">
                    </template>
                    <template x-if="selectedUser && !selectedUser.avatar">
                        <div
                            class="w-24 h-24 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold shadow-md text-2xl">
                            <span x-text="selectedUser.name.charAt(0).toUpperCase()"></span>
                        </div>
                    </template>
                </div>

                <div class="text-center">
                    <h4 class="font-bold text-gray-900 text-lg" x-text="selectedUser ? selectedUser.name : ''"></h4>
                </div>

                <div class="space-y-3 pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total XP</span>
                        <span class="font-bold text-gray-900"
                            x-text="selectedUser ? new Intl.NumberFormat('id-ID').format(selectedUser.total_xp) : ''"></span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Pelajaran Selesai</span>
                        <span class="font-bold text-gray-900"
                            x-text="selectedUser ? selectedUser.completed_lessons : ''"></span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Member Sejak</span>
                        <span class="font-bold text-gray-900"
                            x-text="selectedUser ? selectedUser.member_since : ''"></span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Peringkat (Liga)</span>
                        <span class="font-bold text-emerald-600"
                            x-text="selectedUser ? '#' + selectedUser.rank : ''"></span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <button type="button" @click="fetchLessons(selectedUser.id)"
                    class="w-full px-4 py-2 bg-brand-500 text-white font-semibold rounded-xl hover:bg-brand-600 transition-colors mb-2 cursor-pointer">
                    Lihat Pelajaran Selesai
                </button>
                <button type="button" @click="userDetailOpen = false"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-900 font-semibold rounded-xl hover:bg-gray-200 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>

        <div x-show="lessonsDetailOpen" style="display:none;"
            class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-xl flex flex-col" @click.away="closeLessons()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-lg">Pelajaran Selesai</h3>
                <button type="button" @click="closeLessons()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="relative overflow-y-auto space-y-3 snap-y snap-mandatory pr-1 max-h-[23.5rem]">
                <template x-for="lesson in lessons" :key="lesson.lesson_title">
                    <div data-lesson class="bg-gray-50 rounded-2xl p-4 border border-gray-100 snap-start">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm truncate" x-text="lesson.lesson_title"></p>
                                <p class="text-xs text-gray-500 mt-0.5 truncate" x-text="lesson.unit_title"></p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span x-text="'Skor: ' + lesson.best_score"></span>
                                    </span>
                                    <span x-text="lesson.attempts_count + 'x percobaan'"></span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1" x-text="lesson.completed_at"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="lessons.length === 0">
                    <div class="text-center py-8">
                        <p class="text-gray-400">Belum ada pelajaran yang diselesaikan</p>
                    </div>
                </template>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <button type="button" @click="closeLessons()"
                    class="w-full px-4 py-2 bg-gray-100 text-gray-900 font-semibold rounded-xl hover:bg-gray-200 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection