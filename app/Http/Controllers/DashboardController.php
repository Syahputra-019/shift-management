<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with today's team schedule.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $currentUser = auth()->user();
        $todayStr = today()->toDateString();

        $mySchedule = $currentUser ? $currentUser->schedules()->where('date', '>=', $todayStr)->with('shift')->orderBy('date', 'asc')->first() : null;

        // Eager load users with their schedule for today.
        // The schedule relationship will also eager load the shift details.
        $teamSchedules = User::with(['schedules' => function ($query) use ($todayStr) {
            $query->where('date', $todayStr)->with('shift');
        }])
        ->orderBy('name') // Sort users by name
        ->get();

        return view('dashboard', compact('teamSchedules', 'mySchedule'));
    }
}
