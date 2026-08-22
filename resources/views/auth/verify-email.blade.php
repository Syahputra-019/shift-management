<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ShiftManager') }} — Verifikasi Email</title>
    <meta name="description" content="Verifikasikan alamat email Anda untuk mengakses akun Shift Manager Anda.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        @keyframes float-1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes float-2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-3deg); }
        }
        @keyframes float-3 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(8deg); }
        }
        .float-1 { animation: float-1 6s ease-in-out infinite; }
        .float-2 { animation: float-2 8s ease-in-out infinite; }
        .float-3 { animation: float-3 7s ease-in-out infinite; }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fade-up 0.5s ease-out forwards; }
        .fade-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
    </style>
</head>

<body class="font-sans antialiased bg-slate-900 text-slate-800">

    <div class="relative flex min-h-screen overflow-hidden">

        <!-- Left Panel — Branding (Consistent with Login) -->
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-slate-900 p-12 lg:flex">

            <!-- Decorative blobs -->
            <div class="pointer-events-none absolute inset-0">
                <div class="float-1 absolute -left-16 -top-16 h-64 w-64 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="float-2 absolute -bottom-20 right-10 h-80 w-80 rounded-full bg-indigo-500/25 blur-3xl"></div>
                <div class="float-3 absolute right-1/4 top-1/3 h-48 w-48 rounded-full bg-sky-400/15 blur-2xl"></div>
            </div>

            <!-- Floating cards -->
            <div class="pointer-events-none absolute inset-0">
                <div class="float-1 absolute left-12 top-1/4 rotate-[-6deg] rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm shadow-xl">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-sky-300">Verifikasi Akun</p>
                    <p class="mt-1 text-xl font-bold text-white">Langkah Terakhir</p>
                    <p class="mt-0.5 text-xs text-blue-200">Periksa kotak masuk email Anda</p>
                </div>
            </div>

            <!-- Logo & Tagline -->
            <div class="relative z-10">
                <div class="flex items-center space-x-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-700 text-xl font-extrabold shadow-lg">S</div>
                    <span class="text-2xl font-extrabold tracking-tight text-white">Shift<span class="text-blue-300">Manager</span></span>
                </div>
            </div>

            <!-- Main text -->
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold leading-tight text-white lg:text-5xl">
                    Satu Langkah<br>Lagi Menuju<br><span class="text-blue-300">Dashboard</span>
                </h1>
                <p class="mt-4 max-w-sm text-base text-blue-200/90 leading-relaxed">
                    Kami telah mengirimkan tautan verifikasi ke email Anda. Silakan verifikasi untuk mengaktifkan akses penuh.
                </p>
            </div>

            <!-- Bottom info -->
            <div class="relative z-10">
                <p class="text-xs text-blue-300/70">&copy; {{ date('Y') }} ShiftManager. Kelola tim dengan lebih cerdas.</p>
            </div>
        </div>

        <!-- Right Panel — Verification Action -->
        <div class="flex flex-1 flex-col items-center justify-center bg-white px-6 py-12 lg:px-16">

            <!-- Mobile logo -->
            <div class="mb-8 flex items-center space-x-2 lg:hidden">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white font-extrabold text-lg">S</div>
                <span class="text-xl font-extrabold text-slate-900">Shift<span class="text-blue-600">Manager</span></span>
            </div>

            <div class="w-full max-w-sm">

                <!-- Icon/Illustration and Heading -->
                <div class="fade-up mb-8 text-center lg:text-left">
                    <div class="mx-auto lg:mx-0 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 mb-5 shadow-sm">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4.8a2 2 0 012.22 0l8 4.8A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.5 0l-2.25 1.5"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-slate-900">Verifikasi Email Anda</h2>
                    <p class="mt-2.5 text-sm text-slate-500 leading-relaxed">
                        Terima kasih telah bergabung! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan ulang.
                    </p>
                </div>

                <!-- Status alert for resent email -->
                @if (session('status') == 'verification-link-sent')
                    <div class="fade-up fade-up-delay-1 mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <div class="flex items-start space-x-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-emerald-800">Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.</p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="space-y-4 fade-up fade-up-delay-2">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="group relative w-full overflow-hidden rounded-xl bg-blue-600 py-3 text-sm font-bold text-white shadow-md shadow-blue-500/30 transition-all hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-[0.98]">
                            <span>Kirim Ulang Email Verifikasi</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit"
                            class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors focus:outline-none">
                            Log Out
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-slate-400">
                    &copy; {{ date('Y') }} ShiftManager &bull; Sistem Manajemen Shift Karyawan
                </p>
            </div>
        </div>
    </div>

</body>

</html>
