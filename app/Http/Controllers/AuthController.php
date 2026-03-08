<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        // center list for registration dropdown; include special 'No center' placeholder handled in view
        $centers = [];
        if (Schema::hasTable('centers')) {
            $centers = \App\Models\Center::orderBy('name')->get();
        }
        return view('auth.register', compact('centers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
        // allow center selection if centers table exists
        if (Schema::hasTable('centers')) {
            $rules['center_id'] = ['nullable', 'integer', 'exists:centers,id'];
        } else {
            $rules['center_id'] = ['nullable'];
        }

        $data = $request->validate($rules);

        $data['password'] = Hash::make($data['password']);
        
        // auto-generate student ID similar to admin flow
        $studentId = null;
        if (empty($data['student_id'])) {
            $firstName = strtolower(explode(' ', $data['name'])[0]);
            $studentCount = User::where('is_admin', false)->count();
            $studentId = $firstName . str_pad($studentCount + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $studentId = $data['student_id'];
        }

        $user = User::create([ 
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'center_id' => $data['center_id'] ?? null,
            'student_id' => $studentId,
            'is_admin' => false,
        ]);
        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
