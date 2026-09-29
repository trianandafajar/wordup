@extends('layouts.guest')

@section('content')
<div class="relative flex min-h-dvh overflow-x-hidden bg-white lg:bg-brand-50">

    <div class="hidden lg:flex lg:w-[60%] bg-brand-800 flex-col justify-center items-center p-12 text-white">
        <div class="space-y-3 text-center">
            <div class="flex justify-center">
                <img src="{{ asset('images/auth-image.png') }}" alt="Auth Image"
                    class="w-100 h-100 object-contain" />
            </div>
            <h1 class="font-dynapuff text-4xl leading-tight text-white">
                Lanjutkan streak-mu, jangan sampai putus.
            </h1>
        </div>
    </div>

    <main
        class="relative flex min-h-dvh flex-1 items-center justify-center px-5 py-8 sm:px-10 sm:py-12 lg:min-h-screen lg:py-16">
        <div class="w-full max-w-sm animate-[fadeUp_0.4s_ease-out] lg:max-w-[25rem]">

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
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <span class="font-semibold ">{{ $error }}</span>
                            @endforeach
                    </div>
                @endif

                <form class="space-y-4" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-dark">Email</label>
                        <input id="email" name="email" type="email" required autofocus
                            class="block h-12 w-full rounded-xl border border-[#dce7df] bg-[#fbfefc] px-4 text-gray-dark shadow-sm outline-none transition placeholder:text-gray-dark/30 focus:border-brand-500 sm:text-sm"
                            placeholder="nama@email.com" value="{{ old('email') }}">
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
                                class="block h-12 w-full rounded-xl border border-[#dce7df] bg-[#fbfefc] px-4 pr-12 text-gray-dark shadow-sm outline-none transition placeholder:text-gray-dark/30 focus:border-brand-500 sm:text-sm"
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
                    </div>

                    <div class="flex items-center pt-1">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500 cursor-pointer">
                        <label for="remember" class="ml-2 text-sm text-gray-dark/70">Ingat saya</label>
                    </div>

                    <button type="submit"
                        class="mt-2 h-12 w-full rounded-xl bg-brand-500 px-4 text-sm font-bold text-white transition hover:bg-brand-600 focus:outline-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        Masuk
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#dce7df]"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white px-2 text-gray-dark/45 lg:bg-brand-50 lg:px-2">Atau</span>
                    </div>
                </div>

                <a href="{{ route('login.google') }}"
                    class="flex h-12 w-full items-center justify-center gap-3 rounded-xl border border-[#dce7df] bg-white px-4 text-sm font-semibold text-gray-dark shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-brand-500/15 cursor-pointer">
                    <svg class="h-5 w-5" viewBox="0 0 24 24">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    Masuk dengan Google
                </a>

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