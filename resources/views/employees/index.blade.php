<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shift-Management') }} - Karyawan</title>
    <meta name="description" content="Daftar seluruh karyawan, kelola data, departemen, dan jabatan.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased"
    x-data="{
        sidebarOpen: true,
        searchQuery: '',
        deleteModal: false,
        deleteTarget: null,
        deleteUrl: '',
        openDeleteModal(id, name) {
            this.deleteTarget = name;
            this.deleteUrl = '/employees/' + id;
            this.deleteModal = true;
        }
    }">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-64 shrink-0 border-r border-slate-200 bg-white shadow-sm transition-all duration-300"
            :class="{ '-ml-64': !sidebarOpen }">
            <div class="flex h-16 items-center border-b border-slate-100 px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-lg font-bold text-white shadow-md shadow-blue-500/20">S</div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">Shift<span class="text-blue-600">Manager</span></span>
                </a>
            </div>
            <nav class="mt-6 space-y-1.5 px-3">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('schedule.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Jadwal Shift</span>
                </a>
                <a href="{{ route('swap-shift.index') }}"
                    class="flex items-center justify-between rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Tukar Shift</span>
                    </div>
                    @if ($pendingSwapCount > 0)
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">{{ $pendingSwapCount }}</span>
                    @endif
                </a>
                <a href="{{ route('employees.index') }}"
                    class="flex items-center rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                    <svg class="mr-3 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a9 9 0 00-9-9"/>
                    </svg>
                    <span>Karyawan</span>
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Laporan</span>
                </a>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex flex-1 flex-col overflow-hidden">

            {{-- HEADER --}}
            <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">Manajemen Karyawan</h1>
                        <p class="hidden text-xs text-slate-500 md:block">Kelola data seluruh karyawan dalam sistem</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                            <div class="{{ Auth::user()->avatar_color ?? 'bg-blue-600' }} flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-white shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden text-left md:block">
                                <p class="text-sm font-semibold leading-none text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ Auth::user()->role ?? 'Staff' }}</p>
                            </div>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                            class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-slate-100 bg-white py-1 shadow-lg ring-1 ring-black/5">
                            <div class="border-b border-slate-100 px-4 py-2">
                                <p class="text-xs font-semibold uppercase text-slate-400">Signed in as</p>
                                <p class="truncate text-sm font-medium text-slate-800">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- CONTENT --}}
            <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">

                {{-- Flash messages --}}
                @if (session('success'))
                    <div class="mb-5 flex items-center space-x-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                        <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-5 flex items-center space-x-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 shadow-sm">
                        <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Hero Banner --}}
                <div class="relative mb-6 overflow-hidden rounded-2xl bg-linear-to-r from-indigo-600 via-blue-600 to-sky-500 p-6 text-white shadow-lg shadow-blue-500/20">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
                        <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>
                    </div>
                    <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                        <div>
                            <h2 class="text-2xl font-extrabold">Daftar Karyawan</h2>
                            <p class="mt-1 max-w-lg text-sm text-blue-100">
                                Total <span class="font-bold text-white">{{ $employees->total() }}</span> karyawan terdaftar dalam sistem.
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-center backdrop-blur-md">
                                <p class="text-2xl font-bold">{{ $employees->total() }}</p>
                                <p class="mt-0.5 text-xs text-blue-200">Total Karyawan</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Card --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    {{-- Toolbar --}}
                    <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-base font-bold text-slate-900">Semua Karyawan</h3>
                        <div class="relative w-full sm:w-72">
                            <input type="text" x-model="searchQuery" placeholder="Cari nama, email, departemen..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-4 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-6 py-3.5">#</th>
                                    <th class="px-6 py-3.5">Karyawan</th>
                                    <th class="px-6 py-3.5">Email</th>
                                    <th class="px-6 py-3.5">Departemen</th>
                                    <th class="px-6 py-3.5">Jabatan</th>
                                    <th class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($employees as $index => $employee)
                                    <tr x-show="
                                        searchQuery === '' ||
                                        '{{ strtolower($employee->name) }}'.includes(searchQuery.toLowerCase()) ||
                                        '{{ strtolower($employee->email) }}'.includes(searchQuery.toLowerCase()) ||
                                        '{{ strtolower($employee->department ?? '') }}'.includes(searchQuery.toLowerCase()) ||
                                        '{{ strtolower($employee->role ?? '') }}'.includes(searchQuery.toLowerCase())
                                    " class="transition-colors hover:bg-slate-50/70">
                                        <td class="whitespace-nowrap px-6 py-4 text-xs font-semibold text-slate-400">
                                            {{ $employees->firstItem() + $index }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="{{ $employee->avatar_color ?? 'bg-blue-600' }} flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white shadow-sm">
                                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-900">{{ $employee->name }}</p>
                                                    @if($employee->id === Auth::id())
                                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold text-blue-700">Anda</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $employee->email }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                                {{ $employee->department ?? 'Operational' }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                                {{ $employee->role ?? 'Staff' }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('employees.edit', $employee->id) }}"
                                                    class="inline-flex items-center space-x-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>
                                                @if($employee->id !== Auth::id())
                                                    <button @click="openDeleteModal({{ $employee->id }}, '{{ addslashes($employee->name) }}')"
                                                        class="inline-flex items-center space-x-1 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-sm transition-all hover:bg-rose-100">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <p class="mt-2 text-sm font-medium text-slate-500">Belum ada data karyawan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($employees->hasPages())
                        <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                            <p class="text-xs text-slate-500">
                                Menampilkan <span class="font-semibold text-slate-700">{{ $employees->firstItem() }}</span>–<span class="font-semibold text-slate-700">{{ $employees->lastItem() }}</span>
                                dari <span class="font-semibold text-slate-700">{{ $employees->total() }}</span> karyawan
                            </p>
                            <div class="flex items-center space-x-1">
                                {{-- Previous --}}
                                @if ($employees->onFirstPage())
                                    <span class="cursor-not-allowed rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-300">‹ Prev</span>
                                @else
                                    <a href="{{ $employees->previousPageUrl() }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm hover:bg-slate-50">‹ Prev</a>
                                @endif

                                {{-- Page numbers --}}
                                @foreach ($employees->getUrlRange(max(1, $employees->currentPage() - 2), min($employees->lastPage(), $employees->currentPage() + 2)) as $page => $url)
                                    @if ($page == $employees->currentPage())
                                        <span class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm hover:bg-slate-50">{{ $page }}</a>
                                    @endif
                                @endforeach

                                {{-- Next --}}
                                @if ($employees->hasMorePages())
                                    <a href="{{ $employees->nextPageUrl() }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm hover:bg-slate-50">Next ›</a>
                                @else
                                    <span class="cursor-not-allowed rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-300">Next ›</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-3">
                            <p class="text-xs text-slate-500">Total <span class="font-semibold text-slate-700">{{ $employees->total() }}</span> karyawan</p>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="deleteModal = false"></div>

            <div x-show="deleteModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

                <div class="h-1 w-full bg-linear-to-r from-rose-500 to-pink-500"></div>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-100">
                            <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Karyawan</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Apakah Anda yakin ingin menghapus karyawan
                                <span class="font-semibold text-slate-800" x-text="'«' + deleteTarget + '»'"></span>?
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button @click="deleteModal = false"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Batal
                        </button>
                        <form :action="deleteUrl" method="POST" x-ref="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-rose-500/20 transition hover:bg-rose-700">
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
