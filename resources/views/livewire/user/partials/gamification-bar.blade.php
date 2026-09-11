<header
    class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-8">
    <div class="flex items-center gap-3">
        <span class="text-xl font-extrabold text-brand-500 tracking-wider">WordUp</span>
        <span
            class="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-600">English
            Beginner</span>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-3">
        <div class="flex min-w-12 items-center justify-center gap-1.5 text-orange-500 font-bold text-sm">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>5</span>
        </div>

        <div class="flex min-w-12 items-center justify-center gap-1.5 text-amber-500 font-bold text-sm">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" />
                <path d="M2 17L12 22L22 17" />
                <path d="M2 12L12 17L22 12" />
            </svg>
            <span>1,250</span>
        </div>

        <div class="relative flex min-w-12 items-center justify-center gap-1.5 text-rose-500 font-bold text-sm">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
                <span class="lives-count min-w-3 text-center tabular-nums" data-lives="{{ auth()->user()->lives }}"
                    data-max="{{ \App\Services\LifeService::MAX_LIVES }}">{{ auth()->user()->lives }}</span>
            <span class="lives-timer hidden absolute left-1/2 top-full z-10 -translate-x-1/2 whitespace-nowrap pt-0.5 font-mono text-[10px] font-normal leading-none text-gray-500 tabular-nums sm:text-[11px]"
                role="timer" aria-label="Waktu isi ulang nyawa berikutnya" title="Waktu isi ulang nyawa berikutnya">
                <span class="lives-timer-value">00:00:00</span>
            </span>
        </div>
    </div>
</header>
