@props([
'livewire' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}" @class([ 'fi min-h-screen' , 'dark'=>
filament()->hasDarkModeForced(),
])
>

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
    'min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-900 dark:text-white',
    ]) }}
    x-data="{ sidebarToggle: false, menuToggle: false, dropdownOpen: false, mobileNavOpen: false }"
    >
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_START, scopes:
    $livewire?->getRenderHookScopes()) }}

    @if(filament()->auth()->check())
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        @include('admin.partials.sidebar')

        <div :class="sidebarToggle ? 'lg:pl-[90px]' : 'lg:pl-[290px]'"
            class="flex min-h-screen flex-col transition-[padding] duration-300 ease-linear">
            @include('admin.partials.header')

            <main class="flex-1">
                <div class="p-4 md:p-6" x-cloak>
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

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_AFTER, scopes:
    $livewire?->getRenderHookScopes()) }}

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_END, scopes:
    $livewire?->getRenderHookScopes()) }}
</body>

</html>