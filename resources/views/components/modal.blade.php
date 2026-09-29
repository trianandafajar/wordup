@props(['show' => false])

<div x-show="{{ $show }}" x-transition x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/25 backdrop-blur-md backdrop-saturate-150 px-4">
    <div {{ $attributes->merge(['class' => 'bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl border border-white/20']) }}
        @click.outside="{{ $show }} = false">
        {{ $slot }}
    </div>
</div>