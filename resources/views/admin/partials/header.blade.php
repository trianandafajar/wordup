<header class="sticky top-0 z-99999 flex w-full border-b border-gray-200 bg-white">
    <div class="flex w-full items-center justify-between px-3 py-3 sm:px-5 sm:py-4 lg:px-6">
        <!-- hamburger -->
        <button
            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition-colors hover:bg-gray-100 lg:h-11 lg:w-11"
            @click.stop="sidebarToggle = !sidebarToggle">
            <svg class="hidden fill-current lg:block" width="16" height="12" viewBox="0 0 16 12" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
                    fill="currentColor" />
            </svg>
            <svg class="fill-current lg:hidden" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M3.25 6C3.25 5.58579 3.58579 5.25 4 5.25L20 5.25C20.4142 5.25 20.75 5.58579 20.75 6C20.75 6.41421 20.4142 6.75 20 6.75L4 6.75C3.58579 6.75 3.25 6.41422 3.25 6ZM3.25 18C3.25 17.5858 3.58579 17.25 4 17.25L20 17.25C20.4142 17.25 20.75 17.5858 20.75 18C20.75 18.4142 20.4142 18.75 20 18.75L4 18.75C3.58579 18.75 3.25 18.4142 3.25 18ZM4 11.25C3.58579 11.25 3.25 11.5858 3.25 12C3.25 12.4142 3.58579 12.75 4 12.75L12 12.75C12.4142 12.75 12.75 12.4142 12.75 12C12.75 11.5858 12.4142 11.25 12 11.25L4 11.25Z"
                    fill="currentColor" />
            </svg>
        </button>

        <div class="flex items-center gap-3">
            <!-- user area -->
            @auth
            <div class="relative" x-data="{ userMenuOpen: false, showLogoutModal: false }"
                @click.outside="userMenuOpen = false">
                <a class="flex items-center text-gray-700" href="#" @click.prevent="userMenuOpen = !userMenuOpen">
                    <span
                        class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-brand-100 text-sm font-semibold text-brand-700 lg:h-11 lg:w-11">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </span>
                    <span class="hidden ml-2 text-sm font-medium sm:block">{{ auth()->user()->name }}</span>
                    <svg :class="userMenuOpen && 'rotate-180'" class="ml-1 h-4 w-4 stroke-gray-500" viewBox="0 0 18 20"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.3125 8.65625L9 13.3437L13.6875 8.65625" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <!-- dropdown -->
                <div x-show="userMenuOpen" x-transition x-cloak
                    class="absolute right-0 z-99999 mt-4.25 w-64 flex flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg">
                    <div class="border-b border-gray-200 pb-3">
                        <span class="block text-sm font-medium text-gray-700">{{ auth()->user()->name
                            }}</span>
                        <span class="mt-0.5 block text-xs text-gray-500">{{ auth()->user()->email
                            }}</span>
                    </div>
                    <button type="button" @click="userMenuOpen = false; showLogoutModal = true"
                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        <svg class="fill-gray-500 group-hover:fill-gray-700" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M15.1007 19.247C14.6865 19.247 14.3507 18.9112 14.3507 18.497L14.3507 14.245H12.8507V18.497C12.8507 19.7396 13.8581 20.747 15.1007 20.747H18.5007C19.7434 20.747 20.7507 19.7396 20.7507 18.497L20.7507 5.49609C20.7507 4.25345 19.7433 3.24609 18.5007 3.24609H15.1007C13.8581 3.24609 12.8507 4.25345 12.8507 5.49609V9.74501L14.3507 9.74501V5.49609C14.3507 5.08188 14.6865 4.74609 15.1007 4.74609L18.5007 4.74609C18.9149 4.74609 19.2507 5.08188 19.2507 5.49609L19.2507 18.497C19.2507 18.9112 18.9149 19.247 18.5007 19.247H15.1007ZM3.25073 11.9984C3.25073 12.2144 3.34204 12.4091 3.48817 12.546L8.09483 17.1556C8.38763 17.4485 8.86251 17.4487 9.15549 17.1559C9.44848 16.8631 9.44863 16.3882 9.15583 16.0952L5.81116 12.7484L16.0007 12.7484C16.4149 12.7484 16.7507 12.4127 16.7507 11.9984C16.7507 11.5842 16.4149 11.2484 16.0007 11.2484L5.81528 11.2484L9.15585 7.90554C9.44864 7.61255 9.44847 7.13767 9.15547 6.84488C8.86248 6.55209 8.3876 6.55226 8.09481 6.84525L3.52309 11.4202C3.35673 11.5577 3.25073 11.7657 3.25073 11.9984Z"
                                fill="currentColor" />
                        </svg>
                        <span>Logout</span>
                    </button>

                    <!-- Modal Confirm Logout -->
                    <div x-show="showLogoutModal" x-transition x-cloak
                        class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50 px-4">
                        <div @click.outside="showLogoutModal = false" x-transition
                            class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl">
                            <div class="text-center">
                                <div
                                    class="mx-auto mb-4 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Yakin ingin logout?</h3>
                                <p class="mt-2 text-sm text-gray-500">Kamu harus login kembali untuk mengakses panel
                                    admin.</p>
                            </div>
                            <div class="mt-6 flex gap-3">
                                <button type="button" @click="showLogoutModal = false"
                                    class="flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <form method="post" action="{{ filament()->getLogoutUrl() }}" class="flex-1">
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
            @endauth
        </div>
    </div>
</header>