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
        class="fixed top-0 left-1/2 -translate-x-1/2 z-50 w-full max-w-md bg-white border-b border-gray-200 h-16 flex items-center justify-between px-3 sm:px-4 rounded-b-2xl shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1)]">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto" />
            <span class="text-xl font-bold text-brand-600">WordUp</span>
        </a>

        <div class="flex items-center gap-1.5 sm:gap-3">
            <div class="relative flex items-center min-w-14 justify-center gap-2 font-bold text-sm cursor-pointer select-none"
                @click="activeStat = (activeStat === 'streak' ? 'xp' : (activeStat === 'xp' ? 'lives' : 'streak'))"
                title="Klik untuk mengganti stat">

                <!-- Streak -->
                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="flex items-center gap-1.5 text-orange-500">
                    <img src="{{ asset(auth()->user()->last_activity_date?->isToday() ? 'images/icon-stats/strike-active.png' : 'images/icon-stats/strike-inactive.png') }}" alt="Streak" class="h-6 w-6 object-contain" />
                    <span>{{ auth()->user()->current_streak }}</span>
                </div>

                <!-- XP -->
                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="flex items-center gap-1.5 text-amber-500">
                    <img src="{{ asset('images/icon-stats/xp.png') }}" alt="XP" class="h-6 w-6 object-contain" />
                    <span>{{ number_format(auth()->user()->xp_total) }}</span>
                </div>

                <!-- Lives -->
                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="flex items-center gap-1.5 text-rose-500">
                    <img src="{{ asset(auth()->user()->lives > 0 ? 'images/icon-stats/life-active.png' : 'images/icon-stats/life-inactive.png') }}" alt="Lives" class="h-6 w-6 object-contain" />
                    <span class="lives-count min-w-3 text-center tabular-nums" data-lives="{{ auth()->user()->lives }}"
                        data-max="{{ \App\Services\LifeService::MAX_LIVES }}">{{ auth()->user()->lives }}</span>
                    <span class="lives-timer hidden absolute left-1/2 top-full z-10 -translate-x-1/2 whitespace-nowrap pt-0.5 font-mono text-[10px] font-normal leading-none text-gray-500 tabular-nums sm:text-[11px]"
                        role="timer" aria-label="Waktu isi ulang nyawa berikutnya" title="Waktu isi ulang nyawa berikutnya">
                        <span class="lives-timer-value">00:00:00</span>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-20 {{ $showBottomNav ? 'pb-24' : 'pb-8' }}">
        <div class="flex flex-col items-center w-full max-w-md mx-auto px-4 py-6">
            @yield('content')
        </div>
    </main>

    @if ($showBottomNav)
    @include('livewire.user.partials.bottom-nav', ['active' => $activeMenu])
    @endif

    @stack('scripts')

    @include('livewire.user.partials.lives-timer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const spinnerHtml = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></svg> Memproses...';

            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function () {
                    const btn = this.querySelector('button[type="submit"]:not([data-no-loading])');
                    if (btn && !btn.disabled) {
                        btn.disabled = true;
                        btn.classList.add('opacity-60', 'btn-disabled-override');
                        btn.innerHTML = spinnerHtml;
                    }
                });
            });
        });
    </script>

</body>

</html>