<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            if (Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Admin accounts must sign in from the admin login page.']);
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            if (!Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Use the user/contributor login page for this account.']);
            }

            return redirect()->intended(route('admin.users'));
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function redirectToGoogle()
    {
        if (blank(Config::get('services.google.client_id')) || blank(Config::get('services.google.client_secret'))) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Google sign-in is not configured yet. Please add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $exception) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Google sign-in failed. Please try again.']);
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        if (!$email || !$googleId) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Unable to fetch your Google account details.']);
        }

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            if (!$user->is_active) {
                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'Your account has been deactivated.']);
            }

            if ($user->isAdmin()) {
                return redirect()
                    ->route('admin.login')
                    ->withErrors(['email' => 'Admin accounts must sign in from the admin login page.']);
            }

            if (!$user->google_id) {
                $user->google_id = $googleId;
                $user->save();
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Google User',
                'email' => $email,
                'password' => Hash::make(Str::password(32)),
                'role' => 'viewer',
                'google_id' => $googleId,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,contributor',
        ]);

        // Keep compatibility with DB enum values while exposing business-friendly labels in UI.
        $storedRole = $validated['role'] === 'contributor' ? 'contributor' : 'viewer';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $storedRole,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
