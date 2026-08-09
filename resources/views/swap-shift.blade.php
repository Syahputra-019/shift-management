<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Shift-Management') }} - Tukar Shift</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800">
    <div class="bg-linear-to-br min-h-screen from-blue-50 via-white to-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <div
                class="mb-6 flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <span
                            class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Tukar
                            Shift</span>
                        <span
                            class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">{{ $pendingSwapCount }}
                            menunggu</span>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900">Kelola permintaan tukar shift Anda</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-600">Ajukan pertukaran jadwal dengan rekan tim, lihat
                        status permintaan, dan pantau perkembangan persetujuan dengan lebih jelas.</p>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <div class="mb-6 grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Jadwal Anda yang siap ditukar</h2>
                            <p class="text-sm text-slate-500">Permintaan akan dibuat untuk jadwal mendatang Anda.</p>
                        </div>
                    </div>

                    @if ($myUpcomingSchedule)
                        <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Jadwal
                                        sekarang</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">
                                        {{ $myUpcomingSchedule->shift?->name ?? 'Belum ditentukan' }}</p>
                                    <p class="text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($myUpcomingSchedule->date)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                                <div
                                    class="rounded-2xl bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm">
                                    {{ \Carbon\Carbon::parse($myUpcomingSchedule->shift?->start_time)->format('H:i') }}
                                    - {{ \Carbon\Carbon::parse($myUpcomingSchedule->shift?->end_time)->format('H:i') }}
                                    WIB
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                            Anda belum memiliki jadwal mendatang yang bisa ditukar saat ini.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('swap-requests.store') }}" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Pilih rekan kerja</label>
                            <select name="target_user_id" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="">-- Pilih karyawan --</option>
                                @foreach ($teamSchedules as $emp)
                                    @if ($emp->id !== Auth::id())
                                        <option value="{{ $emp->id }}">{{ $emp->name }}
                                            ({{ $emp->department ?? 'Staff' }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-slate-700">Alasan pertukaran</label>
                            <textarea name="reason" rows="4" required placeholder="Ceritakan alasan Anda ingin menukar shift..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Kirim Permintaan Tukar Shift
                        </button>
                    </form>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Riwayat permintaan</h2>
                            <p class="text-sm text-slate-500">Status terbaru dari setiap permintaan tukar shift.</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @forelse($swapRequests as $req)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-slate-900">{{ $req->requester->name }}</span>
                                    <span class="text-slate-400">→</span>
                                    <span
                                        class="font-semibold text-slate-900">{{ $req->targetUser?->name ?? 'Tim' }}</span>
                                    @if ($req->status === 'pending')
                                        <span
                                            class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span
                                            class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Approved</span>
                                    @else
                                        <span
                                            class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">Rejected</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ $req->reason }}</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if ($req->status === 'pending')
                                        <form method="POST" action="{{ route('swap-requests.respond', $req->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit"
                                                class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('swap-requests.respond', $req->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit"
                                                class="rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                                Belum ada riwayat tukar shift.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
