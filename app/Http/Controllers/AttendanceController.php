<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\UserDetail;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Salary;
use Illuminate\Support\Str;

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


// public function computeSalary(UserDetail $user, Collection $attendances, string $month)
// {
//     $start = Carbon::parse($month . '-01');
//     $end = $start->copy()->endOfMonth();

//    $fixed_working_days = $user->working_days; 
//     $days_data = [];
//     $present = 0;
//     $absent = 0;
//     $overtime = 0;

//     for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
//         $date_str = $date->toDateString();
//         $status = $attendances->has($date_str) ? $attendances[$date_str]->status : null;

//         $days_data[] = [
//             'date' => $date_str,
//             'status' => $status ?? 'unmarked',
//         ];

//         if ($status === 'present') $present++;
//         if ($status === 'absent') $absent++;
//         if ($status === 'overtime') $overtime++;
//     }

//     // Calculate per-day salary based on fixed 22 working days
//     $per_day_salary = $user->monthly_salary / $fixed_working_days;

//     // Assume unmarked days (from 22) are present unless marked absent
//     $marked_days = $present + $absent;
//     $unmarked_days = max(0, $fixed_working_days - $marked_days);
//     $total_present = $present + $unmarked_days;

//     // Final salary calculation
//     $salary = round(($total_present + $overtime) * $per_day_salary, 2);

//     return [
//         'days' => $days_data,
//         'working_days' => $fixed_working_days,
//         'present' => $total_present,
//         'absent' => $absent,
//         'overtime' => $overtime,
//         'per_day_salary' => round($per_day_salary, 2),
//         'salary' => $salary,
//     ];
// }

public function computeSalary(UserDetail $user, Collection $attendances, string $month)
{
    $start = Carbon::parse($month . '-01');
    $end = $start->copy()->endOfMonth();

    $fixed_working_days = $user->working_days;
    $pf_amount = 1800; // You can also fetch this dynamically if needed

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

    // Base calculations
    $total_salary = $user->monthly_salary; // e.g., 30000
    $net_salary = $total_salary - $pf_amount; // 28200

    $per_day_net = $net_salary / $fixed_working_days;      // for present
    $per_day_full = $total_salary / $fixed_working_days;   // for overtime

    $marked_days = $present + $absent;
    $unmarked_days = max(0, $fixed_working_days - $marked_days);
    $total_present = $present + $unmarked_days;

    // Final calculation
    $salary = round(($total_present * $per_day_net) + ($overtime * $per_day_full), 2);

    return [
        'days' => $days_data,
        'working_days' => $fixed_working_days,
        'present' => $total_present,
        'absent' => $absent,
        'overtime' => $overtime,
        'per_day_net_salary' => round($per_day_net, 2),
        'per_day_full_salary' => round($per_day_full, 2),
        'pf_amount' => $pf_amount,
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

    // foreach ($months as $month) {
    //     $monthly_summary[$month] = $this->computeSalary($user, $attendances, $month);
    // }

    
    foreach ($months as $month) {
    $salary = Salary::where('user_id', $user->id)
                    ->where('month', Carbon::parse($month . '-01')->format('F Y'))
                    ->first();

    $monthly_summary[$month] = array_merge(
        $this->computeSalary($user, $attendances, $month),
        ['is_credited' => optional($salary)->is_credited]
    );
    }
    return view('salary.summary', compact('user', 'monthly_summary'));
}


public function download(Request $request, $id)
{
    $user = UserDetail::findOrFail($id); 
    $month = $request->input('month', now()->format('Y-m'));

    $attendances = Attendance::where('user_detail_id', $user->id)->get()->keyBy('date');
    $data = $this->computeSalary($user, $attendances, $month);

    $pdf = Pdf::loadView('pdf.salary', compact('user', 'data', 'month'));
    return $pdf->download('Salary-Summary-' . $user->name . '-' . $month . '.pdf');
}



public function toggleCredit(Request $request, UserDetail $user)
{
    $month = $request->input('month');
    $credited_salary = $request->input('credited_salary');

    $month_name = Carbon::parse($month . '-01')->format('F Y');

    $salary = Salary::firstOrNew([
        'user_id' => $user->id,
        'month' => $month_name,
    ]);

    if ($salary->exists && $salary->is_credited) {
        return back()->with('message', 'Salary already credited.');
    }

    $salary->credited_salary = $credited_salary;
    $salary->is_credited = true;
    $salary->save();

    return back()->with('message', 'Salary credited successfully.');
}



}