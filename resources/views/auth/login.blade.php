@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex bg-[#ecfdf3]">

    {{-- Panel Kiri: konteks produk, disembunyikan di mobile --}}
    <div class="hidden lg:flex lg:w-5/12 bg-[#2e7d32] flex-col justify-between p-12 text-white">
        <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="h-12 w-12 object-contain" />

        <div class="space-y-6">
            <h1 class="font-sans text-4xl leading-tight text-white">
                Lanjutkan streak-mu, jangan sampai putus.
            </h1>
            <p class="text-white/70 text-base max-w-xs">
                Beberapa menit hari ini cukup untuk tetap di jalur.
            </p>
        </div>

        <p class="text-white/40 text-xs">&copy; {{ date('Y') }} WordUp</p>
    </div>

    {{-- Panel Kanan: form --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 sm:px-10">
        <div class="w-full max-w-sm space-y-8 animate-[fadeUp_0.4s_ease-out]">

            <div class="space-y-2 lg:hidden">
                <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="h-10 w-10 object-contain" />
            </div>

            <div>
                <h2 class="font-sans text-2xl font-bold text-[#1a2231]">Masuk</h2>
                <p class="mt-1 text-sm text-[#1a2231]/60">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-[#4caf50] hover:text-[#43a047]">
                        Daftar sekarang
                    </a>
                </p>
            </div>

            <form class="space-y-5" method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-[#1a2231] mb-1.5">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-[#1a2231] placeholder-[#1a2231]/30 focus:outline-none focus:ring-2 focus:ring-[#4caf50] focus:border-[#4caf50] sm:text-sm"
                        placeholder="nama@email.com" value="{{ old('email') }}">
                    @error('email')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-[#1a2231]">Kata sandi</label>
                        <a href="#" class="text-sm font-medium text-[#4caf50] hover:text-[#43a047]">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <input id="password" name="password" type="password" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-[#1a2231] placeholder-[#1a2231]/30 focus:outline-none focus:ring-2 focus:ring-[#4caf50] focus:border-[#4caf50] sm:text-sm"
                        placeholder="Kata sandi kamu">
                    @error('password')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-[#4caf50] focus:ring-[#4caf50]">
                    <label for="remember" class="ml-2 text-sm text-[#1a2231]">Ingat saya</label>
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-[#4caf50] py-2.5 px-4 text-sm font-medium text-white hover:bg-[#43a047] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4caf50] transition-colors">
                    Masuk
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
