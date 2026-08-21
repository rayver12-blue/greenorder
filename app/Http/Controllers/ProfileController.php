<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\OrderAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ── Admin profile ────────────────────────────────────────────────────────
    public function adminProfile()
    {
        $auditLogs = OrderAudit::with(['user', 'actor', 'order'])
            ->latest()
            ->get();

        $loginLogs = LoginLog::with('user')
            ->where('user_id', Auth::id())
            ->latest('logged_in_at')
            ->limit(30)
            ->get();

        return view('admin.profile', compact('auditLogs', 'loginLogs'));
    }

    // ── Customer profile ─────────────────────────────────────────────────────
    public function customerProfile()
    {
        $auditLogs = OrderAudit::where('user_id', Auth::id())
            ->latest()
            ->limit(15)
            ->get();

        return view('customer.profile', compact('auditLogs'));
    }

    // ── Change password (shared for both roles) ───────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'new_password'          => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Your current password is incorrect.'])
                ->withInput();
        }

        // Prevent reuse of same password
        if (Hash::check($request->new_password, $user->password)) {
            return back()
                ->withErrors(['new_password' => 'New password must be different from your current password.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated successfully! 🔒');
    }
}
