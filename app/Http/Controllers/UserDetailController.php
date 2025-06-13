<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
class UserDetailController extends Controller
{
    
// public function index()
// {
//     $users = UserDetail::all();
//     return view('users.index', compact('users'));
// }



public function index()
{
    $userEmail = Auth::user()->email;
    $user = UserDetail::where('email', $userEmail)->first();

    return view('users.index', compact('user'));
}


public function create()
{
    return view('users.create');
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'monthly_salary' => 'required|numeric',
        'email' => 'required|email|unique:user_details,email',
    ]);

    UserDetail::create([
        'name' => $request->name,
        'monthly_salary' => $request->monthly_salary,
        'email' => $request->email,
    ]);

    return redirect()->route('users.index');
}


}
