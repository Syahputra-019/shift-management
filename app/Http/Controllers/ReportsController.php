<?php

namespace App\Http\Controllers;

use App\Models\ShiftSwapRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display the reports page with monthly shift data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year'  => 'nullable|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $month = $request->input('month', Carbon::now()->month);
        $year  = $request->input('year', Carbon::now()->year);

        $selectedDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth  = $selectedDate->daysInMonth;
        $startDate    = $selectedDate->copy()->startOfMonth();
        $endDate      = $selectedDate->copy()->endOfMonth();

        // Pending swap count for sidebar badge
        $pendingSwapCount = ShiftSwapRequest::where('status', 'pending')->count();

        // Get all employees with their schedules for the selected month
        $employees = User::with(['schedules' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->with('shift');
        }])->orderBy('name')->get();

        // Process schedules per employee
        $reportData = $employees->map(function ($employee) use ($daysInMonth) {
            $schedulesByDay = [];
            $shiftCounts    = [
                'Pagi'  => 0,
                'Sore'  => 0,
                'Malam' => 0,
                'Off'   => 0,
            ];

            $scheduleMap = $employee->schedules->keyBy(function ($schedule) {
                return Carbon::parse($schedule->date)->day;
            });

            for ($day = 1; $day <= $daysInMonth; $day++) {
                if (isset($scheduleMap[$day]) && $scheduleMap[$day]->shift) {
                    $shiftName              = $scheduleMap[$day]->shift->name;
                    $schedulesByDay[$day]   = $shiftName;
                    if (preg_match('/pagi/i', $shiftName)) $shiftCounts['Pagi']++;
                    elseif (preg_match('/sore/i', $shiftName)) $shiftCounts['Sore']++;
                    elseif (preg_match('/malam/i', $shiftName)) $shiftCounts['Malam']++;
                } else {
                    $schedulesByDay[$day] = 'Off';
                    $shiftCounts['Off']++;
                }
            }

            $employee->schedules_by_day = $schedulesByDay;
            $employee->shift_counts     = $shiftCounts;
            return $employee;
        });

        return view('reports.index', compact(
            'reportData',
            'selectedDate',
            'daysInMonth',
            'pendingSwapCount'
        ));
    }
}