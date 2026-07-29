<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Shift-Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 shrink-0 border-r border-gray-200 bg-white transition-all duration-300"
            :class="{ '-ml-64': !sidebarOpen }">
            <div class="flex h-16 items-center justify-center border-b">
                <a href="/" class="text-xl font-bold text-blue-600">ShiftManager</a>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center bg-gray-200 px-6 py-3 font-semibold text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="#"
                    class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:font-semibold">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="ml-3">Jadwal Shift</span>
                </a>
                <a href="#"
                    class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:font-semibold">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A6.995 6.995 0 0012 12.75a6.995 6.995 0 00-3-5.197M15 21a9 9 0 00-9-9">
                        </path>
                    </svg>
                    <span class="ml-3">Karyawan</span>
                </a>
                <a href="#"
                    class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 hover:font-semibold">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <span class="ml-3">Laporan</span>
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="flex items-center justify-between border-b bg-white p-4">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <h2 class="ml-4 text-xl font-semibold text-gray-700">Dashboard</h2>
                </div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative flex items-center space-x-2 focus:outline-none">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute right-0 z-10 mt-2 w-48 overflow-hidden rounded-md bg-white shadow-xl"
                        style="display: none;">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-white">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-blue-500 hover:text-white">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content area -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-100 p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Widget 1: Upcoming Shift -->
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="flex items-center">
                            <div class="rounded-full bg-blue-100 p-3 text-blue-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-500">Jadwal Anda Berikutnya</h3>
                                <p class="text-2xl font-bold text-gray-800">08:00 - 16:00</p>
                                <p class="text-sm text-gray-500">Hari ini, Shift Pagi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 2: Shift Requests -->
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="flex items-center">
                            <div class="rounded-full bg-green-100 p-3 text-green-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-500">Permintaan Tukar Shift</h3>
                                <p class="text-2xl font-bold text-gray-800">2 <span
                                        class="text-base font-normal">Menunggu</span></p>
                                <a href="#" class="mt-1 text-sm text-blue-500 hover:underline">Lihat Detail</a>
                            </div>
                        </div>
                    </div>

                    <!-- Widget 3: Announcements -->
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="flex items-center">
                            <div class="rounded-full bg-orange-100 p-3 text-orange-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.518">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-500">Pengumuman</h3>
                                <p class="text-lg font-semibold text-gray-800">Rapat tim mingguan</p>
                                <p class="text-sm text-gray-500">Besok, pukul 10:00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table: Team Schedule -->
                <div class="mt-8 rounded-lg bg-white shadow-sm">
                    <div class="border-b p-4">
                        <h3 class="text-lg font-semibold text-gray-800">Jadwal Tim Hari Ini</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Karyawan</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Shift</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Waktu</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Andi
                                        Saputra</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Pagi</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">08:00 - 16:00</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">On
                                            Shift</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Budi
                                        Santoso</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Pagi</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">08:00 - 16:00</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">On
                                            Shift</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Citra
                                        Lestari</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Sore</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">16:00 - 00:00</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span
                                            class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800">Upcoming</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Dewi
                                        Anggraini</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">-</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">-</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">Off</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
