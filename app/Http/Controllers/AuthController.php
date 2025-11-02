<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try to authenticate as pegawai first
        if (Auth::guard('pegawai')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('pegawai.dashboard'));
        }

        // Try to authenticate as pelanggan
        if (Auth::guard('pelanggan')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('pelanggan.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:pelanggan'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'kode_referal_digunakan' => ['nullable', 'string', 'exists:pelanggan,kode_referal'],
        ]);

        $pelanggan = Pelanggan::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_telp' => $validated['no_telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kode_referal_digunakan' => $validated['kode_referal_digunakan'] ?? null,
        ]);

        // Give referral bonus if using someone's referral code
        if (!empty($validated['kode_referal_digunakan'])) {
            $referrer = Pelanggan::where('kode_referal', $validated['kode_referal_digunakan'])->first();
            if ($referrer) {
                $referrer->tambahPoin(50, "Bonus referral dari {$pelanggan->nama}");
            }
        }

        Auth::guard('pelanggan')->login($pelanggan);

        return redirect()->route('pelanggan.dashboard');
    }

    public function logout(Request $request)
    {
        $guard = null;
        
        if (Auth::guard('pegawai')->check()) {
            $guard = 'pegawai';
        } elseif (Auth::guard('pelanggan')->check()) {
            $guard = 'pelanggan';
        }

        if ($guard) {
            Auth::guard($guard)->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
