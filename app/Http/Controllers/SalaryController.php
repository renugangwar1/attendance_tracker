<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\UserDetail;
use App\Models\Expense;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
 public function index()
{
    $user = auth()->user();

    // Get all salaries for the user, grouped by month (like "June 2025")
    $salaries = Salary::with('user')
        ->where('user_id', $user->id)
        ->orderByDesc('month')
        ->get()
        ->groupBy('month'); // Grouping as 'June 2025', etc.

    // Compute salary for current month
    $attendanceController = new AttendanceController();
    $month = now()->format('Y-m'); // 2025-06

    $userDetail = UserDetail::where('email', $user->email)->first();
    $attendances = Attendance::where('user_detail_id', $userDetail->id)->get()->keyBy('date');

    $calculatedSalaryData = $attendanceController->computeSalary($userDetail, $attendances, $month);
    $formattedMonth = Carbon::parse($month . '-01')->format('F Y');

    $salary = Salary::where('user_id', $user->id)
                    ->where('month', $formattedMonth)
                    ->first();

    $calculatedSalaryData['is_credited'] = $salary ? $salary->is_credited : false;

    return view('salary.index', [
        'salaries' => $salaries, // grouped salaries
        'calculatedSalaryData' => $calculatedSalaryData,
        'user' => $user,
    ]);
}


  


    public function manage($id)
    {
        $salary = Salary::with(['expenses', 'user'])->findOrFail($id);

        // Convert month like "May 2025" into "2025-05"
        $month = Carbon::parse('01 ' . $salary->month)->format('Y-m');

        // Compute attendance-based salary
        $attendanceController = new AttendanceController();
        $attendances = Attendance::where('user_detail_id', $salary->user_id)->get()->keyBy('date');
        $attendanceSalaryData = $attendanceController->computeSalary($salary->user, $attendances, $month);

        // Ensure net salary is always calculated
        $salary->net_salary = $salary->credited_salary - $salary->total_expenses;

        return view('salary.management', compact('salary', 'attendanceSalaryData', 'month'));
    }

 public function addExpense(Request $request, $salaryId)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'amount' => 'required|numeric',
    ]);

    Expense::create([
        'salary_id' => $salaryId,
        'title' => $request->title,
        'amount' => $request->amount,
    ]);

    return back()->with('success', 'Expense added successfully.');
}



    public function deleteExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $salary = Salary::findOrFail($expense->salary_id);

        $salary->total_expenses -= $expense->amount;
        $salary->net_salary = $salary->credited_salary - $salary->total_expenses;
        $salary->save();

        $expense->delete();

        return redirect()->route('salary.manage', $salary->id)->with('success', 'Expense deleted.');
    }







}
