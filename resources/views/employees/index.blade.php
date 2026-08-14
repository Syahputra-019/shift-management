<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shift-Management') }} - Karyawan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: true, searchQuery: '' }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 shrink-0 border-r border-slate-200 bg-white shadow-sm transition-all duration-300"
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
                    {{-- Placeholder for pending swap count --}}
                    {{-- @if (isset($pendingSwapCount) && $pendingSwapCount > 0)
                        <span
                            class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                            {{ $pendingSwapCount }}
                        </span>
                    @endif --}}
                </a>
                <a href="{{ route('employees.index') }}"
                    class="flex items-center rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                    <svg class="mr-3 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a9 9 0 00-9-9">
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
                        <h1 class="text-xl font-bold text-slate-900">Daftar Karyawan</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
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
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil Saya</a>
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

            <!-- Employee List Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div
                        class="flex flex-col space-y-3 border-b border-slate-100 bg-slate-50/50 px-6 py-3.5 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Daftar Karyawan</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Manajemen data karyawan.</p>
                        </div>
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Nama Karyawan</th>
                                    <th scope="col" class="px-6 py-3.5">Email</th>
                                    <th scope="col" class="px-6 py-3.5">Departemen</th>
                                    <th scope="col" class="px-6 py-3.5">Jabatan</th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($employees as $employee)
                                    <tr x-show="'{{ strtolower($employee->name) }}'.includes(searchQuery.toLowerCase()) ||
                                                '{{ strtolower($employee->department ?? '') }}'.includes(searchQuery.toLowerCase()) ||
                                                '{{ strtolower($employee->email) }}'.includes(searchQuery.toLowerCase())"
                                        class="transition-colors hover:bg-slate-50/80">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="{{ $employee->avatar_color ?? 'bg-blue-600' }} flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white shadow-sm">
                                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900">{{ $employee->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">
                                            {{ $employee->email }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">
                                            {{ $employee->department ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-600">
                                            {{ $employee->role ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right">
                                            <a href="#"
                                                class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                            Tidak ada data karyawan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
