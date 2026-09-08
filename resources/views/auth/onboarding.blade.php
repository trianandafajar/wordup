@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#ecfdf3] py-12 px-4 sm:px-6">
    <div class="max-w-md w-full space-y-8 animate-[fadeUp_0.4s_ease-out]">

        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="mx-auto h-12 w-12 object-contain" />
            <h1 class="font-sans text-2xl font-bold text-[#1a2231]">Sesuaikan cara belajarmu</h1>
            <p class="text-sm text-[#1a2231]/60">Dua pertanyaan singkat, lalu kamu langsung mulai</p>
        </div>

        <div class="flex items-center justify-center gap-2 rounded-lg bg-[#4caf50]/10 px-4 py-3 text-sm text-[#1a2231]">
            <span>🇬🇧</span>
            <span>Kamu akan belajar <span class="font-medium">Bahasa Inggris</span></span>
        </div>

        <form method="POST" action="{{ route('user.onboarding') }}" class="space-y-7">
            @csrf
            <input type="hidden" name="target_language" value="English">

            <fieldset>
                <legend class="block text-sm font-medium text-[#1a2231] mb-3">Tujuan belajar</legend>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($learningGoals as $goal)
                    <label class="cursor-pointer">
                        <input type="radio" name="learning_goal" value="{{ $goal->value }}" class="peer sr-only"
                            required {{ old('learning_goal')===$goal->value ? 'checked' : '' }}>
                        <div
                            class="rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-[#1a2231] transition-colors peer-checked:border-[#4caf50] peer-checked:bg-[#4caf50]/10 peer-checked:text-[#4caf50] peer-focus-visible:ring-2 peer-focus-visible:ring-[#4caf50] peer-focus-visible:ring-offset-2">
                            {{ ucfirst($goal->value) }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('learning_goal')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </fieldset>

            <fieldset>
                <legend class="block text-sm font-medium text-[#1a2231] mb-3">Level kemampuan</legend>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($proficiencyLevels as $level)
                    <label class="cursor-pointer">
                        <input type="radio" name="proficiency_level" value="{{ $level->value }}" class="peer sr-only"
                            required {{ old('proficiency_level')===$level->value ? 'checked' : '' }}>
                        <div
                            class="rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-[#1a2231] transition-colors peer-checked:border-[#4caf50] peer-checked:bg-[#4caf50]/10 peer-checked:text-[#4caf50] peer-focus-visible:ring-2 peer-focus-visible:ring-[#4caf50] peer-focus-visible:ring-offset-2">
                            {{ ucfirst($level->value) }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('proficiency_level')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </fieldset>

            <button type="submit"
                class="w-full rounded-lg bg-[#4caf50] py-2.5 px-4 text-sm font-medium text-white hover:bg-[#43a047] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4caf50] transition-colors">
                Mulai belajar
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .animate-\[fadeUp_0\.4s_ease-out\] {
            animation: none;
        }
    }
</style>
@endsection
