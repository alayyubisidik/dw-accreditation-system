<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // Seeder admin otomatis
        if ($credentials['email'] === 'admin@gmail.com') {

            $user = User::where('email', 'admin@gmail.com')->first();

            if (!$user) {

                User::create([
                    'name' => 'Super Admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('test'),
                    'role' => 'admin',
                    'is_active' => true,
                ]);
            }
        }

        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user->is_active) {

            AlertService::error(
                'Akun Anda tidak aktif. Silakan hubungi administrator.'
            );

            return back()->withInput(
                $request->only('email')
            );
        }

        $request->authenticate();

        $request->session()->regenerate();

        if ($user->role == 'admin') {
            return redirect()->route('dashboard-admin.university.index');
        }

        return redirect()->route('dashboard-operator.index');
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
