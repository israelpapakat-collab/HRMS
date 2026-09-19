<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Employees land on the module hub (their dashboard) where they only
        // see their open modules; everyone else gets the analytics dashboard.
        $user = $request->user();

        $this->linkEmployeeAccount($user);

        $home = $user->roles->count() === 1 && $user->hasRole('employee')
            ? route('modules', absolute: false)
            : route('dashboard', absolute: false);

        return redirect()->intended($home);
    }

    /**
     * Link an employee-role user to their employee record so they can see their
     * own payroll/attendance data even if the link is missing. Tries the
     * existing user_id link first, then email, then a unique name match.
     */
    private function linkEmployeeAccount($user): void
    {
        if (!$user || !method_exists($user, 'hasRole')) {
            return;
        }

        $user->linkToEmployeeAccount();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
