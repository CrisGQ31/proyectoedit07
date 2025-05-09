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
        $credentials = $request->only('email', 'password');
        $credentials['activo'] = 'S'; // Solo usuarios activos
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
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
        // Validaciones
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activo' => 'S', // Importante: marcar como activo
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}

//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class AuthController extends Controller
//{
//    // MOSTRAR EL FORMULARIO DE LOGIN
//    public function showLoginForm()
//    {
//        // VALIDAMOS SI YA EXISTE UNA SESSION ACTIVA
//        if (Auth::check()) { // ENVIAMOS AL DASHBOARD
//            return redirect()->route('dashboard');
//        }else{ // ENVIAMOS AL LOGIN
//            return view('login');
//        }
//    }
//
//    // SE REALIZA LA ACCION DE INICIAR SESSION
//
//
//    public function login(Request $request)
//    {
//        // OBTENEMOS CREDENCIALES
//        $credentials = $request->only('email', 'password');
//        $credentials['activo'] = 'S'; // Verificar que el usuario esté activo
//        $remember = $request->has('remember');
//
//        // Intentamos autenticar
//        if (Auth::attempt($credentials, $remember)) {
//            return redirect()->intended('dashboard');
//        } else {
//            \Log::error('Credenciales incorrectas o usuario no activo:', $credentials);
//            return redirect()->back()->with('error', 'Credenciales incorrectas o el usuario no está activo.');
//        }
//    }
////    public function login(Request $request)
////    {
////        // OBTENEMOS CREDENCIALES
////        $credentials = $request->only('email', 'password');
////        $credentials['activo'] = 'S'; // AGREGAMOS CONDICION DE USUARIOS ACTIVOS
////        $remember = $request->has('remember');
////
////        // VALIDAMOS Y ENVIAMOS AL DASHBOARD SI ES CORRECTO
////        if (Auth::att empt($credentials, $remember)) { // CREDENCIALES CORRECTAS
////            return redirect()->intended('dashboard');
////        }else{ // CREDENCIALES INCORRECTAS
////            return redirect()->back()->with('error', 'Credenciales incorrectas');
////        }
////    }
//
//    // CERRAMOS SESSION
//    public function logout(Request $request)
//    {
//        Auth::logout();
//
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
//
//        return redirect('/');
//    }
//}
