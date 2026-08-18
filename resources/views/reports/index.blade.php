<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shift-Management') }} — Laporan Shift</title>
    <meta name="description" content="Laporan jadwal shift bulanan seluruh karyawan dalam format tabel interaktif.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .report-table td, .report-table th { min-width: 38px; }
        .cell-pagi   { background: #e0f2fe; color: #0369a1; font-weight: 700; }
        .cell-sore   { background: #ede9fe; color: #6d28d9; font-weight: 700; }
        .cell-malam  { background: #f3e8ff; color: #7e22ce; font-weight: 700; }
        .cell-off    { background: #f8fafc; color: #94a3b8; }
        @media print {
            aside, header, .no-print { display: none !important; }
            main { overflow: visible !important; }
            .report-wrap { overflow: visible !important; }
        }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: true }">

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
                    @if (isset($pendingSwapCount) && $pendingSwapCount > 0)
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">{{ $pendingSwapCount }}</span>
                    @endif
                </a>
                <a href="{{ route('employees.index') }}"
                    class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900">
                    <svg class="mr-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a9 9 0 00-9-9"/>
                    </svg>
                    <span>Karyawan</span>
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                    <svg class="mr-3 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Laporan</span>
                </a>
            </nav>
        </aside>

        {{-- MAIN --}}
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
                        <h1 class="text-xl font-bold text-slate-900">Laporan Shift</h1>
                        <p class="hidden text-xs text-slate-500 md:block">Rekap jadwal kerja seluruh karyawan per bulan</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="window.print()" class="no-print flex items-center space-x-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak / PDF</span>
                    </button>
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

                {{-- Hero Banner --}}
                <div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 p-6 text-white shadow-lg shadow-blue-500/20">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
                        <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>
                    </div>
                    <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <span class="inline-block rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-md">
                                📊 Laporan Bulanan
                            </span>
                            <h2 class="mt-2 text-2xl font-extrabold">
                                {{ $selectedDate->translatedFormat('F Y') }}
                            </h2>
                            <p class="mt-1 text-sm text-blue-100">
                                Menampilkan jadwal shift seluruh karyawan untuk bulan {{ $selectedDate->translatedFormat('F Y') }}.
                            </p>
                        </div>

                        {{-- Month Filter Form --}}
                        <form method="GET" action="{{ route('reports.index') }}"
                            class="no-print flex flex-wrap items-center gap-3 rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                            <div class="flex flex-col">
                                <label class="mb-1 text-[10px] font-semibold uppercase tracking-widest text-blue-200">Bulan</label>
                                <select name="month" onchange="this.form.submit()"
                                    class="rounded-lg border border-white/20 bg-white/10 px-3 py-1.5 text-sm font-semibold text-white backdrop-blur-sm focus:outline-none focus:ring-1 focus:ring-white/50">
                                    @foreach(range(1,12) as $m)
                                        <option value="{{ $m }}" {{ $selectedDate->month == $m ? 'selected' : '' }}
                                            class="bg-slate-800 text-white">
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label class="mb-1 text-[10px] font-semibold uppercase tracking-widest text-blue-200">Tahun</label>
                                <select name="year" onchange="this.form.submit()"
                                    class="rounded-lg border border-white/20 bg-white/10 px-3 py-1.5 text-sm font-semibold text-white backdrop-blur-sm focus:outline-none focus:ring-1 focus:ring-white/50">
                                    @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                                        <option value="{{ $y }}" {{ $selectedDate->year == $y ? 'selected' : '' }}
                                            class="bg-slate-800 text-white">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="no-print mb-4 flex flex-wrap items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Legenda:</span>
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-800">
                        <span class="h-2 w-2 rounded-sm bg-sky-400"></span><span>P = Pagi</span>
                    </span>
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-violet-100 px-2.5 py-1 text-xs font-semibold text-violet-800">
                        <span class="h-2 w-2 rounded-sm bg-violet-400"></span><span>S = Sore</span>
                    </span>
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-800">
                        <span class="h-2 w-2 rounded-sm bg-purple-500"></span><span>M = Malam</span>
                    </span>
                    <span class="inline-flex items-center space-x-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                        <span class="h-2 w-2 rounded-sm bg-slate-300"></span><span>- = Off/Libur</span>
                    </span>
                </div>

                {{-- Report Table --}}
                <div class="report-wrap overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="report-table min-w-full text-center text-xs">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="sticky left-0 z-10 border-b border-r border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-500 whitespace-nowrap" style="min-width:160px">
                                        Karyawan
                                    </th>
                                    @for ($d = 1; $d <= $daysInMonth; $d++)
                                        @php
                                            $dayDate = \Carbon\Carbon::createFromDate($selectedDate->year, $selectedDate->month, $d);
                                            $isWeekend = $dayDate->isWeekend();
                                        @endphp
                                        <th class="border-b border-slate-100 py-2 px-1 font-bold {{ $isWeekend ? 'bg-amber-50 text-amber-600' : 'text-slate-500' }}" style="min-width:36px">
                                            <div>{{ $d }}</div>
                                            <div class="font-normal text-[9px] {{ $isWeekend ? 'text-amber-400' : 'text-slate-400' }}">{{ $dayDate->translatedFormat('D') }}</div>
                                        </th>
                                    @endfor
                                    <th class="border-b border-l border-slate-200 py-3 px-2 font-bold text-sky-700 whitespace-nowrap" style="min-width:40px">P</th>
                                    <th class="border-b border-slate-100 py-3 px-2 font-bold text-violet-700" style="min-width:40px">S</th>
                                    <th class="border-b border-slate-100 py-3 px-2 font-bold text-purple-700" style="min-width:40px">M</th>
                                    <th class="border-b border-slate-100 py-3 px-2 font-bold text-slate-400" style="min-width:40px">Off</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($reportData as $employee)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="sticky left-0 z-10 border-r border-slate-100 bg-white px-4 py-2.5 text-left" style="min-width:160px">
                                            <div class="flex items-center space-x-2">
                                                <div class="{{ $employee->avatar_color ?? 'bg-blue-600' }} flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-[10px] font-bold text-white shadow-sm">
                                                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-900" style="max-width:110px">{{ $employee->name }}</p>
                                                    <p class="truncate text-[10px] text-slate-400">{{ $employee->department ?? 'Operational' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        @for ($d = 1; $d <= $daysInMonth; $d++)
                                            @php
                                                $shiftVal = $employee->schedules_by_day[$d] ?? 'Off';
                                                $sl = strtolower($shiftVal);
                                                if ($shiftVal === 'Off') {
                                                    $label = '-'; $cellClass = 'cell-off';
                                                } elseif (str_contains($sl, 'pagi')) {
                                                    $label = 'P'; $cellClass = 'cell-pagi';
                                                } elseif (str_contains($sl, 'sore')) {
                                                    $label = 'S'; $cellClass = 'cell-sore';
                                                } elseif (str_contains($sl, 'malam')) {
                                                    $label = 'M'; $cellClass = 'cell-malam';
                                                } else {
                                                    $label = substr($shiftVal, 0, 1); $cellClass = 'cell-pagi';
                                                }
                                                $dayDate = \Carbon\Carbon::createFromDate($selectedDate->year, $selectedDate->month, $d);
                                                $isWeekend = $dayDate->isWeekend();
                                            @endphp
                                            <td class="py-2 px-0 text-center text-[11px] {{ $cellClass }} {{ $isWeekend && $shiftVal === 'Off' ? 'bg-amber-50' : '' }}"
                                                title="{{ $shiftVal === 'Off' ? 'Libur' : $shiftVal }}">
                                                {{ $label }}
                                            </td>
                                        @endfor
                                        <td class="border-l border-slate-100 py-2 px-2 font-bold text-sky-700">{{ $employee->shift_counts['Pagi'] }}</td>
                                        <td class="py-2 px-2 font-bold text-violet-700">{{ $employee->shift_counts['Sore'] }}</td>
                                        <td class="py-2 px-2 font-bold text-purple-700">{{ $employee->shift_counts['Malam'] }}</td>
                                        <td class="py-2 px-2 font-medium text-slate-400">{{ $employee->shift_counts['Off'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $daysInMonth + 5 }}" class="px-6 py-16 text-center text-sm text-slate-400">
                                            Belum ada data karyawan untuk bulan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Footer summary --}}
                    <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-6 py-3">
                        <p class="text-xs text-slate-500">
                            Laporan bulan <span class="font-semibold text-slate-700">{{ $selectedDate->translatedFormat('F Y') }}</span>
                            &bull; <span class="font-semibold text-slate-700">{{ $reportData->count() }}</span> karyawan
                        </p>
                        <div class="no-print flex items-center space-x-3 text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">{{ $daysInMonth }}</span> hari kerja
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>
