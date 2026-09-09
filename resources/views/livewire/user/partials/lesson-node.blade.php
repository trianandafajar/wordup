@props([
    'id' => 1,
    'title' => 'Lesson',
    'status' => 'locked',   // locked | available | completed
    'xp_reward' => 10,
    'best_score' => null,
    'type' => 'reading',    // reading | listening | speaking | quiz
    'has_crown' => false,
])

@php
    $isLocked = $status === 'locked';
    $isCompleted = $status === 'completed';
    $isAvailable = $status === 'available';

    $nodeClasses = match($status) {
        'completed' => 'bg-brand-500 border-brand-600 shadow-lg shadow-brand-500/30 cursor-default',
        'available' => 'bg-brand-500 border-brand-400 shadow-lg shadow-brand-500/30 animate-pulse-slow cursor-pointer hover:scale-105',
        'locked' => 'bg-gray-200 border-gray-300 opacity-60 cursor-not-allowed',
    };

    $iconColor = $isLocked ? 'text-gray-400' : 'text-white';
@endphp

<button {{ $isLocked ? 'disabled' : '' }} class="relative group focus:outline-none {{ $nodeClasses }} w-14 h-14 lg:w-16 lg:h-16 rounded-full border-4 transition-all duration-300 flex items-center justify-center shrink-0 z-10">
    @if ($isLocked)
        <!-- Lock icon -->
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-gray-400 fill-current">
            <path d="M12 2C9.243 2 7 4.243 7 7V10H6C4.897 10 4 10.897 4 12V20C4 21.103 4.897 22 6 22H18C19.103 22 20 21.103 20 20V12C20 10.897 19.103 10 18 10H17V7C17 4.243 14.757 2 12 2ZM12 4C13.654 4 15 5.346 15 7V10H9V7C9 5.346 10.346 4 12 4ZM18 12V20H6V12H18ZM12 13C11.448 13 11 13.448 11 14V18C11 18.552 11.448 19 12 19C12.552 19 13 18.552 13 18V14C13 13.448 12.552 13 12 13Z"/>
        </svg>
    @elseif ($isCompleted)
        <!-- Checkmark / Star icon -->
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white fill-current">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
    @else
        <!-- Available / Lesson type icon -->
        @if ($type === 'listening')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white fill-current">
                <path d="M12 3C10.34 3 9 4.37 9 6V12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12V6C15 4.37 13.66 3 12 3ZM19 12C19 15.53 16.39 18.44 13 18.92V21H11V18.92C7.61 18.44 5 15.53 5 12H7C7 14.76 9.24 17 12 17C14.76 17 17 14.76 17 12H19Z"/>
            </svg>
        @elseif ($type === 'speaking')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white fill-current">
                <path d="M12 14C13.66 14 15 12.66 15 11V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 12.66 10.34 14 12 14ZM17 11C17 13.76 14.76 16 12 16C9.24 16 7 13.76 7 11H5C5 14.53 7.61 17.43 11 17.92V21H13V17.92C16.39 17.43 19 14.53 19 11H17Z"/>
            </svg>
        @else
            <!-- Default: reading/book icon -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white fill-current">
                <path d="M18 2H6C4.89 2 4 2.9 4 4V20C4 21.1 4.89 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.11 2 18 2ZM6 4H11V12L8.5 10.5L6 12V4Z"/>
            </svg>
        @endif
    @endif

    <!-- XP Reward Badge (when completed) -->
    @if ($isCompleted)
        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 shadow-sm">
            +{{ $xp_reward }}
        </span>
    @endif

    <!-- Available indicator ring -->
    @if ($isAvailable)
        <span class="absolute inset-0 rounded-full border-2 border-brand-400/60 animate-ping"></span>
    @endif

    <!-- Crown for perfect score -->
    @if ($has_crown)
        <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 text-amber-500"><x-heroicon-s-star class="w-5 h-5" /></span>
    @endif

    <!-- Tooltip -->
    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:block z-20 pointer-events-none">
        <div class="bg-gray-900 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
            <span class="font-bold">{{ $title }}</span>
            @if ($isCompleted && $best_score !== null)
                <span class="ml-1.5 opacity-75">— Best: {{ $best_score }}%</span>
            @elseif ($isAvailable)
                <span class="ml-1.5 opacity-75">— +{{ $xp_reward }} XP</span>
            @elseif ($isLocked)
                <span class="ml-1.5 opacity-75">— Selesaikan lesson sebelumnya</span>
            @endif
            <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-px">
                <div class="w-2 h-2 bg-gray-900 rotate-45 transform"></div>
            </div>
        </div>
    </div>
</button>
