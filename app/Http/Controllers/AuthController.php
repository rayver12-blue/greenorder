<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    // ── Login ─────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectAuthenticated();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Rate limiting — brute-force protection
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'throttle' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->withInput($request->only('login'));
        }

        $request->validate([
            'login'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $loginValue = $request->input('login');

        // Support login by email OR username
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user  = User::where($field, $loginValue)->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($key, 60);
            return back()->withErrors([
                'login' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('login'));
        }

        RateLimiter::clear($key);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate(); // session fixation protection
        LoginLog::create([
            'user_id'      => $user->id,
            'role'         => $user->role,
            'ip_address'   => $request->ip(),
            'user_agent'   => substr((string) $request->userAgent(), 0, 255),
            'logged_in_at' => now(),
        ]);

        return $this->redirectAuthenticated();
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectAuthenticated();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'username'  => ['required', 'string', 'min:3', 'max:30', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'name'      => ['required', 'string', 'min:2', 'max:100'],
            'email'     => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'birthdate' => ['required', 'date', 'before:' . now()->subYears(13)->toDateString()],
            'mobile'    => ['required', 'string', 'regex:/^(09|\+639)\d{9}$/', 'unique:users,mobile'],
            'role'      => ['required', 'in:user,admin'],
            'password'  => [
                'required',
                'confirmed',
                PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ];

        if ($request->input('role') === 'admin') {
            $rules['admin_key'] = ['required', 'string'];
        }

        $validated = $request->validate($rules, [
            'username.regex'   => 'Username may only contain letters, numbers, and underscores.',
            'mobile.regex'     => 'Enter a valid Philippine mobile number (e.g. 09XXXXXXXXX).',
            'birthdate.before' => 'You must be at least 13 years old to register.',
        ]);

        // Verify admin key server-side
        if ($validated['role'] === 'admin') {
            if ($request->input('admin_key') !== config('app.admin_key', 'GREENORDER_ADMIN_2024')) {
                return back()->withErrors(['admin_key' => 'Invalid admin authorization key.'])->withInput();
            }
        }

        $user = User::create([
            'username'  => $validated['username'],
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'birthdate' => $validated['birthdate'],
            'mobile'    => $validated['mobile'],
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectAuthenticated();
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('success', 'You have been signed out successfully.');
    }

    // ── Forgot Password ───────────────────────────────────────────────────────

    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email'        => ['required', 'email'],
            'new_password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'No account found for that email address.'])
                ->withInput();
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()
                ->withErrors(['new_password' => 'New password must be different from your current password.'])
                ->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($request->new_password),
        ])->setRememberToken(Str::random(60));
        $user->save();

        return redirect()
            ->route('auth.login')
            ->with('success', 'Password updated successfully. You can now sign in.');
    }

    public function showReset(Request $request, string $token)
    {
        return view('auth.reset', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('auth.login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    protected function redirectAuthenticated()
    {
        $user = Auth::user();
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('customer.home');
    }
}
