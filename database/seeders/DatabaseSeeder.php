<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftSwapRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Shifts
        $shiftPagi = Shift::firstOrCreate(
            ['name' => 'Pagi'],
            ['start_time' => '08:00:00', 'end_time' => '16:00:00']
        );

        $shiftSore = Shift::firstOrCreate(
            ['name' => 'Sore'],
            ['start_time' => '16:00:00', 'end_time' => '00:00:00']
        );

        $shiftMalam = Shift::firstOrCreate(
            ['name' => 'Malam'],
            ['start_time' => '00:00:00', 'end_time' => '08:00:00']
        );

        $shifts = [$shiftPagi, $shiftSore, $shiftMalam];

        // 2. Create Users with realistic details
        $usersData = [
            [
                'email' => 'admin@example.com',
                'name' => 'Admin Utama',
                'department' => 'Management',
                'role' => 'Admin',
                'avatar_color' => 'bg-indigo-600',
            ],
            [
                'email' => 'test@example.com',
                'name' => 'Test User',
                'department' => 'Operational',
                'role' => 'Team Lead',
                'avatar_color' => 'bg-blue-600',
            ],
            [
                'email' => 'andi@example.com',
                'name' => 'Andi Saputra',
                'department' => 'Operational',
                'role' => 'Supervisor',
                'avatar_color' => 'bg-emerald-600',
            ],
            [
                'email' => 'budi@example.com',
                'name' => 'Budi Santoso',
                'department' => 'Customer Support',
                'role' => 'Senior Specialist',
                'avatar_color' => 'bg-purple-600',
            ],
            [
                'email' => 'citra@example.com',
                'name' => 'Citra Lestari',
                'department' => 'Operational',
                'role' => 'Staff',
                'avatar_color' => 'bg-pink-600',
            ],
            [
                'email' => 'dewi@example.com',
                'name' => 'Dewi Anggraini',
                'department' => 'Customer Support',
                'role' => 'Staff Support',
                'avatar_color' => 'bg-amber-600',
            ],
            [
                'email' => 'eko@example.com',
                'name' => 'Eko Prasetyo',
                'department' => 'IT Tech',
                'role' => 'System Operator',
                'avatar_color' => 'bg-cyan-600',
            ],
            [
                'email' => 'fajar@example.com',
                'name' => 'Fajar Nugraha',
                'department' => 'Logistics',
                'role' => 'Warehouse Staff',
                'avatar_color' => 'bg-teal-600',
            ],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'department' => $data['department'],
                    'role' => $data['role'],
                    'avatar_color' => $data['avatar_color'],
                ]
            );
            $users[] = $user;
        }

        // 3. Generate Schedules spanning from H-7 to H+14
        $startDate = today()->subDays(7);
        $endDate = today()->addDays(14);

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->toDateString();
            $dayIndex = (int) $currentDate->format('z'); // day of year

            foreach ($users as $index => $user) {
                // Rotating shift logic based on user index and date
                $shiftIndex = ($index + $dayIndex) % 4;

                if ($shiftIndex < 3) {
                    Schedule::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $dateStr],
                        ['shift_id' => $shifts[$shiftIndex]->id]
                    );
                } else {
                    // Off / Libur day (no shift_id)
                    Schedule::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $dateStr],
                        ['shift_id' => null]
                    );
                }
            }

            $currentDate->addDay();
        }

        // 4. Create Shift Swap Requests for demonstration
        $todayStr = today()->toDateString();
        $tomorrowStr = today()->addDay()->toDateString();

        $scheduleAndi = Schedule::where('user_id', $users[2]->id)->where('date', $tomorrowStr)->first();
        $scheduleBudi = Schedule::where('user_id', $users[3]->id)->where('date', $tomorrowStr)->first();
        $scheduleCitra = Schedule::where('user_id', $users[4]->id)->where('date', $todayStr)->first();
        $scheduleEko = Schedule::where('user_id', $users[6]->id)->where('date', $todayStr)->first();

        if ($scheduleAndi) {
            ShiftSwapRequest::firstOrCreate(
                ['requester_id' => $users[2]->id, 'schedule_id' => $scheduleAndi->id],
                [
                    'target_user_id' => $users[3]->id,
                    'target_schedule_id' => $scheduleBudi?->id,
                    'status' => 'pending',
                    'reason' => 'Ada urusan keluarga mendadak besok pagi.',
                ]
            );
        }

        if ($scheduleCitra) {
            ShiftSwapRequest::firstOrCreate(
                ['requester_id' => $users[4]->id, 'schedule_id' => $scheduleCitra->id],
                [
                    'target_user_id' => $users[6]->id,
                    'target_schedule_id' => $scheduleEko?->id,
                    'status' => 'approved',
                    'reason' => 'Tukar shift untuk menyesuaikan jadwal kuliah.',
                ]
            );
        }

        // 5. Create Announcements
        Announcement::firstOrCreate(
            ['title' => 'Rapat Tim Mingguan & Evaluasi SOP'],
            [
                'content' => 'Seluruh supervisor dan staff diharapkan menghadiri rapat evaluasi bulanan di Ruang Meeting Utama atau via Google Meet.',
                'badge_type' => 'Penting',
                'author_name' => 'Management',
                'time_schedule' => 'Besok, pukul 10:00 WIB',
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'Pembaruan Jadwal Shift Bulan Ini'],
            [
                'content' => 'Jadwal shift operasional telah diperbarui. Silakan periksa jadwal masing-masing dan ajukan tukar shift sebelum H-1.',
                'badge_type' => 'Info',
                'author_name' => 'HRD Department',
                'time_schedule' => 'Hari ini, pukul 14:00 WIB',
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'Gathering Kebersamaan Tim Operational'],
            [
                'content' => 'Acara kebersamaan dan ramah tamah antar tim akan diadakan akhir pekan ini. Makanan dan refreshment disediakan.',
                'badge_type' => 'Kegiatan',
                'author_name' => 'Panitia Internal',
                'time_schedule' => 'Sabtu ini, pukul 16:00 WIB',
            ]
        );
    }
}
