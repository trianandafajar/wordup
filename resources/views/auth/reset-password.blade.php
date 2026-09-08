@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex bg-[#ecfdf3]">

    <div class="hidden lg:flex lg:w-5/12 bg-[#2e7d32] flex-col justify-between p-12 text-white">
        <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="h-12 w-12 object-contain" />

        <div class="space-y-6">
            <h1 class="font-sans text-4xl leading-tight text-white">
                Buat kata sandi baru yang aman.
            </h1>
            <p class="text-white/70 text-base max-w-xs">
                Gunakan kombinasi yang mudah kamu ingat, tapi sulit ditebak.
            </p>
        </div>

        <p class="text-white/40 text-xs">&copy; {{ date('Y') }} WordUp</p>
    </div>

    <div class="flex-1 flex items-center justify-center px-6 py-12 sm:px-10">
        <div class="w-full max-w-sm space-y-8 animate-[fadeUp_0.4s_ease-out]">

            <div class="space-y-2 lg:hidden">
                <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="h-10 w-10 object-contain" />
            </div>

            <div>
                <h2 class="font-sans text-2xl font-bold text-[#1a2231]">Reset kata sandi</h2>
                <p class="mt-1 text-sm text-[#1a2231]/60">
                    Masukkan kata sandi baru untuk akun kamu.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="password" class="block text-sm font-medium text-[#1a2231] mb-1.5">Kata sandi
                        baru</label>
                    <input id="password" name="password" type="password" required autofocus
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-[#1a2231] placeholder-[#1a2231]/30 focus:outline-none focus:ring-2 focus:ring-[#4caf50] focus:border-[#4caf50] sm:text-sm"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-[#1a2231] mb-1.5">Konfirmasi kata sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-[#1a2231] placeholder-[#1a2231]/30 focus:outline-none focus:ring-2 focus:ring-[#4caf50] focus:border-[#4caf50] sm:text-sm"
                        placeholder="Ulangi kata sandi baru">
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-[#4caf50] py-2.5 px-4 text-sm font-medium text-white hover:bg-[#43a047] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4caf50] transition-colors">
                    Simpan Kata Sandi
                </button>
            </form>
        </div>
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