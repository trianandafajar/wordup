@extends('layouts.guest')

@section('content')
<div class="relative flex min-h-dvh overflow-x-hidden bg-white lg:bg-brand-50">

    <div class="hidden lg:flex lg:w-[42%] bg-brand-800 flex-col justify-between p-12 text-white">
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

    <main
        class="relative flex min-h-dvh flex-1 items-center justify-center px-5 py-8 sm:px-10 sm:py-12 lg:min-h-screen lg:py-16">
        <div class="w-full max-w-sm animate-[fadeUp_0.4s_ease-out] lg:max-w-[26rem]">

            <div class="mb-9 text-center lg:hidden">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-brand-50">
                    <img src="{{ asset('images/logo.png') }}" alt="WordUp"
                        class="h-[4.5rem] w-[4.5rem] object-contain" />
                </div>
                <p class="mt-4 text-xl font-bold tracking-tight text-gray-dark">WordUp</p>
                <p class="mt-1 text-sm text-gray-dark/55">Belajar sedikit, tiap hari.</p>
            </div>

            <div class="lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none">
                <div class="mb-6 space-y-1.5">
                    <h2 class="font-sans text-[1.7rem] font-bold tracking-tight text-gray-dark">Masuk</h2>
                    <p class="text-sm leading-6 text-gray-dark/55">Senang melihatmu lagi. Lanjutkan belajarmu.</p>
                </div>

                <form class="space-y-4" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-dark">Email</label>
                        <input id="email" name="email" type="email" required autofocus
                            class="block h-12 w-full rounded-xl border border-[#dce7df] bg-[#fbfefc] px-4 text-gray-dark shadow-sm outline-none transition placeholder:text-gray-dark/30 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 sm:text-sm"
                            placeholder="nama@email.com" value="{{ old('email') }}">
                        @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ showPassword: false }">
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-gray-dark">Kata sandi</label>
                            <a href="{{ route('password.request') }}"
                                class="text-xs font-semibold text-brand-600 transition hover:text-brand-700">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                                class="block h-12 w-full rounded-xl border border-[#dce7df] bg-[#fbfefc] px-4 pr-12 text-gray-dark shadow-sm outline-none transition placeholder:text-gray-dark/30 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 sm:text-sm"
                                placeholder="Kata sandi kamu">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center pt-1">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                        <label for="remember" class="ml-2 text-sm text-gray-dark/70">Ingat saya</label>
                    </div>

                    <button type="submit"
                        class="mt-2 h-12 w-full rounded-xl bg-brand-500 px-4 text-sm font-bold text-white shadow-[0_10px_20px_-12px_rgba(76,175,80,0.9)] transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/25 focus:ring-offset-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        Masuk
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-gray-dark/55">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                        class="font-semibold text-brand-600 transition hover:text-brand-700">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        </div>
    </main>
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