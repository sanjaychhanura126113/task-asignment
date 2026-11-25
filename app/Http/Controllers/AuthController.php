<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the login page with demo account shortcuts.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('member.dashboard');
        }

        $demoUsers = User::orderBy('role', 'asc')->take(5)->get();

        return view('auth.login', compact('demoUsers'));
    }

    /**
     * Process authentication credentials.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Please enter your registered email address.',
            'email.email' => 'Please provide a valid email format (e.g. user@example.com).',
            'password.required' => 'Password is required to authenticate.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $greeting = $user->isAdmin() ? 'Welcome back, Administrator!' : 'Welcome back, ' . $user->name . '!';

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', $greeting);
            }

            return redirect()->intended(route('member.dashboard'))->with('success', $greeting);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Instant 1-Click login helper for testing / presentation.
     */
    public function quickLogin($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);
        request()->session()->regenerate();

        $message = "Logged in successfully as {$user->name} (" . ucfirst($user->role) . ")";

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')->with('success', $message)
            : redirect()->route('member.dashboard')->with('success', $message);
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('member.dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:member,admin'],
            'department' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Full Name is required.',
            'name.min' => 'Full Name must be at least 3 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department'] ?? 'General',
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $message = "Account created successfully! Welcome to TaskFlow, {$user->name}.";

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')->with('success', $message)
            : redirect()->route('member.dashboard')->with('success', $message);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been safely logged out.');
    }
}
