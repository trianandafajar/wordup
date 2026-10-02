@props(['show' => false, 'title' => null, 'icon' => null])

<div x-show="{{ $show }}" x-transition x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/25 backdrop-blur-md backdrop-saturate-150 px-4">
    <div {{ $attributes->merge(['class' => 'bg-white rounded-2xl p-6 w-full max-w-sm shadow-xl border border-white/20']) }}
        @click.outside="{{ $show }} = false">
        
        @if ($icon)
        <div class="mx-auto mb-4 w-12 h-12 rounded-full {{ $icon['bg'] }} flex items-center justify-center">
            {!! $icon['svg'] !!}
        </div>
        @endif

        @if ($title)
        <h3 class="text-lg font-bold text-gray-900 text-center @if ($icon) mb-2 @else mb-4 @endif">
            {{ $title }}
        </h3>
        @endif

        <div @if ($title) class="mb-6" @endif>
            {{ $slot }}
        </div>

        @if ($footer ?? false)
        <div class="mt-6 flex gap-3">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>