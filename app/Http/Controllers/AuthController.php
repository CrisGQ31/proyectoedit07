<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // MOSTRAR EL FORMULARIO DE LOGIN
    public function showLoginForm()
    {   
        // VALIDAMOS SI YA EXISTE UNA SESSION ACTIVA
        if (Auth::check()) { // ENVIAMOS AL DASHBOARD
            return redirect()->route('dashboard');
        }else{ // ENVIAMOS AL LOGIN
            return view('login');
        }
    }

    // SE REALIZA LA ACCION DE INICIAR SESSION
    public function login(Request $request)
    {   
        // OBTENEMOS CREDENCIALES
        $credentials = $request->only('email', 'password');
        $credentials['activo'] = 'S'; // AGREGAMOS CONDICION DE USUARIOS ACTIVOS
        $remember = $request->has('remember');

        // VALIDAMOS Y ENVIAMOS AL DASHBOARD SI ES CORRECTO
        if (Auth::attempt($credentials, $remember)) { // CREDENCIALES CORRECTAS
            return redirect()->intended('dashboard');
        }else{ // CREDENCIALES INCORRECTAS
            return redirect()->back()->with('error', 'Credenciales incorrectas');
        }
    }

    // CERRAMOS SESSION
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}