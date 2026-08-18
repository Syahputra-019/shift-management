<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shift-Management') }} — Tukar Shift</title>
    <meta name="description" content="Ajukan dan kelola permintaan tukar shift dengan rekan kerja.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
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
                    class="flex items-center justify-between rounded-lg bg-blue-50 px-4 py-2.5 font-semibold text-blue-600 transition-colors">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Tukar Shift</span>
                    </div>
                    @if ($pendingSwapCount > 0)
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
                        <h1 class="text-xl font-bold text-slate-900">Tukar Shift</h1>
                        <p class="hidden text-xs text-slate-500 md:block">Ajukan dan kelola permintaan pertukaran jadwal</p>
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
                        <svg class="h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-5 flex items-center space-x-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 shadow-sm">
                        <svg class="h-5 w-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Hero Banner --}}
                <div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 p-6 text-white shadow-lg shadow-amber-500/20">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
                        <div class="absolute -bottom-8 right-32 h-32 w-32 rounded-full bg-white/5"></div>
                    </div>
                    <div class="relative z-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                        <div>
                            <span class="inline-block rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-md">
                                🔄 Permintaan Tukar Shift
                            </span>
                            <h2 class="mt-2 text-2xl font-extrabold">Kelola Pertukaran Jadwal</h2>
                            <p class="mt-1 text-sm text-amber-100">
                                Ajukan permintaan tukar shift dengan rekan tim dan pantau statusnya secara real-time.
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-center backdrop-blur-md">
                                <p class="text-2xl font-bold">{{ $pendingSwapCount }}</p>
                                <p class="mt-0.5 text-xs text-amber-200">Menunggu Persetujuan</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-center backdrop-blur-md">
                                <p class="text-2xl font-bold">{{ $swapRequests->count() }}</p>
                                <p class="mt-0.5 text-xs text-amber-200">Total Permintaan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">

                    {{-- Left: Form ajukan --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-slate-900">Ajukan Permintaan Tukar Shift</h2>
                            <p class="mt-0.5 text-sm text-slate-500">Permintaan akan dibuat untuk jadwal mendatang Anda.</p>
                        </div>

                        {{-- My upcoming schedule --}}
                        @if ($myUpcomingSchedule)
                            <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-700">Jadwal Anda Berikutnya</p>
                                <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-lg font-bold text-slate-900">{{ $myUpcomingSchedule->shift?->name ?? 'Belum ditentukan' }}</p>
                                        <p class="text-sm text-slate-600">
                                            {{ \Carbon\Carbon::parse($myUpcomingSchedule->date)->translatedFormat('l, d M Y') }}
                                        </p>
                                    </div>
                                    <div class="rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm">
                                        {{ \Carbon\Carbon::parse($myUpcomingSchedule->shift?->start_time)->format('H:i') }}
                                        — {{ \Carbon\Carbon::parse($myUpcomingSchedule->shift?->end_time)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-5 rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Anda belum memiliki jadwal mendatang yang bisa ditukar saat ini.</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('swap-requests.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Tukar Dengan Karyawan</label>
                                <select name="target_user_id" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <option value="">-- Pilih karyawan --</option>
                                    @foreach ($teamSchedules as $emp)
                                        @if ($emp->id !== Auth::id())
                                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->department ?? 'Staff' }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Alasan Pertukaran</label>
                                <textarea name="reason" rows="4" required
                                    placeholder="Jelaskan alasan Anda ingin menukar shift..."
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-md shadow-blue-500/20 transition hover:bg-blue-700 active:scale-[0.98]">
                                Kirim Permintaan Tukar Shift
                            </button>
                        </form>
                    </div>

                    {{-- Right: History --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-5">
                            <h2 class="text-lg font-bold text-slate-900">Riwayat Permintaan</h2>
                            <p class="mt-0.5 text-sm text-slate-500">Status terbaru dari setiap permintaan tukar shift.</p>
                        </div>

                        <div class="space-y-3 max-h-[calc(100vh-22rem)] overflow-y-auto pr-1">
                            @forelse($swapRequests as $req)
                                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:bg-slate-50">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="{{ $req->requester->avatar_color ?? 'bg-blue-600' }} flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-[10px] font-bold text-white">
                                            {{ strtoupper(substr($req->requester->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-900">{{ $req->requester->name }}</span>
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                        <span class="text-sm font-bold text-slate-900">{{ $req->targetUser?->name ?? 'Tim' }}</span>

                                        @if ($req->status === 'pending')
                                            <span class="ml-auto rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Pending</span>
                                        @elseif($req->status === 'approved')
                                            <span class="ml-auto rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">✓ Disetujui</span>
                                        @else
                                            <span class="ml-auto rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">✗ Ditolak</span>
                                        @endif
                                    </div>

                                    <p class="mt-2 text-xs text-slate-500 italic">"{{ $req->reason }}"</p>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ $req->created_at?->diffForHumans() }}</p>

                                    @if ($req->status === 'pending')
                                        <div class="mt-3 flex items-center gap-2">
                                            <form method="POST" action="{{ route('swap-requests.respond', $req->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                                    ✓ Setujui
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('swap-requests.respond', $req->id) }}">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit"
                                                    class="rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-300">
                                                    ✗ Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-slate-400">Belum ada riwayat tukar shift.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>
