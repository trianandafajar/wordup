@extends('layouts.user', ['pageTitle' => 'Belajar', 'activeMenu' => 'learn'])

@section('content')
    <!-- ====== UNIT 1: Greetings ====== -->
    <div class="w-full max-w-lg">
        <div class="rounded-2xl bg-brand-500 text-white p-4 shadow-md flex items-center justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider opacity-80">Unit 1</span>
                <h3 class="text-lg font-bold leading-snug">Greetings & Introductions</h3>
                <p class="text-xs opacity-90 mt-0.5">Sapaan dan perkenalan diri</p>
            </div>
            <div class="shrink-0 bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="fill-current">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Path Zigzag Lessons -->
    <div class="flex flex-col items-center gap-0 w-full" style="padding-bottom: 2rem;">

        <!-- Lesson 1: Completed -->
        <div class="self-center ml-0 sm:ml-0 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 1,
                'title' => 'Hello & Goodbye',
                'status' => 'completed',
                'xp_reward' => 15,
                'best_score' => 100,
                'type' => 'reading',
                'has_crown' => true,
            ])
        </div>
        <div class="h-10 path-line-done w-0.5"></div>

        <!-- Lesson 2: Completed -->
        <div class="self-start ml-8 sm:ml-16 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 2,
                'title' => 'How Are You?',
                'status' => 'completed',
                'xp_reward' => 15,
                'best_score' => 85,
                'type' => 'listening',
            ])
        </div>
        <div class="h-10 path-line-done w-0.5"></div>

        <!-- Lesson 3: Completed -->
        <div class="self-center ml-0 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 3,
                'title' => 'Nice to Meet You',
                'status' => 'completed',
                'xp_reward' => 15,
                'best_score' => 90,
                'type' => 'speaking',
            ])
        </div>
        <div class="h-10 path-line-done w-0.5"></div>

        <!-- Lesson 4: CURRENT / Available -->
        <div class="self-end mr-8 sm:mr-16 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 4,
                'title' => 'Self Introduction',
                'status' => 'available',
                'xp_reward' => 20,
                'type' => 'speaking',
            ])
        </div>
        <div class="h-10 path-line w-0.5"></div>

        <!-- Lesson 5: Locked -->
        <div class="self-center ml-0 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 5,
                'title' => 'Asking Names',
                'status' => 'locked',
                'xp_reward' => 20,
                'type' => 'listening',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 6: Locked -->
        <div class="self-start ml-8 sm:ml-16 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 6,
                'title' => 'Daily Greetings',
                'status' => 'locked',
                'xp_reward' => 20,
                'type' => 'reading',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 7: Locked (Boss/Review) -->
        <div class="self-center ml-0 mb-0 relative">
            <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20">
                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap">REVIEW</span>
            </div>
            @include('livewire.user.partials.lesson-node', [
                'id' => 7,
                'title' => 'Unit 1 Review',
                'status' => 'locked',
                'xp_reward' => 40,
                'type' => 'quiz',
                'has_crown' => false,
            ])
        </div>
    </div>

    <!-- ====== UNIT 2: Basics ====== -->
    <div class="w-full max-w-lg mt-4">
        <div class="rounded-2xl bg-amber-500 text-white p-4 shadow-md flex items-center justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider opacity-80">Unit 2</span>
                <h3 class="text-lg font-bold leading-snug">Basic Vocabulary</h3>
                <p class="text-xs opacity-90 mt-0.5">Kosakata dasar sehari-hari</p>
            </div>
            <div class="shrink-0 bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="fill-current">
                    <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Path Zigzag Unit 2 -->
    <div class="flex flex-col items-center gap-0 w-full" style="padding-bottom: 2rem;">

        <!-- Lesson 8: Locked -->
        <div class="self-center ml-0 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 8,
                'title' => 'Numbers 1-20',
                'status' => 'locked',
                'xp_reward' => 20,
                'type' => 'reading',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 9: Locked -->
        <div class="self-end mr-8 sm:mr-16 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 9,
                'title' => 'Colors',
                'status' => 'locked',
                'xp_reward' => 20,
                'type' => 'listening',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 10: Locked -->
        <div class="self-center ml-0 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 10,
                'title' => 'Family Members',
                'status' => 'locked',
                'xp_reward' => 20,
                'type' => 'speaking',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 11: Locked -->
        <div class="self-start ml-8 sm:ml-16 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 11,
                'title' => 'Food & Drinks',
                'status' => 'locked',
                'xp_reward' => 25,
                'type' => 'reading',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 12: Locked -->
        <div class="self-center ml-0 mb-0">
            @include('livewire.user.partials.lesson-node', [
                'id' => 12,
                'title' => 'Body Parts',
                'status' => 'locked',
                'xp_reward' => 25,
                'type' => 'listening',
            ])
        </div>
        <div class="h-10 w-0.5 bg-gray-200"></div>

        <!-- Lesson 13: Locked (Boss) -->
        <div class="self-end mr-8 sm:mr-16 mb-0 relative">
            <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20">
                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap">REVIEW</span>
            </div>
            @include('livewire.user.partials.lesson-node', [
                'id' => 13,
                'title' => 'Unit 2 Review',
                'status' => 'locked',
                'xp_reward' => 50,
                'type' => 'quiz',
            ])
        </div>
    </div>

    <!-- ====== UNIT 3: Present Tense (Locked) ====== -->
    <div class="w-full max-w-lg mt-4">
        <div class="rounded-2xl bg-gray-300 text-gray-600 p-4 shadow-sm flex items-center justify-between gap-4 mb-8 opacity-70">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider opacity-70">Unit 3</span>
                <h3 class="text-lg font-bold leading-snug">Present Tense</h3>
                <p class="text-xs opacity-80 mt-0.5">Locked — selesaikan Unit 2 terlebih dahulu</p>
            </div>
            <div class="shrink-0 bg-black/10 p-2.5 rounded-xl">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="fill-current opacity-50">
                    <path d="M12 2C9.243 2 7 4.243 7 7V10H6C4.897 10 4 10.897 4 12V20C4 21.103 4.897 22 6 22H18C19.103 22 20 21.103 20 20V12C20 10.897 19.103 10 18 10H17V7C17 4.243 14.757 2 12 2ZM12 4C13.654 4 15 5.346 15 7V10H9V7C9 5.346 10.346 4 12 4ZM18 12V20H6V12H18ZM12 13C11.448 13 11 13.448 11 14V18C11 18.552 11.448 19 12 19C12.552 19 13 18.552 13 18V14C13 13.448 12.552 13 12 13Z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Placeholder for future units -->
    <div class="w-full max-w-lg flex flex-col items-center gap-4 py-12 text-center opacity-40">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" class="text-gray-400 fill-current">
            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z"/>
        </svg>
        <p class="text-sm text-gray-500">Selesaikan unit sebelumnya untuk membuka unit baru</p>
    </div>
@endsection