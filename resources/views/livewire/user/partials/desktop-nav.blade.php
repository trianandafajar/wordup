@props([
'active' => 'learn',
])

<nav class="hidden lg:flex fixed left-0 top-0 bottom-0 w-64 border-r border-gray-200 bg-white flex-col p-6 z-40">
    <div class="mb-10 text-2xl font-extrabold text-brand-500 tracking-wider">WordUp</div>

    <div class="flex flex-col gap-2">
        <a href="/"
            class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'home' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-home class="w-6 h-6" /> Home
        </a>
        <a href="{{ route('user.learn') }}"
            class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'learn' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-book-open class="w-6 h-6" /> Learn
        </a>
        <a href="{{ route('user.leaderboard') }}"
            class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'rank' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-trophy class="w-6 h-6" /> Leaderboard
        </a>
        <a href="{{ route('user.profile') }}"
            class="flex items-center gap-4 p-3 rounded-xl font-semibold transition-colors {{ $active === 'profile' ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-100' }}">
            <x-heroicon-o-user class="w-6 h-6" /> Profile
        </a>

        <div class="mt-auto" x-data="{ showLogoutModal: false }">
            <button type="button" @click="showLogoutModal = true"
                class="flex items-center gap-4 p-3 rounded-xl font-semibold w-full text-gray-600 hover:bg-gray-100 transition-colors">
                <x-heroicon-o-arrow-right-on-rectangle class="w-6 h-6" /> Logout
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
                        <form action="{{ route('logout') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-red-500 text-white font-semibold text-sm hover:bg-red-600 transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
