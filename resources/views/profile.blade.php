<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shift-Management') }} - Profil Saya</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{
    sidebarOpen: true,
    avatarColor: '{{ $user->avatar_color ?? 'bg-blue-600' }}'
}">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 flex-shrink-0 border-r border-slate-200 bg-white shadow-sm transition-all duration-300"
            :class="{ '-ml-64': !sidebarOpen }">
            <div class="flex h-16 items-center justify-between border-b border-slate-100 px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-lg font-bold text-white shadow-md shadow-blue-500/20">
                        S
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">Shift<span
                            class="text-blue-600">Manager</span></span>
                </a>
            </div>
            <nav class="mt-6 space-y-1.5 px-3">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('schedule.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Jadwal Shift</span>
                </a>
                <a href="{{ route('swap-shift.index') }}"
                    class="flex items-center justify-between rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>Tukar Shift</span>
                    </div>
                    @if (isset($pendingSwapCount) && $pendingSwapCount > 0)
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                            {{ $pendingSwapCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('employees.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A6.995 6.995 0 0012 12.75a6.995 6.995 0 00-3-5.197M15 21a9 9 0 00-9-9">
                        </path>
                    </svg>
                    <span>Karyawan</span>
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>Laporan</span>
                </a>
            </nav>
        </aside>

        <!-- Main content area -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top Header Bar -->
            <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">Profil Saya</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- User Profile Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center space-x-3 focus:outline-none">
                            <div :class="avatarColor"
                                class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm transition-all duration-300">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="hidden text-left md:block">
                                <p class="text-sm font-semibold leading-none text-slate-800">{{ $user->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $user->role ?? 'Staff' }}</p>
                            </div>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                            class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-slate-100 bg-white py-1 shadow-lg ring-1 ring-black/5">
                            <div class="border-b border-slate-100 px-4 py-2">
                                <p class="text-xs font-semibold uppercase text-slate-400">Signed in as</p>
                                <p class="truncate text-sm font-medium text-slate-800">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full px-4 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Container -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="mb-6 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Validation Error Alerts -->
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm">
                        <div class="flex items-start space-x-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-bold">Terjadi kesalahan input:</h3>
                                <ul class="mt-1 list-inside list-disc text-xs space-y-0.5 font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Profile Card & Stats Header -->
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col items-center space-y-4 md:flex-row md:items-center md:space-x-6 md:space-y-0">
                            <!-- Large Circle Avatar with Alpine preview -->
                            <div :class="avatarColor"
                                class="flex h-24 w-24 flex-shrink-0 items-center justify-center rounded-3xl text-3xl font-extrabold text-white shadow-lg shadow-slate-200/50 transition-all duration-300 transform hover:scale-105">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            
                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-wrap items-center justify-center gap-2 md:justify-start">
                                    <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                                        {{ $user->role ?? 'Staff' }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Departemen: <span class="font-semibold text-slate-600">{{ $user->department ?? 'Operational' }}</span></p>
                            </div>

                            <!-- Quick Stats Grid -->
                            <div class="grid w-full grid-cols-3 gap-4 border-t border-slate-100 pt-4 md:w-auto md:border-t-0 md:pt-0 md:pl-6 md:border-l">
                                <div class="text-center px-2">
                                    <span class="block text-2xl font-bold text-slate-800">{{ $totalShifts }}</span>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Shift</span>
                                </div>
                                <div class="text-center px-2">
                                    <span class="block text-2xl font-bold text-slate-800">{{ $totalSwapSent }}</span>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Tukar Sent</span>
                                </div>
                                <div class="text-center px-2">
                                    <span class="block text-2xl font-bold text-slate-800">{{ $totalSwapReceived }}</span>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Tukar Recv</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Forms Grid -->
                    <div class="grid gap-6 lg:grid-cols-2">
                        <!-- Edit Info Form -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-900 mb-5 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Profil
                            </h3>

                            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Warna Avatar</label>
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach(['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-purple-600', 'bg-pink-600', 'bg-amber-600', 'bg-cyan-600', 'bg-teal-600', 'bg-rose-600'] as $color)
                                            <label class="relative cursor-pointer">
                                                <input type="radio" name="avatar_color" value="{{ $color }}" class="sr-only" x-model="avatarColor">
                                                <span class="block h-8 w-8 rounded-full border border-slate-200/50 shadow-sm transition-transform duration-150 hover:scale-110 {{ $color }}"
                                                      :class="avatarColor === '{{ $color }}' ? 'ring-2 ring-offset-2 ring-blue-500 scale-110' : 'opacity-80 hover:opacity-100'">
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Read-only Administrative Information -->
                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1 flex items-center">
                                            Departemen
                                            <svg class="h-3 w-3 ml-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </label>
                                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-sm text-slate-500 font-medium">
                                            {{ $user->department ?? 'Operational' }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-400 mb-1 flex items-center">
                                            Peran Kerja
                                            <svg class="h-3 w-3 ml-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </label>
                                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-sm text-slate-500 font-medium">
                                            {{ $user->role ?? 'Staff' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-colors">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Form -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-900 mb-5 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Keamanan Akun
                            </h3>

                            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                                    <input type="password" name="current_password" required
                                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi Baru</label>
                                    <input type="password" name="password" required
                                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>

                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-100 transition-colors">
                                        Perbarui Kata Sandi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
