@extends('layouts.user', ['pageTitle' => 'Learn', 'activeMenu' => 'learn'])

@section('content')
@foreach ($units as $unitData)
<div class="w-full max-w-lg mb-4">
    <div
        class="rounded-2xl {{ $unitData['lessons']->every('status', 'completed') ? 'bg-brand-500' : 'bg-gray-300 text-gray-600' }} text-white p-4 shadow-md flex items-center justify-between gap-4 mb-8">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider opacity-80">Unit {{ $loop->iteration }}</span>
            <h3 class="text-lg font-bold leading-snug">{{ $unitData['unit']->title }}</h3>
            @if ($unitData['lessons']->firstWhere('status', 'available'))
            <p class="text-xs opacity-90 mt-0.5">Sedang berjalan</p>
            @elseif ($unitData['lessons']->every('status', 'completed'))
            <p class="text-xs opacity-90 mt-0.5">Selesai</p>
            @else
            <p class="text-xs opacity-80 mt-0.5">Terkunci</p>
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

<!-- Path Zigzag Lessons -->
<div class="flex flex-col items-center gap-0 w-full mb-8" style="padding-bottom: 2rem;">
    @foreach ($unitData['lessons'] as $index => $lesson)
    @php
    $isFirst = $index === 0;
    $isLast = $index === $unitData['lessons']->count() - 1;
    $isAvailable = $lesson['status'] === 'available';
    $isCompleted = $lesson['status'] === 'completed';
    $isLocked = $lesson['status'] === 'locked';

    // Zigzag: center, left, center, right pattern
    $position = $index % 4;
    $mlClass = $position === 1 ? 'self-start ml-8 sm:ml-16' : ($position === 3 ? 'self-end mr-8 sm:mr-16' : 'self-center
    ml-0');
    @endphp

    <!-- Lesson Node -->
    <div class="{{ $mlClass }} mb-0">
        <button @if (!$isLocked) onclick="window.location.href='{{ route('user.lesson.practice', $lesson['id']) }}'"
            @else disabled @endif
            class="relative group focus:outline-none w-14 h-14 lg:w-16 lg:h-16 rounded-full border-4 transition-all duration-300 flex items-center justify-center shrink-0 z-10 hover:scale-105 {{ $isLocked ? 'bg-gray-200 border-gray-300 opacity-60 cursor-not-allowed' : 'bg-brand-500 border-brand-400 shadow-lg shadow-brand-500/30' }}">
            @if ($isLocked)
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-gray-400 fill-current">
                <path
                    d="M12 2C9.243 2 7 4.243 7 7V10H6C4.897 10 4 10.897 4 12V20C4 21.103 4.897 22 6 22H18C19.103 22 20 21.103 20 20V12C20 10.897 19.103 10 18 10H17V7C17 4.243 14.757 2 12 2ZM12 4C13.654 4 15 5.346 15 7V10H9V7C9 5.346 10.346 4 12 4ZM18 12V20H6V12H18ZM12 13C11.448 13 11 13.448 11 14V18C11 18.552 11.448 19 12 19C12.552 19 13 18.552 13 18V14C13 13.448 12.552 13 12 13Z" />
            </svg>
            @elseif ($isCompleted)
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
            </svg>
            @else
            <!-- Available / Lesson type icon -->
            @if ($lesson['type'] === 'listening')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M12 3C10.34 3 9 4.37 9 6V12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12V6C15 4.37 13.66 3 12 3ZM19 12C19 15.53 16.39 18.44 13 18.92V21H11V18.92C7.61 18.44 5 15.53 5 12H7C7 14.76 9.24 17 12 17C14.76 17 17 14.76 17 12H19Z" />
            </svg>
            @elseif ($lesson['type'] === 'speaking')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M12 14C13.66 14 15 12.66 15 11V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 12.66 10.34 14 12 14ZM17 11C17 13.76 14.76 16 12 16C9.24 16 7 13.76 7 11H5C5 14.53 7.61 17.43 11 17.92V21H13V17.92C16.39 17.43 19 14.53 19 11H17Z" />
            </svg>
            @elseif ($lesson['type'] === 'quiz')
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M11 18H13V16H11V18ZM12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM12 6C9.24 6 7 8.24 7 11H9C9 9.34 10.34 8 12 8C13.66 8 15 9.34 15 11C15 12.66 13.66 14 12 14V16H16V14C16 11.24 14.21 6 12 6Z" />
            </svg>
            @else
            <!-- Default: reading/book icon -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="text-white fill-current">
                <path
                    d="M18 2H6C4.89 2 4 2.9 4 4V20C4 21.1 4.89 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.11 2 18 2ZM6 4H11V12L8.5 10.5L6 12V4Z" />
            </svg>
            @endif
            @endif

            <!-- XP Reward Badge (when completed) -->
            @if ($isCompleted)
            <span
                class="absolute -top-1 -right-1 bg-amber-500 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 shadow-sm">
                +{{ $lesson['xp_reward'] }}
            </span>
            @endif

            <!-- Available indicator ring -->
            @if ($isAvailable)
            <span class="absolute inset-0 rounded-full border-2 border-brand-400/60 animate-ping"></span>
            @endif

            <!-- Tooltip -->
            <div
                class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:block z-20 pointer-events-none">
                <div
                    class="bg-gray-900 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                    <span class="font-bold">{{ $lesson['title'] }}</span>
                    @if ($isCompleted)
                    <span class="ml-1.5 opacity-75">— Best: {{ $lesson['best_score'] }}%</span>
                    @elseif ($isAvailable)
                    <span class="ml-1.5 opacity-75">— +{{ $lesson['xp_reward'] }} XP</span>
                    @elseif ($isLocked)
                    <span class="ml-1.5 opacity-75">— {{ $lives <= 0 ? 'Nyawa habis, tunggu pemulihan'
                            : 'Selesaikan lesson sebelumnya' }}</span>
                            @endif
                            <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-px">
                                <div class="w-2 h-2 bg-gray-900 rotate-45 transform"></div>
                            </div>
                </div>
            </div>
        </button>
    </div>

    <!-- Path connector -->
    @if (!$isLast)
    <div class="h-10 w-0.5 {{ $isCompleted ? 'bg-brand-500' : 'bg-gray-200' }}"></div>
    @endif
    @endforeach
</div>
@endforeach
@endsection