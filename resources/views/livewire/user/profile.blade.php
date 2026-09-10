@extends('layouts.user', ['pageTitle' => 'Profile', 'activeMenu' => 'profile'])

@section('content')
<div class="w-full">

    @if (session('status'))
    <div class="mb-6 rounded-lg bg-[#4caf50]/10 border border-[#4caf50]/30 p-4 text-sm text-[#1a2231]">
        {{ session('status') }}
    </div>
    @endif

    <!-- Profile Header -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-5">
            <div class="relative">
                @if ($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                @else
                <div
                    class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 border-2 border-gray-200">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.866 0-7 1.79-7 4v2h14v-2c0-2.21-3.134-4-7-4z" />
                    </svg>
                </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-bold text-gray-900 truncate">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $completedLessonsCount }} pelajaran selesai</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4">Edit Profil</h2>
        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-[#1a2231] mb-1.5">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-[#1a2231] placeholder-[#1a2231]/30 focus:outline-none focus:ring-2 focus:ring-[#4caf50] focus:border-[#4caf50] sm:text-sm">
                @error('name')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1a2231] mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-[#1a2231] placeholder-[#1a2231]/30 focus:outline-none focus:ring-2 focus:ring-[#4caf50] focus:border-[#4caf50] sm:text-sm">
                @error('email')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1a2231] mb-1.5">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, atau WebP. Maks 2MB.</p>
                @error('avatar')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-[#4caf50] py-2.5 px-4 text-sm font-medium text-white hover:bg-[#43a047] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4caf50] transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-orange-500 font-extrabold text-xl">
                <x-heroicon-s-fire class="w-6 h-6 inline" />
            </div>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $user->current_streak }}</p>
            <p class="text-xs text-gray-500">Streak</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-amber-500 font-extrabold text-xl">
                <x-heroicon-s-sparkles class="w-6 h-6 inline" />
            </div>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ number_format($totalXp) }}</p>
            <p class="text-xs text-gray-500">XP</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-rose-500 font-extrabold text-xl">
                <x-heroicon-s-heart class="w-6 h-6 inline" />
            </div>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $user->lives }}</p>
            <p class="text-xs text-gray-500">Lives</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4">Badge</h2>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($badges as $badge)
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full {{ $badge['color'] }} flex items-center justify-center font-bold">
                    @if ($badge['icon'] === 'book-open')
                    <x-heroicon-s-book-open class="w-5 h-5" />
                    @elseif ($badge['icon'] === 'sparkles')
                    <x-heroicon-s-sparkles class="w-5 h-5" />
                    @elseif ($badge['icon'] === 'fire')
                    <x-heroicon-s-fire class="w-5 h-5" />
                    @elseif ($badge['icon'] === 'trophy')
                    <x-heroicon-s-trophy class="w-5 h-5" />
                    @elseif ($badge['icon'] === 'user')
                    <x-heroicon-s-user class="w-5 h-5" />
                    @endif
                </span>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $badge['title'] }}</p>
                    <p class="text-xs text-gray-500">{{ $badge['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 text-sm mb-4">Aktivitas Terbaru</h2>
        <div class="space-y-2 text-xs text-gray-500">
            @forelse ($recentActivities as $activity)
            <div class="flex items-center gap-3">
                <span
                    class="w-3 h-3 rounded-full {{ $activity->status === 'completed' ? 'bg-brand-500' : 'bg-gray-400' }}"></span>
                <span class="flex-1">{{ $activity->lesson->title }}</span>
                <span class="text-gray-300">{{ $activity->completed_at ? $activity->completed_at->diffForHumans() : '-'
                    }}</span>
            </div>
            @empty
            <p class="text-center text-gray-400 py-4">Belum ada aktivitas</p>
            @endforelse
        </div>
    </div>

    <div x-data="{ showLogoutModal: false }">
        <button type="button" @click="showLogoutModal = true"
            class="w-full py-3 rounded-xl bg-red-500 border border-red-200 text-white font-semibold text-sm hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                <path fill-rule="evenodd"
                    d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
            Logout
        </button>

        <div x-show="showLogoutModal" x-transition x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 px-4">
            <div @click.outside="showLogoutModal = false" x-transition
                class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">
                <div class="text-center">
                    <div class="mx-auto mb-4 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Yakin ingin logout?</h3>
                    <p class="mt-2 text-sm text-gray-500">Kamu harus login kembali untuk mengakses akunmu.</p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showLogoutModal = false"
                        class="flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1"
                        x-data="{ isLoggingOut: false }" @submit="isLoggingOut = true">
                        @csrf
                        <button type="submit" data-no-loading :disabled="isLoggingOut"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-500 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-red-600 disabled:cursor-wait disabled:opacity-75">
                            <svg x-show="isLoggingOut" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span x-text="isLoggingOut ? 'Logging out...' : 'Logout'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
