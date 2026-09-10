@extends('layouts.guest')

@section('content')
<div class="relative flex min-h-dvh overflow-x-hidden bg-white lg:bg-brand-50">

    <div class="hidden lg:flex lg:w-[42%] bg-brand-800 flex-col justify-between p-12 text-white">
        <img src="{{ asset('images/logo.png') }}" alt="WordUp" class="h-12 w-12 object-contain" />

        <div class="space-y-6">
            <h1 class="font-sans text-4xl leading-tight text-white">
                Tenang, kata sandimu akan kembali.
            </h1>
            <p class="text-white/70 text-base max-w-xs">
                Kami kirim link reset ke email kamu, tinggal ikuti langkahnya.
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
                <p class="mt-1 text-sm text-gray-dark/55">Kembali belajar dengan tenang.</p>
            </div>

            <div class="lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:shadow-none">
                <div class="mb-6 space-y-1.5">
                    <h2 class="font-sans text-[1.7rem] font-bold tracking-tight text-gray-dark">Lupa kata sandi?</h2>
                    <p class="text-sm leading-6 text-gray-dark/55">
                        Masukkan email kamu, kami kirim link untuk reset kata sandi.
                    </p>
                </div>

                @if (session('status'))
                <div class="mb-5 rounded-2xl border border-brand-200 bg-brand-50 p-4 text-sm leading-6 text-gray-dark">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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

                    <button type="submit"
                        class="mt-2 h-12 w-full rounded-xl bg-brand-500 px-4 text-sm font-bold text-white shadow-[0_10px_20px_-12px_rgba(76,175,80,0.9)] transition hover:bg-brand-600 focus:outline-none focus:ring-4 focus:ring-brand-500/25 focus:ring-offset-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        Kirim Link Reset
                    </button>

                    <p class="pt-2 text-center text-sm text-gray-dark/55">
                        Ingat kata sandi?
                        <a href="{{ route('login') }}"
                            class="font-semibold text-brand-600 transition hover:text-brand-700">
                            Masuk
                        </a>
                    </p>
                </form>
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