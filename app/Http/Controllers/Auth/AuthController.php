<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
    $data = $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email',
        'phone'       => 'nullable|string|max:20',
        'address'     => 'nullable|string|max:255',
        'occupation'  => 'nullable|string|max:100',
        'home_type'   => 'nullable|string|in:house,apartment,condo,other',
        'password'    => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name'       => $data['name'],
        'email'      => $data['email'],
        'password'   => bcrypt($data['password']),
        'role'       => 'adopter',
        'phone'      => $data['phone'] ?? null,
        'address'    => $data['address'] ?? null,
        'occupation' => $data['occupation'] ?? null,
        'home_type'  => $data['home_type'] ?? null,
    ]);

    Auth::login($user);

    return redirect('/dashboard')
        ->with('success', 'Welcome to PAWsome Pets, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}