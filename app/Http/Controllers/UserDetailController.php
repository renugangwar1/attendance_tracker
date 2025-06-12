<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDetail;

class UserDetailController extends Controller
{
    
public function index()
{
    $users = UserDetail::all();
    return view('users.index', compact('users'));
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
    ]);

   UserDetail::create([
    'name' => $request->name,
    'monthly_salary' => $request->monthly_salary,
]);

    return redirect()->route('users.index');
}

}
