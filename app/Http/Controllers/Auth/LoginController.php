<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ── Show login form ──────────────────────────────────────────────
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }

        return view('auth.login');
    }

    // ── Handle login ─────────────────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            if ($user->isTeacher()) {
                return redirect()->intended(route('teacher.dashboard'));
            }
            if ($user->isStudent()) {
                return redirect()->intended(route('student.dashboard'));
            }

            return redirect()->intended(route('welcome'));
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    // ── Handle logout ────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
