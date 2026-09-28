@props(['show' => false])

<div x-show="{{ $show }}" x-transition x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 px-4">
    <div {{ $attributes->merge(['class' => 'bg-white/50 backdrop-blur-2xl rounded-2xl p-6 w-full max-w-sm shadow-xl border border-white/20']) }}
        @click.outside="{{ $show }} = false">
        {{ $slot }}
    </div>
</div>
