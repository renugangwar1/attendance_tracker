<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;

class UserDetailController extends Controller
{
    public function index()
    {
        $userEmail = Auth::user()->email;
        $user = UserDetail::where('email', $userEmail)->first();

        return view('users.index', compact('user'));
    }

    public function create()
    {
        $userEmail = Auth::user()->email;
        $user = UserDetail::where('email', $userEmail)->first();

        // If user already submitted details, redirect to show filled details
        if ($user) {
            return view('users.create', compact('user'));
        }

        // Show empty form if no record found
        return view('users.create')->with('user', null);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'monthly_salary' => 'required|numeric',
            'email' => 'required|email|unique:user_details,email',
            'working_days' => 'required|integer|min:1|max:31',
        ]);

        $user = UserDetail::create([
            'name' => $request->name,
            'monthly_salary' => $request->monthly_salary,
            'email' => $request->email,
            'working_days' => $request->working_days,
        ]);

        return redirect()->route('users.create')->with('user', $user);
    }

    public function edit($id)
    {
        $user = UserDetail::findOrFail($id);

        if (Auth::user()->email !== $user->email) {
            abort(403);
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = UserDetail::findOrFail($id);

        if (Auth::user()->email !== $user->email) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string',
            'monthly_salary' => 'required|numeric',
            'email' => 'required|email|unique:user_details,email,' . $user->id,
            'working_days' => 'required|integer|min:1|max:31',
        ]);

        $user->update([
            'name' => $request->name,
            'monthly_salary' => $request->monthly_salary,
            'email' => $request->email,
            'working_days' => $request->working_days,
        ]);

        return redirect()->route('users.create')->with('user', $user);
    }
}
