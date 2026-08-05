<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
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

        // 2. Create Users
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')]
        );

        $andi = User::firstOrCreate(
            ['email' => 'andi@example.com'],
            ['name' => 'Andi Saputra', 'password' => Hash::make('password')]
        );

        $budi = User::firstOrCreate(
            ['email' => 'budi@example.com'],
            ['name' => 'Budi Santoso', 'password' => Hash::make('password')]
        );

        $citra = User::firstOrCreate(
            ['email' => 'citra@example.com'],
            ['name' => 'Citra Lestari', 'password' => Hash::make('password')]
        );

        // 3. Create Schedules for today
        $today = today()->toDateString();

        Schedule::firstOrCreate(
            ['user_id' => $testUser->id, 'date' => $today],
            ['shift_id' => $shiftPagi->id]
        );

        Schedule::firstOrCreate(
            ['user_id' => $andi->id, 'date' => $today],
            ['shift_id' => $shiftPagi->id]
        );

        Schedule::firstOrCreate(
            ['user_id' => $budi->id, 'date' => $today],
            ['shift_id' => $shiftSore->id]
        );

        Schedule::firstOrCreate(
            ['user_id' => $citra->id, 'date' => $today],
            ['shift_id' => $shiftMalam->id]
        );
    }
}
