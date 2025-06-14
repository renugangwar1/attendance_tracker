<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\UserDetail;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

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
public function unmark(Request $request)
{
    $request->validate([
        'user_detail_id' => 'required|exists:user_details,id',
        'date' => 'required|date',
    ]);

    Attendance::where('user_detail_id', $request->user_detail_id)
        ->where('date', $request->date)
        ->delete();

    return response()->json(['message' => 'Attendance removed successfully.']);
}


private function computeSalary(UserDetail $user, Collection $attendances, string $month)
{
    $start = Carbon::parse($month . '-01');
    $end = $start->copy()->endOfMonth();

   $fixed_working_days = $user->working_days; 
    $days_data = [];
    $present = 0;
    $absent = 0;
    $overtime = 0;

    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
        $date_str = $date->toDateString();
        $status = $attendances->has($date_str) ? $attendances[$date_str]->status : null;

        $days_data[] = [
            'date' => $date_str,
            'status' => $status ?? 'unmarked',
        ];

        if ($status === 'present') $present++;
        if ($status === 'absent') $absent++;
        if ($status === 'overtime') $overtime++;
    }

    // Calculate per-day salary based on fixed 22 working days
    $per_day_salary = $user->monthly_salary / $fixed_working_days;

    // Assume unmarked days (from 22) are present unless marked absent
    $marked_days = $present + $absent;
    $unmarked_days = max(0, $fixed_working_days - $marked_days);
    $total_present = $present + $unmarked_days;

    // Final salary calculation
    $salary = round(($total_present + $overtime) * $per_day_salary, 2);

    return [
        'days' => $days_data,
        'working_days' => $fixed_working_days,
        'present' => $total_present,
        'absent' => $absent,
        'overtime' => $overtime,
        'per_day_salary' => round($per_day_salary, 2),
        'salary' => $salary,
    ];
}
public function calculateSalary(UserDetail $user, Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));
    $attendances = Attendance::where('user_detail_id', $user->id)->get()->keyBy('date');

    $data = $this->computeSalary($user, $attendances, $month);
    $month_name = Carbon::parse($month . '-01')->format('F Y');

    return view('salary.result', compact('user', 'data', 'month_name'));
}

public function showSalary($id)
{
    $user = UserDetail::findOrFail($id);
    $attendances = Attendance::where('user_detail_id', $id)->get()->keyBy('date');

    $months = $attendances->keys()->map(function ($date) {
        return Carbon::parse($date)->format('Y-m');
    })->unique();

    $monthly_summary = [];

    foreach ($months as $month) {
        $monthly_summary[$month] = $this->computeSalary($user, $attendances, $month);
    }

    return view('salary.summary', compact('user', 'monthly_summary'));
}


public function download(Request $request, $id)
{
    $user = UserDetail::findOrFail($id); // ✅ corrected model
    $month = $request->input('month', now()->format('Y-m'));

    $attendances = Attendance::where('user_detail_id', $user->id)->get()->keyBy('date');
    $data = $this->computeSalary($user, $attendances, $month);

    $pdf = Pdf::loadView('pdf.salary', compact('user', 'data', 'month'));
    return $pdf->download('Salary-Summary-' . $user->name . '-' . $month . '.pdf');
}



}