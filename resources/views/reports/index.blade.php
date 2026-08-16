<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shift-Management') }} - Laporan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: true }">

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
                    {{-- @if (isset($pendingSwapCount) && $pendingSwapCount > 0)
                        <span
                            class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">
                            {{ $pendingSwapCount }}
                        </span>
                    @endif --}}
                </a>
                <a href="{{ route('employees.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a9 9 0 00-9-9">
                        </path>
                    </svg>
                    <span>Karyawan</span>
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                    <svg class="mr-3 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <h1 class="text-xl font-bold text-slate-900">Laporan</h1>
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

            <!-- Reports Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8" x-data>
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                    id="report-content">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Laporan Jadwal Bulanan</h3>
                                <p class="mt-0.5 text-sm text-slate-500">
                                    Menampilkan rincian jadwal shift untuk semua karyawan pada bulan
                                    <span
                                        class="font-semibold text-slate-700">{{ $selectedDate->format('F Y') }}</span>.
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="window.print()"
                                    class="hidden-print rounded-md bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50">
                                    Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <div class="hidden-print border-b border-slate-200 p-6">
                        <form action="{{ route('reports.index') }}" method="GET" class="flex items-end gap-4">
                            <div>
                                <label for="month" class="block text-sm font-medium text-slate-700">Bulan</label>
                                <select id="month" name="month"
                                    class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}"
                                            {{ $selectedDate->month == $m ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="year" class="block text-sm font-medium text-slate-700">Tahun</label>
                                <select id="year" name="year"
                                    class="mt-1 block w-full rounded-md border-slate-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                                    @php
                                        $currentYear = date('Y');
                                    @endphp
                                    @foreach (range($currentYear - 2, $currentYear + 1) as $y)
                                        <option value="{{ $y }}"
                                            {{ $selectedDate->year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Tampilkan
                            </button>
                        </form>
                    </div>

                    <!-- Report Table -->
                    <div class="overflow-x-auto">
                        <div class="min-w-full p-6">
                            <table class="min-w-full divide-y divide-slate-200 border border-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col"
                                            class="sticky left-0 z-10 w-40 bg-slate-50 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                            Karyawan</th>
                                        <th scope="col"
                                            class="w-48 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                            Total Shift</th>
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            <th scope="col"
                                                class="w-16 px-2 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                {{ $day }}
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @forelse ($reportData as $employee)
                                        <tr>
                                            <td
                                                class="sticky left-0 z-10 w-40 whitespace-nowrap bg-white px-3 py-3 font-medium text-slate-900">
                                                {{ $employee->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-3 text-slate-600">
                                                <div class="flex flex-wrap gap-x-2 gap-y-1">
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-600/20">Pagi:
                                                        {{ $employee->shift_counts['Pagi'] }}</span>
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Sore:
                                                        {{ $employee->shift_counts['Sore'] }}</span>
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">Malam:
                                                        {{ $employee->shift_counts['Malam'] }}</span>
                                                    <span
                                                        class="inline-flex items-center rounded-md bg-slate-50 px-2 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">Off:
                                                        {{ $employee->shift_counts['Off'] }}</span>
                                                </div>
                                            </td>
                                            @foreach ($employee->schedules_by_day as $shiftName)
                                                <td class="whitespace-nowrap text-center">
                                                    @if ($shiftName == 'Off')
                                                        <span class="text-slate-400">{{ $shiftName }}</span>
                                                    @elseif (preg_match('/pagi/i', $shiftName))
                                                        <span
                                                            class="font-semibold text-sky-600">{{ $shiftName }}</span>
                                                    @elseif (preg_match('/sore/i', $shiftName))
                                                        <span
                                                            class="font-semibold text-amber-600">{{ $shiftName }}</span>
                                                    @elseif (preg_match('/malam/i', $shiftName))
                                                        <span
                                                            class="font-semibold text-indigo-600">{{ $shiftName }}</span>
                                                    @else
                                                        <span
                                                            class="font-semibold text-slate-700">{{ $shiftName }}</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $daysInMonth + 2 }}"
                                                class="py-12 text-center text-slate-500">
                                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-slate-900">Tidak ada data
                                                    karyawan</h3>
                                                <p class="mt-1 text-sm text-slate-500">Silakan tambahkan data karyawan
                                                    terlebih dahulu.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <style>
                    @media print {
                        body {
                            background-color: white;
                        }

                        .hidden-print {
                            display: none !important;
                        }

                        main {
                            padding: 0;
                            overflow: visible;
                        }

                        #report-content {
                            box-shadow: none;
                            border: none;
                        }

                        .sticky {
                            position: static;
                        }
                    }
                </style>
            </main>
        </div>
    </div>
</body>

</html>
</p>
</div>
</div>
</div>
</div>
</main>
</div>
</div>
</body>

</html>
