<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shift-Management') }} - Jadwal Shift</title>
    <meta name="description" content="Halaman jadwal shift mingguan karyawan - lihat dan kelola jadwal kerja tim secara visual.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .shift-pagi  { background: linear-gradient(135deg,#e0f2fe 0%,#bae6fd 100%); border-color:#7dd3fc; color:#0369a1; }
        .shift-sore  { background: linear-gradient(135deg,#ede9fe 0%,#ddd6fe 100%); border-color:#a78bfa; color:#6d28d9; }
        .shift-malam { background: linear-gradient(135deg,#f3e8ff 0%,#e9d5ff 100%); border-color:#c084fc; color:#7e22ce; }
        .shift-other { background: linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%); border-color:#93c5fd; color:#1d4ed8; }
        .shift-off   { background:#f8fafc; border-color:#e2e8f0; color:#94a3b8; }
        .my-shift-pagi  { background:linear-gradient(135deg,#0ea5e9 0%,#0284c7 100%); color:#fff; border-color:#0369a1; }
        .my-shift-sore  { background:linear-gradient(135deg,#7c3aed 0%,#6d28d9 100%); color:#fff; border-color:#5b21b6; }
        .my-shift-malam { background:linear-gradient(135deg,#9333ea 0%,#7e22ce 100%); color:#fff; border-color:#6b21a8; }
        .my-shift-other { background:linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%); color:#fff; border-color:#1e40af; }
        .my-shift-off   { background:#f1f5f9; border-color:#cbd5e1; color:#64748b; }
        .shift-card { transition:transform .15s ease,box-shadow .15s ease; }
        .shift-card:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.08); }
        .today-col { background:linear-gradient(180deg,rgba(59,130,246,.04) 0%,transparent 100%); }
        @keyframes pulse-ring {
            0%   { box-shadow:0 0 0 0 rgba(59,130,246,.4); }
            70%  { box-shadow:0 0 0 6px rgba(59,130,246,0); }
            100% { box-shadow:0 0 0 0 rgba(59,130,246,0); }
        }
        .today-dot { animation:pulse-ring 2s infinite; }
        .schedule-table-wrap { overflow-x:auto; }
        .sticky-col      { position:sticky; left:0; z-index:10; background:white; }
        .sticky-col-head { position:sticky; left:0; z-index:20; background:#f8fafc; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800"
      x-data="{
          sidebarOpen: true,
          searchQuery: '',
          filterShift: 'all',
          detailModal: false,
          detailData: null,
          openDetail(name, dept, role, shiftName, shiftTime, date, isMe) {
              this.detailData = { name, dept, role, shiftName, shiftTime, date, isMe };
              this.detailModal = true;
          }
      }">
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 flex-shrink-0 border-r border-slate-200 bg-white transition-all duration-300 shadow-sm relative"
           :class="{ '-ml-64': !sidebarOpen }">
        <div class="flex h-16 items-center border-b border-slate-100 px-6">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white font-bold text-lg shadow-md shadow-blue-500/20">S</div>
                <span class="text-xl font-bold tracking-tight text-slate-900">Shift<span class="text-blue-600">Manager</span></span>
            </a>
        </div>
        <nav class="mt-6 space-y-1.5 px-3">
            <a href="{{ route('dashboard') }}" class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('schedule.index') }}" class="flex items-center rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Jadwal Shift</span>
            </a>
            <a href="#" class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span>Tukar Shift</span>
            </a>
            <a href="#" class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Karyawan</span>
            </a>
            <a href="#" class="flex items-center rounded-lg px-4 py-2.5 font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan</span>
            </a>
        </nav>
        {{-- Legend --}}
        <div class="absolute bottom-0 left-0 right-0 border-t border-slate-100 p-4">
            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Legenda Shift</p>
            <div class="space-y-1.5">
                <div class="flex items-center space-x-2"><span class="h-3 w-3 rounded-sm bg-sky-400"></span><span class="text-xs text-slate-600 font-medium">Pagi (06:00-14:00)</span></div>
                <div class="flex items-center space-x-2"><span class="h-3 w-3 rounded-sm bg-violet-400"></span><span class="text-xs text-slate-600 font-medium">Sore (14:00-22:00)</span></div>
                <div class="flex items-center space-x-2"><span class="h-3 w-3 rounded-sm bg-purple-500"></span><span class="text-xs text-slate-600 font-medium">Malam (22:00-06:00)</span></div>
                <div class="flex items-center space-x-2"><span class="h-3 w-3 rounded-sm bg-slate-300"></span><span class="text-xs text-slate-600 font-medium">Off / Libur</span></div>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex flex-1 flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Jadwal Shift</h1>
                    <p class="text-xs text-slate-500 hidden md:block">Kalender jadwal kerja mingguan tim karyawan</p>
                </div>
            </div>
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3 focus:outline-none">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full {{ Auth::user()->avatar_color ?? 'bg-blue-600' }} text-white font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden text-left md:block">
                        <p class="text-sm font-semibold text-slate-800 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->role ?? 'Staff' }}</p>
                    </div>
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                     class="absolute right-0 z-20 mt-2 w-48 rounded-xl border border-slate-100 bg-white py-1 shadow-lg">
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-xs font-semibold text-slate-400 uppercase">Signed in as</p>
                        <p class="text-sm font-medium text-slate-800 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 font-medium">Log Out</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">

            {{-- HERO BANNER --}}
            <div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 p-6 text-white shadow-lg shadow-blue-500/20">
                <div class="pointer-events-none absolute inset-0 overflow-hidden">
                    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
                    <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>
                    <div class="absolute bottom-0 left-1/3 h-24 w-24 rounded-full bg-white/5"></div>
                </div>
                <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <span class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-md border border-white/10">
                            &#x1F4C5; Periode: {{ $weekStart->translatedFormat('d M') }} &ndash; {{ $weekEnd->translatedFormat('d M Y') }}
                        </span>
                        <h2 class="mt-2 text-2xl font-extrabold lg:text-3xl">Jadwal Shift Minggu Ini</h2>
                        <p class="mt-1 text-blue-100 text-sm max-w-lg">
                            Lihat dan pantau jadwal kerja seluruh tim secara visual dalam tampilan kalender mingguan yang interaktif.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-3 text-center border border-white/10">
                            <p class="text-2xl font-bold">{{ $weekStats['pagi'] }}</p>
                            <p class="text-xs text-blue-200 mt-0.5">Shift Pagi</p>
                        </div>
                        <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-3 text-center border border-white/10">
                            <p class="text-2xl font-bold">{{ $weekStats['sore'] }}</p>
                            <p class="text-xs text-blue-200 mt-0.5">Shift Sore</p>
                        </div>
                        <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-3 text-center border border-white/10">
                            <p class="text-2xl font-bold">{{ $weekStats['malam'] }}</p>
                            <p class="text-xs text-blue-200 mt-0.5">Shift Malam</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MY SCHEDULE STRIP --}}
            <div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ Auth::user()->avatar_color ?? 'bg-blue-600' }} text-white text-xs font-bold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Jadwal Saya Minggu Ini</h3>
                            <p class="text-xs text-slate-500">{{ Auth::user()->name }} &bull; {{ Auth::user()->department ?? 'Operational' }}</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-blue-50 border border-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                        {{ collect($myWeekSchedules)->filter(fn($s) => $s && $s->shift)->count() }} hari kerja
                    </span>
                </div>
                <div class="grid grid-cols-7 divide-x divide-slate-100">
                    @foreach($weekDays as $day)
                        @php
                            $dayKey = $day->toDateString();
                            $mySched = $myWeekSchedules[$dayKey] ?? null;
                            $myShift = $mySched?->shift;
                            $isToday = $day->isToday();
                            $myShiftType = 'off';
                            if ($myShift) {
                                $n = strtolower($myShift->name);
                                if (str_contains($n, 'pagi')) $myShiftType = 'pagi';
                                elseif (str_contains($n, 'sore')) $myShiftType = 'sore';
                                elseif (str_contains($n, 'malam')) $myShiftType = 'malam';
                                else $myShiftType = 'other';
                            }
                            $myCardClass = match($myShiftType) {
                                'pagi'  => 'my-shift-pagi',
                                'sore'  => 'my-shift-sore',
                                'malam' => 'my-shift-malam',
                                'other' => 'my-shift-other',
                                default => 'my-shift-off',
                            };
                        @endphp
                        <div class="flex flex-col items-center py-4 px-2 {{ $isToday ? 'bg-blue-50/60' : '' }} transition-colors">
                            <p class="text-xs font-semibold uppercase tracking-wide {{ $isToday ? 'text-blue-600' : 'text-slate-400' }}">
                                {{ $day->translatedFormat('D') }}
                            </p>
                            <div class="my-2 flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold {{ $isToday ? 'bg-blue-600 text-white today-dot shadow-md' : 'text-slate-700' }}">
                                {{ $day->format('d') }}
                            </div>
                            @if($myShift)
                                <div class="w-full rounded-lg border px-1 py-1.5 text-center shift-card {{ $myCardClass }}">
                                    <p class="text-xs font-bold leading-tight">{{ $myShift->name }}</p>
                                    <p class="text-[10px] opacity-80 mt-0.5">
                                        {{ \Carbon\Carbon::parse($myShift->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($myShift->end_time)->format('H:i') }}
                                    </p>
                                </div>
                            @else
                                <div class="w-full rounded-lg border border-dashed border-slate-200 px-1 py-1.5 text-center my-shift-off">
                                    <p class="text-[10px] font-semibold">Off</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- WEEKLY CALENDAR TABLE --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                {{-- Toolbar --}}
                <div class="flex flex-col gap-4 border-b border-slate-100 p-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Kalender Jadwal Tim</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $users->count() }} karyawan &bull; {{ $weekStart->translatedFormat('d M') }} &ndash; {{ $weekEnd->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Week Nav --}}
                        <div class="flex items-center space-x-1 rounded-xl bg-slate-100 p-1">
                            <a href="{{ route('schedule.index', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                               class="rounded-lg p-1.5 text-slate-600 hover:bg-white hover:shadow-sm transition-all">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                            <a href="{{ route('schedule.index') }}"
                               class="rounded-lg px-3 py-1 text-xs font-bold transition-all {{ $weekStart->isCurrentWeek() ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-white' }}">
                                Minggu Ini
                            </a>
                            <a href="{{ route('schedule.index', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                               class="rounded-lg p-1.5 text-slate-600 hover:bg-white hover:shadow-sm transition-all">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        {{-- Shift Filter --}}
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button @click="filterShift = 'all'" :class="filterShift === 'all' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="rounded-lg px-2.5 py-1 text-xs font-semibold transition-all">Semua</button>
                            <button @click="filterShift = 'pagi'" :class="filterShift === 'pagi' ? 'bg-sky-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="rounded-lg px-2.5 py-1 text-xs font-semibold transition-all">Pagi</button>
                            <button @click="filterShift = 'sore'" :class="filterShift === 'sore' ? 'bg-violet-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="rounded-lg px-2.5 py-1 text-xs font-semibold transition-all">Sore</button>
                            <button @click="filterShift = 'malam'" :class="filterShift === 'malam' ? 'bg-purple-700 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="rounded-lg px-2.5 py-1 text-xs font-semibold transition-all">Malam</button>
                        </div>
                        {{-- Search --}}
                        <div class="relative">
                            <input type="text" x-model="searchQuery" placeholder="Cari karyawan..."
                                   class="w-48 rounded-xl border border-slate-200 bg-white py-1.5 pl-8 pr-3 text-xs font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <svg class="absolute left-2.5 top-2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="schedule-table-wrap">
                    <table class="min-w-full table-fixed border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="sticky-col-head w-52 py-3.5 px-5 text-left text-xs font-bold uppercase tracking-wider text-slate-500 border-r border-slate-100">Karyawan</th>
                                @foreach($weekDays as $day)
                                    @php $isToday = $day->isToday(); @endphp
                                    <th class="w-36 py-3.5 px-3 text-center {{ $isToday ? 'today-col' : '' }}">
                                        <div class="flex flex-col items-center space-y-1">
                                            <span class="text-xs font-semibold uppercase tracking-wide {{ $isToday ? 'text-blue-600' : 'text-slate-400' }}">{{ $day->translatedFormat('D') }}</span>
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-sm font-bold {{ $isToday ? 'bg-blue-600 text-white shadow-sm today-dot' : 'text-slate-700' }}">{{ $day->format('d') }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $day->translatedFormat('M') }}</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @forelse($users as $user)
                                @php $isMe = $user->id === Auth::id(); @endphp
                                <tr x-show="
                                    filterShift === 'all' &&
                                    ('{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase()) ||
                                     '{{ strtolower($user->department ?? '') }}'.includes(searchQuery.toLowerCase()))"
                                    class="hover:bg-slate-50/60 transition-colors {{ $isMe ? 'bg-blue-50/20' : '' }}">
                                    {{-- Name --}}
                                    <td class="sticky-col border-r border-slate-100 py-3 px-4 {{ $isMe ? 'bg-blue-50/40' : 'bg-white' }}">
                                        <div class="flex items-center space-x-3">
                                            <div class="relative flex-shrink-0">
                                                <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $user->avatar_color ?? 'bg-blue-600' }} text-white text-xs font-bold shadow-sm">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                @if($isMe)
                                                    <span class="absolute -top-0.5 -right-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-blue-600 border-2 border-white">
                                                        <span class="text-[5px] text-white font-bold">Me</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500 truncate">{{ $user->department ?? 'Operational' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- Day columns --}}
                                    @foreach($weekDays as $day)
                                        @php
                                            $dayKey  = $day->toDateString();
                                            $sched   = $scheduleMatrix[$user->id][$dayKey] ?? null;
                                            $shift   = $sched?->shift;
                                            $isToday = $day->isToday();
                                            $isPast  = $day->isPast() && !$isToday;
                                            $shiftType = 'off';
                                            if ($shift) {
                                                $sn = strtolower($shift->name);
                                                if (str_contains($sn, 'pagi'))       $shiftType = 'pagi';
                                                elseif (str_contains($sn, 'sore'))   $shiftType = 'sore';
                                                elseif (str_contains($sn, 'malam'))  $shiftType = 'malam';
                                                else                                  $shiftType = 'other';
                                            }
                                            $cardClass = match($shiftType) {
                                                'pagi'  => 'shift-pagi',
                                                'sore'  => 'shift-sore',
                                                'malam' => 'shift-malam',
                                                'other' => 'shift-other',
                                                default => 'shift-off',
                                            };
                                        @endphp
                                        <td class="px-2 py-2.5 text-center align-middle {{ $isToday ? 'today-col' : '' }}">
                                            @if($shift)
                                                @php
                                                    $ss = \Carbon\Carbon::parse($shift->start_time)->format('H:i');
                                                    $se = \Carbon\Carbon::parse($shift->end_time)->format('H:i');
                                                    $un = addslashes($user->name);
                                                    $ud = addslashes($user->department ?? 'Operational');
                                                    $ur = addslashes($user->role ?? 'Staff');
                                                    $sn2 = addslashes($shift->name);
                                                    $df = $day->translatedFormat('l, d M Y');
                                                @endphp
                                                <button @click="openDetail('{{ $un }}','{{ $ud }}','{{ $ur }}','{{ $sn2 }}','{{ $ss }} - {{ $se }} WIB','{{ $df }}',{{ $isMe ? 'true' : 'false' }})"
                                                        class="shift-card w-full rounded-xl border px-2 py-2 {{ $cardClass }} {{ $isPast ? 'opacity-60' : '' }} text-center block cursor-pointer">
                                                    <p class="text-xs font-bold leading-tight">{{ $shift->name }}</p>
                                                    <p class="text-[10px] opacity-75 mt-0.5">{{ $ss }}-{{ $se }}</p>
                                                </button>
                                            @else
                                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-2 px-2 {{ $isPast ? 'opacity-40' : '' }}">
                                                    <p class="text-[10px] font-medium text-slate-400">-</p>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <p class="text-sm text-slate-500">Tidak ada data karyawan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-5 py-3">
                    <p class="text-xs text-slate-500">
                        <span class="font-semibold text-slate-700">{{ $users->count() }}</span> karyawan &bull;
                        Total jadwal: <span class="font-semibold text-slate-700">{{ $weekStats['total_schedules'] }}</span>
                    </p>
                    <div class="flex items-center space-x-3 text-xs text-slate-500">
                        <div class="flex items-center space-x-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-sky-400"></span><span>Pagi: {{ $weekStats['pagi'] }}</span></div>
                        <div class="flex items-center space-x-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-violet-400"></span><span>Sore: {{ $weekStats['sore'] }}</span></div>
                        <div class="flex items-center space-x-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-purple-500"></span><span>Malam: {{ $weekStats['malam'] }}</span></div>
                        <div class="flex items-center space-x-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-slate-300"></span><span>Off: {{ $weekStats['off'] }}</span></div>
                    </div>
                </div>
            </div>

            {{-- SHIFT TYPE CARDS --}}
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-base font-bold text-slate-900">Daftar Jenis Shift</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Semua tipe shift yang tersedia dalam sistem</p>
                </div>
                <div class="p-6">
                    @if($shifts->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-4">Belum ada data shift.</p>
                    @else
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach($shifts as $shift)
                                @php
                                    $sn = strtolower($shift->name);
                                    if (str_contains($sn, 'pagi')) {
                                        $icon = '&#x1F305;'; $bg = 'from-sky-50 to-blue-50'; $border = 'border-sky-200'; $txt = 'text-sky-800'; $badge = 'bg-sky-100 text-sky-700'; $bar = 'bg-sky-400';
                                    } elseif (str_contains($sn, 'sore')) {
                                        $icon = '&#x1F307;'; $bg = 'from-violet-50 to-indigo-50'; $border = 'border-violet-200'; $txt = 'text-violet-800'; $badge = 'bg-violet-100 text-violet-700'; $bar = 'bg-violet-400';
                                    } elseif (str_contains($sn, 'malam')) {
                                        $icon = '&#x1F319;'; $bg = 'from-purple-50 to-fuchsia-50'; $border = 'border-purple-200'; $txt = 'text-purple-800'; $badge = 'bg-purple-100 text-purple-700'; $bar = 'bg-purple-500';
                                    } else {
                                        $icon = '&#x23F1;'; $bg = 'from-blue-50 to-slate-50'; $border = 'border-blue-200'; $txt = 'text-blue-800'; $badge = 'bg-blue-100 text-blue-700'; $bar = 'bg-blue-400';
                                    }
                                    $sf = \Carbon\Carbon::parse($shift->start_time);
                                    $ef = \Carbon\Carbon::parse($shift->end_time);
                                    if ($ef < $sf) $ef->addDay();
                                    $hours = $sf->diffInHours($ef);
                                @endphp
                                <div class="rounded-2xl border {{ $border }} bg-gradient-to-br {{ $bg }} p-5 shift-card">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-2xl">{!! $icon !!}</span>
                                            <h4 class="mt-2 text-sm font-bold {{ $txt }}">Shift {{ $shift->name }}</h4>
                                        </div>
                                        <span class="rounded-full {{ $badge }} px-2.5 py-0.5 text-xs font-semibold">{{ $hours }} jam</span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-slate-500">Mulai</span>
                                            <span class="text-sm font-bold {{ $txt }}">{{ $sf->format('H:i') }} WIB</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-slate-500">Selesai</span>
                                            <span class="text-sm font-bold {{ $txt }}">{{ $ef->format('H:i') }} WIB</span>
                                        </div>
                                        <div class="mt-3 h-1.5 rounded-full bg-white/60 overflow-hidden">
                                            <div class="h-full rounded-full {{ $bar }}" style="width: {{ min(($hours / 24) * 100, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </main>
    </div>
</div>

{{-- DETAIL MODAL --}}
<div x-show="detailModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div x-show="detailModal" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="detailModal = false"></div>
        <div x-show="detailModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative z-10 w-full max-w-sm rounded-2xl bg-white shadow-2xl overflow-hidden">
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white font-bold shadow-sm">
                            <span x-text="detailData?.name?.substring(0,2).toUpperCase()"></span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900" x-text="detailData?.name"></h3>
                            <p class="text-xs text-slate-500" x-text="(detailData?.dept ?? '') + ' - ' + (detailData?.role ?? '')"></p>
                        </div>
                    </div>
                    <button @click="detailModal = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Shift</span>
                        <span class="rounded-full bg-blue-100 text-blue-800 px-2.5 py-0.5 text-xs font-bold" x-text="detailData?.shiftName"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jam Kerja</span>
                        <span class="text-sm font-bold text-slate-900" x-text="detailData?.shiftTime"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal</span>
                        <span class="text-xs font-semibold text-slate-700" x-text="detailData?.date"></span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <template x-if="detailData?.isMe">
                        <p class="text-xs text-blue-600 font-semibold">Ini adalah jadwal shift Anda</p>
                    </template>
                </div>
            </div>
            <div class="border-t border-slate-100 bg-slate-50 px-6 py-3 flex justify-end">
                <button @click="detailModal = false" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition-colors">Tutup</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>