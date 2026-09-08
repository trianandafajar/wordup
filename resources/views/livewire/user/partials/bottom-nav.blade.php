@props([
'active' => 'learn',
])

<nav
    class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 px-4 py-2 flex justify-around items-center safe-area-bottom lg:max-w-xl lg:mx-auto lg:rounded-t-2xl lg:shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
    <a href="/"
        class="flex flex-col items-center gap-0.5 py-1 transition-colors {{ $active === 'home' ? 'text-brand-500' : 'text-gray-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3L2 12H5V20H10V14H14V20H19V12H22L12 3Z" />
        </svg>
        <span class="text-[10px] font-semibold">Home</span>
    </a>
    <a href="{{ route('user.learn') }}"
        class="flex flex-col items-center gap-0.5 py-1 transition-colors {{ $active === 'learn' ? 'text-brand-500' : 'text-gray-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M4 6H20V18H4V6ZM2 4C1.44772 4 1 4.44772 1 5V19C1 19.5523 1.44772 20 2 20H22C22.5523 20 23 19.5523 23 19V5C23 4.44772 22.5523 4 22 4H2ZM6 8H18V10H6V8ZM6 12H14V14H6V12Z" />
        </svg>
        <span class="text-[10px] font-bold">Learn</span>
    </a>
    <a href="{{ route('user.leaderboard') }}"
        class="flex flex-col items-center gap-0.5 py-1 transition-colors {{ $active === 'rank' ? 'text-brand-500' : 'text-gray-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
        </svg>
        <span class="text-[10px] font-semibold">Rank</span>
    </a>
    <a href="{{ route('user.profile') }}"
        class="flex flex-col items-center gap-0.5 py-1 transition-colors {{ $active === 'profile' ? 'text-brand-500' : 'text-gray-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5ZM12 19.2C9.5 19.2 7.29 17.92 6 15.98C6.03 13.99 10 12.9 12 12.9C14 12.9 17.97 13.99 18 15.98C16.71 17.92 14.5 19.2 12 19.2Z" />
        </svg>
        <span class="text-[10px] font-semibold">Profile</span>
    </a>
</nav>