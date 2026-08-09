<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\ShiftSwapRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with team schedules, statistics, swap requests, and announcements.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Selected date for team schedule view (defaults to today)
        $dateParam = $request->query('date', today()->toDateString());
        try {
            $selectedDate = Carbon::parse($dateParam);
        } catch (\Exception $e) {
            $selectedDate = today();
        }

        $todayStr = today()->toDateString();
        $selectedDateStr = $selectedDate->toDateString();

        // User's next active schedule (today or upcoming)
        $mySchedule = $currentUser ? $currentUser->schedules()
            ->where('date', '>=', $todayStr)
            ->whereNotNull('shift_id')
            ->with('shift')
            ->orderBy('date', 'asc')
            ->first() : null;

        // Team schedules for the selected date
        $teamSchedules = User::with(['schedules' => function ($query) use ($selectedDateStr) {
            $query->where('date', $selectedDateStr)->with('shift');
        }])
        ->orderBy('name')
        ->get();

        // Shifts list
        $shifts = Shift::all();

        // Calculate Shift Counts for selected date
        $stats = [
            'total_employees' => $teamSchedules->count(),
            'on_shift_today' => 0,
            'pagi' => 0,
            'sore' => 0,
            'malam' => 0,
            'off' => 0,
        ];

        foreach ($teamSchedules as $emp) {
            $sched = $emp->schedules->first();
            if ($sched && $sched->shift) {
                $stats['on_shift_today']++;
                $shiftName = strtolower($sched->shift->name);
                if (str_contains($shiftName, 'pagi')) {
                    $stats['pagi']++;
                } elseif (str_contains($shiftName, 'sore')) {
                    $stats['sore']++;
                } elseif (str_contains($shiftName, 'malam')) {
                    $stats['malam']++;
                }
            } else {
                $stats['off']++;
            }
        }

        // Pending shift swap requests
        $swapRequests = ShiftSwapRequest::with([
            'requester',
            'targetUser',
            'schedule.shift',
            'targetSchedule.shift',
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        $pendingSwapCount = $swapRequests->where('status', 'pending')->count();

        // Announcements
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        return view('dashboard', compact(
            'teamSchedules',
            'mySchedule',
            'selectedDate',
            'shifts',
            'stats',
            'swapRequests',
            'pendingSwapCount',
            'announcements'
        ));
    }

    /**
     * Respond to a shift swap request (approve / reject)
     */
    public function swapShiftPage(Request $request)
    {
        $currentUser = auth()->user();

        $dateParam = $request->query('date', today()->toDateString());
        try {
            $selectedDate = Carbon::parse($dateParam);
        } catch (\Exception $e) {
            $selectedDate = today();
        }

        $selectedDateStr = $selectedDate->toDateString();

        $teamSchedules = User::with(['schedules' => function ($query) use ($selectedDateStr) {
            $query->where('date', $selectedDateStr)->with('shift');
        }])
        ->orderBy('name')
        ->get();

        $myUpcomingSchedule = $currentUser ? $currentUser->schedules()
            ->where('date', '>=', today()->toDateString())
            ->whereNotNull('shift_id')
            ->with('shift')
            ->orderBy('date', 'asc')
            ->first() : null;

        $swapRequests = ShiftSwapRequest::with([
            'requester',
            'targetUser',
            'schedule.shift',
            'targetSchedule.shift',
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        $pendingSwapCount = $swapRequests->where('status', 'pending')->count();

        return view('swap-shift', compact('teamSchedules', 'myUpcomingSchedule', 'selectedDate', 'swapRequests', 'pendingSwapCount'));
    }

    public function respondSwapRequest(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $swapRequest = ShiftSwapRequest::findOrFail($id);
        $swapRequest->update([
            'status' => $request->status,
        ]);

        // If approved, swap the shift_ids between schedules
        if ($request->status === 'approved') {
            $schedule1 = $swapRequest->schedule;
            $schedule2 = $swapRequest->targetSchedule;

            if ($schedule1 && $schedule2) {
                $tempShiftId = $schedule1->shift_id;
                $schedule1->update(['shift_id' => $schedule2->shift_id]);
                $schedule2->update(['shift_id' => $tempShiftId]);
            }
        }

        return redirect()->back()->with('success', 'Status permintaan tukar shift berhasil diperbarui.');
    }

    /**
     * Submit a new shift swap request
     */
    public function submitSwapRequest(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:500',
        ]);

        $currentUser = auth()->user();
        $tomorrowStr = today()->addDay()->toDateString();

        $mySchedule = Schedule::where('user_id', $currentUser->id)->where('date', '>=', $tomorrowStr)->first();
        $targetSchedule = Schedule::where('user_id', $request->target_user_id)->where('date', '>=', $tomorrowStr)->first();

        if (!$mySchedule) {
            return redirect()->back()->with('error', 'Anda tidak memiliki jadwal mendatangkan untuk ditukar.');
        }

        ShiftSwapRequest::create([
            'requester_id' => $currentUser->id,
            'target_user_id' => $request->target_user_id,
            'schedule_id' => $mySchedule->id,
            'target_schedule_id' => $targetSchedule?->id,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Permintaan tukar shift berhasil diajukan!');
    }
}
