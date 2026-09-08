@props([
'number' => 1,
'title' => 'Unit Title',
'description' => '',
'color' => 'bg-brand-500',
])

<div class="w-full max-w-lg my-6">
    <div class="rounded-2xl bg-brand-500 text-white p-4 shadow-md flex items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider opacity-80">Unit {{ $number }}</span>
            <h3 class="text-lg font-bold leading-snug">{{ $title }}</h3>
            @if ($description)
            <p class="text-xs opacity-90 mt-0.5 line-clamp-1">{{ $description }}</p>
            @endif
        </div>
        <div class="shrink-0 bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="fill-current">
                <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" />
            </svg>
        </div>
    </div>
</div>