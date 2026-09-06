@php
$livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="h-full">
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_BEFORE, scopes:
        $livewire?->getRenderHookScopes()) }}

        {{ $slot }}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_AFTER, scopes:
        $livewire?->getRenderHookScopes()) }}
    </div>
</x-filament-panels::layout.base>