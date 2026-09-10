@props([
'livewire' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}" class="fi min-h-screen">

<head>
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_START, scopes:
    $livewire?->getRenderHookScopes()) }}

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @if ($favicon = filament()->getFavicon())
    <link rel="icon" href="{{ $favicon }}" />
    @endif

    @php
    $title = trim(strip_tags(($livewire ?? null)?->getTitle() ?? ''));
    $brandName = trim(strip_tags(filament()->getBrandName()));
    @endphp

    <title>
        {{ filled($title) ? "{$title} - " : null }} {{ $brandName }}
    </title>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_BEFORE, scopes:
    $livewire?->getRenderHookScopes()) }}

    @filamentStyles

    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}

    <style>
        [x-cloak=''],
        [x-cloak='x-cloak'],
        [x-cloak='1'] {
            display: none !important;
        }

        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';

            --sidebar-width: {
                    {
                    filament()->getSidebarWidth()
                }
            }

            ;

            --collapsed-sidebar-width: {
                    {
                    filament()->getCollapsedSidebarWidth()
                }
            }

            ;

            --default-theme-mode: {
                    {
                    filament()->getDefaultThemeMode()->value
                }
            }

            ;
        }
    </style>

    @stack('styles')

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_AFTER, scopes:
    $livewire?->getRenderHookScopes()) }}

    @livewire('notifications')
</head>

<body {{ $attributes ->merge(($livewire ?? null)?->getExtraBodyAttributes() ?? [], escape: false)
    ->class([
    'fi-body',
    'fi-panel-'.filament()->getId(),
    'min-h-screen bg-gray-100 font-normal text-gray-950 antialiased',
    ]) }}
    x-data="{ sidebarToggle: false, menuToggle: false, dropdownOpen: false, mobileNavOpen: false }"
    >
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_START, scopes:
    $livewire?->getRenderHookScopes()) }}

    @if(filament()->auth()->check())
    <div class="min-h-screen bg-gray-100">
        @include('admin.partials.sidebar')

        <div x-show="sidebarToggle" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarToggle = false"
            class="fixed inset-0 z-50 bg-black/50 cursor-pointer lg:hidden" x-cloak></div>

        <div :class="sidebarToggle ? 'lg:pl-[90px]' : 'lg:pl-[290px]'"
            class="flex min-h-screen flex-col transition-[padding] duration-300 ease-linear">
            @include('admin.partials.header')

            <main class="flex-1">
                <div class="p-4 md:p-6 overflow-x-auto" x-cloak>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    @else
    {{ $slot }}
    @endif

    @php
    $baseActionModals = $baseActionModals ?? true;
    @endphp

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_BEFORE, scopes:
    $livewire?->getRenderHookScopes()) }}

    @filamentScripts(withCore: true)

    @if (filament()->hasBroadcasting() && config('filament.broadcasting.echo'))
    <script data-navigate-once>
        window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                    window.dispatchEvent(new CustomEvent('EchoLoaded'))
    </script>
    @endif

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const spinnerHtml = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';

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

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_AFTER, scopes:
    $livewire?->getRenderHookScopes()) }}

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_END, scopes:
    $livewire?->getRenderHookScopes()) }}
</body>

</html>