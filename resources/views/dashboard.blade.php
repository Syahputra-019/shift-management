<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shift-Management') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{
    sidebarOpen: true,
    activeShiftFilter: 'all',
    searchQuery: '',
    swapModalOpen: false,
    announcementModalOpen: false,
    selectedAnnouncement: null,
    clockedIn: false,
    clockInTime: null,
    liveTime: '',
    liveDate: '',
    init() {
        this.updateClock();
        setInterval(() => this.updateClock(), 1000);
    },
    updateClock() {
        const now = new Date();
        this.liveTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
        this.liveDate = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    },
    toggleClock() {
        this.clockedIn = !this.clockedIn;
        if (this.clockedIn) {
            const now = new Date();
            this.clockInTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    }
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
                    class="flex items-center rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                    <svg class="mr-3 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    @if ($pendingSwapCount > 0)
                        <span
                            class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                            {{ $pendingSwapCount }}
                        </span>
                    @endif
                </a>
                <a href="#"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A6.995 6.995 0 0012 12.75a6.995 6.995 0 00-3-5.197M15 21a9 9 0 00-9-9">
                        </path>
                    </svg>
                    <span>Karyawan</span>
                </a>
                <a href="#"
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
                        <h1 class="text-xl font-bold text-slate-900">Dashboard Shift</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Live Attendance Action -->
                    <button @click="toggleClock()"
                        :class="clockedIn ? 'bg-emerald-600 hover:bg-emerald-700 text-white' :
                            'bg-blue-600 hover:bg-blue-700 text-white'"
                        class="flex items-center space-x-2 rounded-lg px-3.5 py-2 text-sm font-semibold shadow-sm transition-all">
                        <span class="relative flex h-2.5 w-2.5">
                            <span :class="clockedIn ? 'bg-emerald-400' : 'bg-blue-400'"
                                class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75"></span>
                            <span :class="clockedIn ? 'bg-emerald-300' : 'bg-blue-300'"
                                class="relative inline-flex h-2.5 w-2.5 rounded-full"></span>
                        </span>
                        <span x-text="clockedIn ? 'Absen Keluar (' + clockInTime + ')' : 'Absen Masuk'"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center space-x-3 focus:outline-none">
                            <div
                                class="{{ Auth::user()->avatar_color ?? 'bg-blue-600' }} flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden text-left md:block">
                                <p class="text-sm font-semibold leading-none text-slate-800">{{ Auth::user()->name }}
                                </p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ Auth::user()->role ?? 'Staff' }}</p>
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
                                <p class="truncate text-sm font-medium text-slate-800">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil
                                Saya</a>
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

            <!-- Dashboard Main Container -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">
                <!-- Alerts / Flash Messages -->
                @if (session('success'))
                    <div
                        class="mb-6 flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Hero Welcome Banner with Live Time -->
                <div
                    class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-slate-900 p-6 text-white shadow-lg shadow-blue-500/10 lg:p-8">
                    <div class="relative z-10 flex flex-col justify-between md:flex-row md:items-center">
                        <div>
                            <span
                                class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-md">
                                <span x-text="liveDate"></span>
                            </span>
                            <h2 class="mt-2 text-2xl font-extrabold lg:text-3xl">Selamat Datang,
                                {{ Auth::user()->name }}! 👋</h2>
                            <p class="mt-1 max-w-xl text-sm text-blue-100">
                                Pantau jadwal shift tim, kelola permintaan tukar shift, dan konfirmasi presensi kerja
                                harian Anda dengan mudah.
                            </p>
                        </div>
                        <div
                            class="mt-6 flex items-center space-x-4 rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-md md:mt-0">
                            <div class="text-right">
                                <p class="text-xs font-medium text-blue-200">Waktu Presensi System</p>
                                <p class="font-mono text-2xl font-bold tracking-tight" x-text="liveTime">--:--:-- WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 KPI Summary Widgets -->
                <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Widget 1: Jadwal Anda Berikutnya -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal Anda</span>
                            <div class="rounded-xl bg-blue-50 p-2.5 text-blue-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            @if (isset($mySchedule) && $mySchedule?->shift)
                                <p class="text-2xl font-bold text-slate-900">
                                    {{ \Carbon\Carbon::parse($mySchedule->shift->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($mySchedule->shift->end_time)->format('H:i') }}
                                </p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700">
                                        Shift {{ $mySchedule->shift->name }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        {{ $mySchedule->date->isToday() ? 'Hari ini' : $mySchedule->date->format('d M Y') }}
                                    </span>
                                </div>
                            @else
                                <p class="text-2xl font-bold text-slate-400">-</p>
                                <p class="mt-1 text-xs text-slate-500">Tidak ada jadwal mendatang</p>
                            @endif
                        </div>
                    </div>

                    <!-- Widget 2: Permintaan Tukar Shift -->
                    <a href="{{ route('swap-shift.index') }}"
                        class="block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tukar Shift</span>
                            <div class="rounded-xl bg-amber-50 p-2.5 text-amber-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline justify-between">
                            <div>
                                <p class="text-2xl font-bold text-slate-900">{{ $pendingSwapCount }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Menunggu Persetujuan</p>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 hover:underline">Kelola &rarr;</span>
                        </div>
                    </a>

                    <!-- Widget 3: Shift Stats Hari Ini -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Shift Aktif
                                Tim</span>
                            <div class="rounded-xl bg-emerald-50 p-2.5 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-2xl font-bold text-slate-900">{{ $stats['on_shift_today'] }} <span
                                    class="text-sm font-normal text-slate-500">/ {{ $stats['total_employees'] }}
                                    Orang</span></p>
                            <div class="mt-2 flex items-center space-x-1.5 text-xs">
                                <span class="rounded bg-sky-100 px-1.5 py-0.5 font-semibold text-sky-800">Pagi:
                                    {{ $stats['pagi'] }}</span>
                                <span class="rounded bg-indigo-100 px-1.5 py-0.5 font-semibold text-indigo-800">Sore:
                                    {{ $stats['sore'] }}</span>
                                <span class="rounded bg-purple-100 px-1.5 py-0.5 font-semibold text-purple-800">Malam:
                                    {{ $stats['malam'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 4: Pengumuman Highlight -->
                    <div class="cursor-pointer rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md"
                        @click="selectedAnnouncement = {{ json_encode($announcements->first()) }}; announcementModalOpen = true">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengumuman</span>
                            <div class="rounded-xl bg-orange-50 p-2.5 text-orange-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.518">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            @if ($announcements->first())
                                <p class="line-clamp-1 text-sm font-bold text-slate-900">
                                    {{ $announcements->first()->title }}</p>
                                <p class="mt-1 flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $announcements->first()->time_schedule }}</span>
                                    <span class="font-medium text-blue-600">Baca &rarr;</span>
                                </p>
                            @else
                                <p class="text-sm text-slate-400">Tidak ada pengumuman</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Interactive Team Schedule Section -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <!-- Schedule Header & Date Selector -->
                    <div
                        class="flex flex-col space-y-4 border-b border-slate-100 p-6 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Jadwal Tim Karyawan</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Menampilkan jadwal kerja seluruh anggota tim untuk
                                tanggal yang dipilih.</p>
                        </div>

                        <!-- Date Navigation Bar -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex items-center space-x-1 rounded-xl bg-slate-100 p-1">
                                <a href="?date={{ $selectedDate->copy()->subDay()->toDateString() }}"
                                    class="rounded-lg p-1.5 text-slate-600 transition-all hover:bg-white hover:shadow-sm"
                                    title="Hari Sebelumnya">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                                <a href="?date={{ today()->toDateString() }}"
                                    class="{{ $selectedDate->isToday() ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-white' }} rounded-lg px-3 py-1 text-xs font-bold transition-all">
                                    Hari Ini
                                </a>
                                <a href="?date={{ $selectedDate->copy()->addDay()->toDateString() }}"
                                    class="rounded-lg p-1.5 text-slate-600 transition-all hover:bg-white hover:shadow-sm"
                                    title="Hari Berikutnya">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>

                            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center">
                                <input type="date" name="date" value="{{ $selectedDate->toDateString() }}"
                                    onchange="this.form.submit()"
                                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none">
                            </form>
                        </div>
                    </div>

                    <!-- Filters & Search Toolbar -->
                    <div
                        class="flex flex-col space-y-3 border-b border-slate-100 bg-slate-50/50 px-6 py-3.5 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <!-- Shift Filter Pills -->
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button @click="activeShiftFilter = 'all'"
                                :class="activeShiftFilter === 'all' ? 'bg-slate-900 text-white font-semibold' :
                                    'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'"
                                class="rounded-lg px-3 py-1 text-xs shadow-sm transition-all">
                                Semua ({{ $stats['total_employees'] }})
                            </button>
                            <button @click="activeShiftFilter = 'pagi'"
                                :class="activeShiftFilter === 'pagi' ? 'bg-sky-600 text-white font-semibold' :
                                    'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'"
                                class="rounded-lg px-3 py-1 text-xs shadow-sm transition-all">
                                Pagi ({{ $stats['pagi'] }})
                            </button>
                            <button @click="activeShiftFilter = 'sore'"
                                :class="activeShiftFilter === 'sore' ? 'bg-indigo-600 text-white font-semibold' :
                                    'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'"
                                class="rounded-lg px-3 py-1 text-xs shadow-sm transition-all">
                                Sore ({{ $stats['sore'] }})
                            </button>
                            <button @click="activeShiftFilter = 'malam'"
                                :class="activeShiftFilter === 'malam' ? 'bg-purple-600 text-white font-semibold' :
                                    'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'"
                                class="rounded-lg px-3 py-1 text-xs shadow-sm transition-all">
                                Malam ({{ $stats['malam'] }})
                            </button>
                            <button @click="activeShiftFilter = 'off'"
                                :class="activeShiftFilter === 'off' ? 'bg-rose-600 text-white font-semibold' :
                                    'bg-white text-slate-600 hover:bg-slate-200 border border-slate-200'"
                                class="rounded-lg px-3 py-1 text-xs shadow-sm transition-all">
                                Off / Libur ({{ $stats['off'] }})
                            </button>
                        </div>

                        <!-- Instant Client Search -->
                        <div class="relative w-full md:w-64">
                            <input type="text" x-model="searchQuery" placeholder="Cari nama / departemen..."
                                class="w-full rounded-xl border border-slate-200 bg-white py-1.5 pl-9 pr-4 text-xs font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <svg class="absolute left-3 top-2 h-4 w-4 text-slate-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Schedule Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Karyawan & Departemen</th>
                                    <th scope="col" class="px-6 py-3.5">Jenis Shift</th>
                                    <th scope="col" class="px-6 py-3.5">Jam Kerja</th>
                                    <th scope="col" class="px-6 py-3.5">Status Presensi</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($teamSchedules as $employee)
                                    @php
                                        $todaySchedule = $employee->schedules->first();
                                        $shiftName = $todaySchedule?->shift?->name
                                            ? strtolower($todaySchedule->shift->name)
                                            : 'off';
                                    @endphp
                                    <tr x-show="(activeShiftFilter === 'all' || activeShiftFilter === '{{ $shiftName }}') &&
                                                ('{{ strtolower($employee->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($employee->department ?? '') }}'.includes(searchQuery.toLowerCase()))
"
                                        class="transition-colors hover:bg-slate-50/80">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="{{ $employee->avatar_color ?? 'bg-blue-600' }} flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white shadow-sm">
                                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900">{{ $employee->name }}</p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $employee->department ?? 'Operational' }} &bull; <span
                                                            class="text-slate-400">{{ $employee->role ?? 'Staff' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($todaySchedule?->shift)
                                                @if (str_contains(strtolower($todaySchedule->shift->name), 'pagi'))
                                                    <span
                                                        class="inline-flex items-center rounded-lg border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700">
                                                        Shift Pagi
                                                    </span>
                                                @elseif(str_contains(strtolower($todaySchedule->shift->name), 'sore'))
                                                    <span
                                                        class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                        Shift Sore
                                                    </span>
                                                @elseif(str_contains(strtolower($todaySchedule->shift->name), 'malam'))
                                                    <span
                                                        class="inline-flex items-center rounded-lg border border-purple-200 bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-700">
                                                        Shift Malam
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                        {{ $todaySchedule->shift->name }}
                                                    </span>
                                                @endif
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                    Off / Libur
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-xs font-medium text-slate-600">
                                            @if ($todaySchedule?->shift)
                                                <div class="flex items-center space-x-1.5">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ \Carbon\Carbon::parse($todaySchedule->shift->start_time)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($todaySchedule->shift->end_time)->format('H:i') }}
                                                        WIB</span>
                                                </div>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($todaySchedule?->shift)
                                                <span
                                                    class="inline-flex items-center space-x-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>On Shift</span>
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center space-x-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                    <span>Off</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right">
                                            <button @click="swapModalOpen = true"
                                                class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                                Ajukan Tukar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                            Tidak ada data jadwal untuk tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <!-- MODAL 1: Tukar Shift Requests -->
        <div x-show="swapModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div x-show="swapModalOpen" x-transition.opacity
                    class="backdrop-blur-xs fixed inset-0 bg-slate-900/40 transition-opacity"
                    @click="swapModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div x-show="swapModalOpen" x-transition.scale.95
                    class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">Kelola Permintaan Tukar Shift
                        </h3>
                        <button @click="swapModalOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="max-h-[70vh] space-y-6 overflow-y-auto p-6">
                        <!-- Pending Requests List -->
                        <div>
                            <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Daftar
                                Permintaan Masuk & Keluar</h4>
                            <div class="space-y-3">
                                @forelse($swapRequests as $req)
                                    <div
                                        class="flex flex-col justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 sm:flex-row sm:items-center">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="text-sm font-bold text-slate-900">{{ $req->requester->name }}</span>
                                                <span class="text-slate-400">&rarr;</span>
                                                <span
                                                    class="text-sm font-bold text-slate-900">{{ $req->targetUser?->name ?? 'Tim' }}</span>
                                                @if ($req->status === 'pending')
                                                    <span
                                                        class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Pending</span>
                                                @elseif($req->status === 'approved')
                                                    <span
                                                        class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Approved</span>
                                                @else
                                                    <span
                                                        class="rounded-full bg-rose-100 px-2 py-0.5 text-xs font-semibold text-rose-800">Rejected</span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-xs text-slate-600">Alasan: "{{ $req->reason }}"</p>
                                        </div>

                                        @if ($req->status === 'pending')
                                            <div class="flex items-center space-x-2">
                                                <form method="POST"
                                                    action="{{ route('swap-requests.respond', $req->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit"
                                                        class="shadow-xs rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form method="POST"
                                                    action="{{ route('swap-requests.respond', $req->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit"
                                                        class="rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-300">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="py-2 text-xs text-slate-500">Belum ada riwayat permintaan tukar shift.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- New Request Form -->
                        <div>
                            <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Buat Pengajuan
                                Tukar Shift Baru</h4>
                            <form method="POST" action="{{ route('swap-requests.store') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Tukar Dengan
                                        Karyawan:</label>
                                    <select name="target_user_id" required
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none">
                                        <option value="">-- Pilih Anggota Tim --</option>
                                        @foreach ($teamSchedules as $emp)
                                            @if ($emp->id !== Auth::id())
                                                <option value="{{ $emp->id }}">{{ $emp->name }}
                                                    ({{ $emp->department ?? 'Staff' }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-700">Alasan
                                        Pertukaran:</label>
                                    <textarea name="reason" rows="2" required placeholder="Jelaskan alasan pengajuan pertukaran..."
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-800 focus:border-blue-500 focus:outline-none"></textarea>
                                </div>
                                <button type="submit"
                                    class="w-full rounded-xl bg-blue-600 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 transition-all hover:bg-blue-700">
                                    Kirim Pengajuan Tukar Shift
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 2: Detail Pengumuman -->
        <div x-show="announcementModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div x-show="announcementModalOpen" x-transition.opacity
                    class="backdrop-blur-xs fixed inset-0 bg-slate-900/40 transition-opacity"
                    @click="announcementModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div x-show="announcementModalOpen" x-transition.scale.95
                    class="inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-800"
                            x-text="selectedAnnouncement?.badge_type || 'Info'"></span>
                        <button @click="announcementModalOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900" x-text="selectedAnnouncement?.title"></h3>
                        <p class="mt-1 text-xs text-slate-400">Diterbitkan oleh <span
                                class="font-semibold text-slate-600"
                                x-text="selectedAnnouncement?.author_name"></span> &bull; <span
                                x-text="selectedAnnouncement?.time_schedule"></span></p>

                        <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-sm leading-relaxed text-slate-700" x-text="selectedAnnouncement?.content">
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 bg-slate-50 px-6 py-3 text-right">
                        <button @click="announcementModalOpen = false"
                            class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
