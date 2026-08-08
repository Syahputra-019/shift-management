<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display the shift schedule page with weekly/monthly calendar view.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Determine the week to display (default: current week Monday)
        $weekParam = $request->query('week');
        try {
            $weekStart = $weekParam
                ? Carbon::parse($weekParam)->startOfWeek(Carbon::MONDAY)
                : Carbon::now()->startOfWeek(Carbon::MONDAY);
        } catch (\Exception $e) {
            $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        }

        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        // Generate array of 7 days in the selected week
        $weekDays = collect();
        for ($i = 0; $i < 7; $i++) {
            $weekDays->push($weekStart->copy()->addDays($i));
        }

        // Fetch all users with their schedules for this week
        $users = User::with(['schedules' => function ($query) use ($weekStart, $weekEnd) {
            $query->whereBetween('date', [
                $weekStart->toDateString(),
                $weekEnd->toDateString(),
            ])->with('shift');
        }])->orderBy('name')->get();

        // Fetch all shifts
        $shifts = Shift::all();

        // Build a lookup: user_id -> date -> schedule
        $scheduleMatrix = [];
        foreach ($users as $user) {
            foreach ($user->schedules as $schedule) {
                $dateKey = $schedule->date->toDateString();
                $scheduleMatrix[$user->id][$dateKey] = $schedule;
            }
        }

        // Stats for the current week
        $weekStats = [
            'total_schedules' => 0,
            'pagi'  => 0,
            'sore'  => 0,
            'malam' => 0,
            'off'   => 0,
        ];

        foreach ($users as $user) {
            foreach ($weekDays as $day) {
                $dayKey = $day->toDateString();
                $sched  = $scheduleMatrix[$user->id][$dayKey] ?? null;
                if ($sched && $sched->shift) {
                    $weekStats['total_schedules']++;
                    $name = strtolower($sched->shift->name);
                    if (str_contains($name, 'pagi')) {
                        $weekStats['pagi']++;
                    } elseif (str_contains($name, 'sore')) {
                        $weekStats['sore']++;
                    } elseif (str_contains($name, 'malam')) {
                        $weekStats['malam']++;
                    }
                } else {
                    $weekStats['off']++;
                }
            }
        }

        // My schedules for this week (logged in user)
        $myWeekSchedules = [];
        if ($currentUser) {
            foreach ($weekDays as $day) {
                $dayKey = $day->toDateString();
                $myWeekSchedules[$dayKey] = $scheduleMatrix[$currentUser->id][$dayKey] ?? null;
            }
        }

        return view('schedule', compact(
            'users',
            'shifts',
            'weekStart',
            'weekEnd',
            'weekDays',
            'scheduleMatrix',
            'weekStats',
            'myWeekSchedules'
        ));
    }
}
