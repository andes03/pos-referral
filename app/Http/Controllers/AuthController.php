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
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggan,email',
            'password' => 'required|string|min:8|confirmed',
            'no_telp' => 'required|regex:/^[0-9]{10,15}$/',
            'alamat' => 'required|string',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 100 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_telp.regex' => 'Nomor telepon harus berisi 10-15 digit angka',
            'alamat.required' => 'Alamat harus diisi',
        ]);

        $pelanggan = Pelanggan::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_telp' => $validated['no_telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        // Give initial bonus points to new customer
        $pelanggan->tambahPoin(50, 'Bonus pendaftaran pelanggan baru');

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
