@props([
    'pageTitle' => 'WordUp',
    'showBottomNav' => true,
    'activeMenu' => 'learn',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }} - WordUp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#ecfdf3] font-sans text-gray-900 antialiased flex flex-col justify-between">

    <!-- Top Gamification Bar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
            <span class="text-xl font-extrabold text-brand-500 tracking-wider">WordUp</span>
        </div>

        <div class="flex items-center gap-4 sm:gap-6">
            <!-- Streak -->
            <div class="flex items-center gap-1.5 text-orange-500 font-bold text-sm">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.71 19C9.93 19 8.5 17.57 8.5 15.79C8.5 14.22 9.56 12.95 11.04 12.58C12.12 12.31 13.15 11.59 13.73 10.55L14.48 9.2C14.81 8.61 15.48 8.25 16.21 8.25H16.96C17.76 8.25 18.47 8.79 18.73 9.57L19.07 10.6C19.19 10.97 19.54 11.22 19.95 11.22C20.57 11.22 21 10.79 21 10.17V9.5C21 7.57 19.43 6 17.5 6H16.21C14.44 6 12.83 6.95 12.11 8.45L11.33 10.06C10.76 11.24 9.6 12.1 8.23 12.28C6.76 12.47 5.4 13.57 4.83 15.03C4.06 16.99 5.45 19.21 7.6 19.79C8.52 20.05 9.49 20 10.33 19.72L11.71 19Z"/>
                </svg>
                <span>5</span>
            </div>
            <!-- XP -->
            <div class="flex items-center gap-1.5 text-amber-500 font-bold text-sm">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                    <path d="M2 17L12 22L22 17"/>
                    <path d="M2 12L12 17L22 12"/>
                </svg>
                <span>1,250</span>
            </div>
            <!-- Hearts -->
            <div class="flex items-center gap-1.5 text-rose-500 font-bold text-sm">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span class="lives-count" data-lives="{{ auth()->user()->lives }}" data-max="{{ \App\Services\LifeService::MAX_LIVES }}">{{ auth()->user()->lives }}</span>
                <span class="lives-timer text-gray-400 text-xs hidden"></span>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto pt-20 {{ $showBottomNav ? 'pb-24' : 'pb-8' }}">
        <div class="flex flex-col items-center w-full max-w-xl mx-auto px-4 py-6">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    @if ($showBottomNav)
        @include('livewire.user.partials.bottom-nav', ['active' => $activeMenu])
    @endif

    @stack('scripts')

@include('livewire.user.partials.lives-timer')

</body>
</html>
