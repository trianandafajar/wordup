<x-filament-panels::page.simple>
    <div class="wordup-login-card">
        <div class="mb-6 flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="h-20 w-20" />
        </div>

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Sign In</h1>
            <p class="mt-1 text-sm text-gray-500">Welcome back to WordUp!</p>
        </div>

        <x-filament-panels::form id="form" wire:submit="authenticate">
            {{ $this->form }}

            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()" />
        </x-filament-panels::form>
    </div>
</x-filament-panels::page.simple>