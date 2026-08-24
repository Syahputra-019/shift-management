<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ShiftManager') }} — Masuk</title>
    <meta name="description" content="Masuk ke aplikasi Shift Manager untuk mengelola jadwal kerja tim Anda.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes float-1 {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes float-2 {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(-3deg);
            }
        }

        @keyframes float-3 {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-25px) rotate(8deg);
            }
        }

        .float-1 {
            animation: float-1 6s ease-in-out infinite;
        }

        .float-2 {
            animation: float-2 8s ease-in-out infinite;
        }

        .float-3 {
            animation: float-3 7s ease-in-out infinite;
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fade-up 0.5s ease-out forwards;
        }

        .fade-up-delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .fade-up-delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .fade-up-delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }
    </style>
</head>

<body class="bg-slate-900 font-sans text-slate-800 antialiased" x-data="{ showPass: false }">

    <div class="relative flex min-h-screen overflow-hidden">

        <!-- Left Panel — Branding -->
        <div
            class="bg-linear-to-br relative hidden w-1/2 flex-col justify-between overflow-hidden from-blue-700 via-indigo-700 to-slate-900 p-12 lg:flex">

            <!-- Decorative blobs -->
            <div class="pointer-events-none absolute inset-0">
                <div class="float-1 absolute -left-16 -top-16 h-64 w-64 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="float-2 absolute -bottom-20 right-10 h-80 w-80 rounded-full bg-indigo-500/25 blur-3xl">
                </div>
                <div class="float-3 absolute right-1/4 top-1/3 h-48 w-48 rounded-full bg-sky-400/15 blur-2xl"></div>
            </div>

            <!-- Floating shift cards -->
            <div class="pointer-events-none absolute inset-0">
                <!-- 1. Shift Pagi (06:00 — 14:00) -->
                <div class="float-1 absolute right-12 top-36 rotate-6 rounded-2xl border border-white/10 bg-white/5 p-4 shadow-xl backdrop-blur-sm">
                    <div class="flex items-center space-x-2">
                        <span class="h-2 w-2 rounded-full bg-sky-400 shadow-[0_0_8px_rgba(56,189,248,0.6)]"></span>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-300">Shift Pagi</p>
                    </div>
                    <p class="mt-2 text-xl font-bold text-white">06:00 — 14:00</p>
                    <p class="mt-1 text-xs text-blue-200/80">3 karyawan aktif</p>
                </div>

                <!-- 2. Shift Sore (14:00 — 22:00) -->
                <div class="float-2 absolute right-8 top-[38%] rotate-2 rounded-2xl border border-white/10 bg-white/5 p-4 shadow-xl backdrop-blur-sm">
                    <div class="flex items-center space-x-2">
                        <span class="h-2 w-2 rounded-full bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.6)]"></span>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-indigo-300">Shift Sore</p>
                    </div>
                    <p class="mt-2 text-xl font-bold text-white">14:00 — 22:00</p>
                    <p class="mt-1 text-xs text-blue-200/80">4 karyawan aktif</p>
                </div>

                <!-- 3. Shift Malam (22:00 — 06:00) -->
                <div class="float-3 absolute bottom-[20%] right-12 rotate-[4deg] rounded-2xl border border-white/10 bg-white/5 p-4 shadow-xl backdrop-blur-sm">
                    <div class="flex items-center space-x-2">
                        <span class="h-2 w-2 rounded-full bg-violet-400 shadow-[0_0_8px_rgba(167,139,250,0.6)]"></span>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-violet-300">Shift Malam</p>
                    </div>
                    <p class="mt-2 text-xl font-bold text-white">22:00 — 06:00</p>
                    <p class="mt-1 text-xs text-blue-200/80">2 karyawan aktif</p>
                </div>
            </div>

            <!-- Logo & Tagline -->
            <div class="relative z-10">
                <div class="flex items-center space-x-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-xl font-extrabold text-blue-700 shadow-lg">
                        S</div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">Shift<span
                            class="text-blue-300">Manager</span></span>
                </div>
            </div>

            <!-- Main text -->
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold leading-tight text-white lg:text-5xl">
                    Jadwal Shift<br>yang Lebih<br><span class="text-blue-300">Terorganisir</span>
                </h1>
                <p class="mt-4 max-w-sm text-base leading-relaxed text-blue-200/90">
                    Pantau, kelola, dan optimalkan jadwal kerja seluruh tim Anda dalam satu platform yang intuitif dan
                    modern.
                </p>

                <!-- Feature list -->
                <div class="mt-8 space-y-3">
                    @foreach ([['Jadwal mingguan interaktif secara real-time', '📅'], ['Permintaan tukar shift antar karyawan', '🔄'], ['Laporan shift bulanan terperinci', '📊']] as [$feat, $icon])
                        <div class="flex items-center space-x-3">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-sm">{{ $icon }}</span>
                            <span class="text-sm font-medium text-blue-100">{{ $feat }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom info -->
            <div class="relative z-10">
                <p class="text-xs text-blue-300/70">&copy; {{ date('Y') }} ShiftManager. Kelola tim dengan lebih
                    cerdas.</p>
            </div>
        </div>

        <!-- Right Panel — Login Form -->
        <div class="flex flex-1 flex-col items-center justify-center bg-white px-6 py-12 lg:px-16">

            <!-- Mobile logo -->
            <div class="mb-8 flex items-center space-x-2 lg:hidden">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-lg font-extrabold text-white">
                    S</div>
                <span class="text-xl font-extrabold text-slate-900">Shift<span
                        class="text-blue-600">Manager</span></span>
            </div>

            <div class="w-full max-w-sm">

                <!-- Heading -->
                <div class="fade-up mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900">Selamat Datang 👋</h2>
                    <p class="mt-1.5 text-sm text-slate-500">Masukkan kredensial Anda untuk mengakses dashboard.</p>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <div class="fade-up fade-up-delay-1 mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-rose-700">Email atau password salah.</p>
                                @foreach ($errors->all() as $error)
                                    <p class="mt-0.5 text-xs text-rose-600">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div class="fade-up fade-up-delay-1">
                        <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat
                            Email</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-3 h-5 w-5 text-slate-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus
                                class="@error('email') @enderror w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 placeholder-slate-400 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                                placeholder="nama@perusahaan.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="fade-up fade-up-delay-2">
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Kata
                            Sandi</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-3 h-5 w-5 text-slate-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-12 text-sm text-slate-900 placeholder-slate-400 transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-3.5 top-3 text-slate-400 transition-colors hover:text-slate-600 focus:outline-none">
                                <svg x-show="!showPass" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="fade-up fade-up-delay-2 flex items-center justify-between">
                        <label class="flex cursor-pointer items-center space-x-2">
                            <input type="checkbox" name="remember" id="remember"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-600">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-blue-600 transition-colors hover:text-blue-700">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="fade-up fade-up-delay-3">
                        <button type="submit"
                            class="group relative w-full overflow-hidden rounded-xl bg-blue-600 py-3 text-sm font-bold text-white shadow-md shadow-blue-500/30 transition-all hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-[0.98]">
                            <span class="relative flex items-center justify-center space-x-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Masuk ke Dashboard</span>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-slate-400">
                    &copy; {{ date('Y') }} ShiftManager &bull; Sistem Manajemen Shift Karyawan
                </p>
            </div>
        </div>
    </div>

</body>

</html>
