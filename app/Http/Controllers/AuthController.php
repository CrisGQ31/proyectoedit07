<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // MOSTRAR EL FORMULARIO DE LOGIN
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('login');
        }
    }

    // INICIAR SESIÓN
    public function login(Request $request)
    {
        $credentials = $request->only('correo', 'password');
        $credentials['activo'] = 1; // Solo usuarios activos (1 = true)

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        } else {
            \Log::error('Credenciales incorrectas o usuario no activo:', $credentials);
            return redirect()->back()->with('error', 'Credenciales incorrectas o el usuario no está activo.');
        }
    }

    // CERRAR SESIÓN
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // MOSTRAR FORMULARIO DE REGISTRO
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // REGISTRAR NUEVO USUARIO
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellidopaterno' => 'required|string|max:100',
            'apellidomaterno' => 'nullable|string|max:100',
            'correo' => 'required|email|unique:tblusuarios,correo',
            'contraseña' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nombre' => $request->nombre,
            'apellidopaterno' => $request->apellidopaterno,
            'apellidomaterno' => $request->apellidomaterno,
            'correo' => $request->correo,
            'contraseña' => Hash::make($request->contraseña),
            'activo' => 1,
            'fecharegistro' => now(),
            'fechaactualizacion' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
