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

<body class="h-full bg-brand-50 font-sans text-gray-900 antialiased flex flex-col justify-between">
    <header
        class="fixed top-0 left-1/2 -translate-x-1/2 z-50 w-full max-w-md bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 rounded-b-2xl shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)]">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto" />
            <span class="text-xl font-bold text-brand-600">WordUp</span>
        </a>

        <div class="flex items-center gap-3 sm:gap-4">
            <!-- Streak -->
            <div class="flex items-center gap-1 text-orange-500 font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd"
                        d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ auth()->user()->current_streak }}</span>
            </div>
            <!-- XP -->
            <div class="flex items-center gap-1 text-amber-500 font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd"
                        d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ number_format(auth()->user()->xp_total) }}</span>
            </div>
            <!-- Hearts -->
            <div class="flex items-center gap-1 text-rose-500 font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path
                        d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                </svg>
                <span class="lives-count" data-lives="{{ auth()->user()->lives }}"
                    data-max="{{ \App\Services\LifeService::MAX_LIVES }}">{{ auth()->user()->lives }}</span>
                <span class="lives-timer text-gray-400 text-xs hidden"></span>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="pt-20 {{ $showBottomNav ? 'pb-24' : 'pb-8' }}">
        <div class="flex flex-col items-center w-full max-w-md mx-auto px-4 py-6">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation Bar -->
    @if ($showBottomNav)
    @include('livewire.user.partials.bottom-nav', ['active' => $activeMenu])
    @endif

    @stack('scripts')

    @include('livewire.user.partials.lives-timer')

    <!-- Global form submit loading handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const spinnerHtml = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';

            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function () {
                    const btn = this.querySelector('button[type="submit"]:not([data-no-loading])');
                    if (btn && !btn.disabled) {
                        btn.disabled = true;
                        btn.classList.add('opacity-60', 'cursor-wait', 'pointer-events-none');
                        btn.innerHTML = spinnerHtml;
                    }
                });
            });
        });
    </script>

</body>

</html>