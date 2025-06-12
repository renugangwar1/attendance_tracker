<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\UserDetail;
use App\Models\Attendance;
use Carbon\Carbon;
class AttendanceController extends Controller
{
   
public function calendar($userId)
{
    $user = UserDetail::findOrFail($userId);
    $attendances = Attendance::where('user_detail_id', $userId)->get();

    $events = $attendances->map(function ($att) {
        return [
            'title' => ucfirst($att->status),
            'start' => $att->date,
            'color' => match ($att->status) {
                'present' => 'green',
                'absent' => 'red',
                'holiday' => 'blue',
                'overtime' => 'orange',
                default => 'gray',
            },
        ];
    });

    return view('attendance.calendar', [
        'user' => $user,
        'events' => $events
    ]);
}


public function mark(Request $request)
{
    $request->validate([
        'user_detail_id' => 'required|exists:user_details,id',
        'date' => 'required|date',
        'status' => 'required|in:present,absent,holiday,overtime',
    ]);

    Attendance::updateOrCreate(
        ['user_detail_id' => $request->user_detail_id, 'date' => $request->date],
        ['status' => $request->status]
    );

    return response()->json(['message' => 'Attendance marked successfully.']);
}
public function calculateSalary(UserDetail $user)
{
    $base_salary = $user->monthly_salary;
    $working_days = 22; // fixed
    $per_day_salary = $base_salary / $working_days;

    $attendance = Attendance::where('user_detail_id', $user->id)->get();

    $present_days = $attendance->where('status', 'present')->count();
    $overtime_days = $attendance->where('status', 'overtime')->count();

    $total_salary = ($present_days + $overtime_days) * $per_day_salary;

    return view('salary.result', compact('user', 'present_days', 'overtime_days', 'total_salary'));
}
public function showSalary($id)
{
    $user = UserDetail::findOrFail($id);
    $attendances = Attendance::where('user_detail_id', $id)->get()->keyBy('date');

    $monthly_summary = [];

    // Get list of months user has attendance
    $months = $attendances->keys()->map(function ($date) {
        return Carbon::parse($date)->format('Y-m');
    })->unique();

    foreach ($months as $month) {
        $start = Carbon::parse($month . '-01');
        $end = $start->copy()->endOfMonth();

        $working_days = [];
        $days_data = [];
        $present = 0;
        $absent = 0;
        $overtime = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $date_str = $date->toDateString();
            $day_name = $date->format('D');
            $is_weekend = in_array($day_name, ['Sat', 'Sun']);

            $status = $attendances->has($date_str) ? $attendances[$date_str]->status : null;

            // Build days list for display
            $days_data[] = [
                'date' => $date_str,
                'status' => $status ?? 'unmarked',
            ];

            if (!$is_weekend) {
                $working_days[] = $date_str;

                if ($status === 'absent') {
                    $absent++;
                }
                if ($status === 'present') {
                    $present++;
                }
            }

            // Count overtime on any day (weekday or weekend)
            if ($status === 'overtime') {
                $overtime++;
            }
        }

        $working_days_count = count($working_days);
        $per_day_salary = $working_days_count > 0 ? $user->monthly_salary / $working_days_count : 0;

        // Treat unmarked weekdays as present unless absent
        $unmarked_present = $working_days_count - $present - $absent;

        $total_present = $present + $unmarked_present;

        $salary = round(($total_present + $overtime) * $per_day_salary, 2);

        $monthly_summary[$month] = [
            'days' => $days_data,
            'working_days' => $working_days_count,
            'present' => $total_present,
            'absent' => $absent,
            'overtime' => $overtime,
            'per_day_salary' => round($per_day_salary, 2),
            'salary' => $salary,
        ];
    }

    return view('salary.summary', compact('user', 'monthly_summary'));
}



}