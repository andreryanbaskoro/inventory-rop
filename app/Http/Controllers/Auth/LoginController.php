<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function formLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    public function prosesLogin(Request $request): RedirectResponse
    {
        $kredensial = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $ingatSaya = $request->boolean('ingat_saya');

        if (Auth::attempt(['email' => $kredensial['email'], 'password' => $kredensial['password']], $ingatSaya)) {
            $request->session()->regenerate();

            /** @var \App\Models\Pengguna $pengguna */
            $pengguna = Auth::user();

            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function keluar(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
