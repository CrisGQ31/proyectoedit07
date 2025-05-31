<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;


class UsersController extends Controller
{
    public function index()
    {
        return view('modules.usuarios');
    }

    public function data($activo)
    {
        $usuarios = User::where('activo', $activo)->get();

        $data = $usuarios->map(function($usuario) {
            return [
                'idusuarios' => $usuario->idusuarios,
                'nombre' => $usuario->nombre,
                'apellidopaterno' => $usuario->apellidopaterno,
                'apellidomaterno' => $usuario->apellidomaterno,
                'correo' => $usuario->correo,
                'activo' => $usuario->activo ? 'Activo' : 'Inactivo',
                'fecharegistro' => $usuario->fecharegistro,
                'fechaactualizacion' => $usuario->fechaactualizacion,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $now = Carbon::now();

        if ($request->idusuarios) {
            // Actualización
            DB::table('tblusuarios')
                ->where('idusuarios', $request->idusuarios)
                ->update([
                    'nombre' => $request->nombre,
                    'apellidopaterno' => $request->apellidopaterno,
                    'apellidomaterno' => $request->apellidomaterno,
                    'correo' => $request->correo,
                    'password' => Hash::make($request->password),
                    'fechaactualizacion' => $now
                ]);
            return response()->json(['message' => 'Usuario actualizado correctamente']);
        } else {
            // Registro nuevo
            DB::table('tblusuarios')->insert([
                'nombre' => $request->nombre,
                'apellidopaterno' => $request->apellidopaterno,
                'apellidomaterno' => $request->apellidomaterno,
                'correo' => $request->correo,
                'password' => Hash::make($request->password),
                'activo' => 1,
                'fecharegistro' => $now,
                'fechaactualizacion' => $now
            ]);
            return response()->json(['message' => 'Usuario registrado correctamente']);
        }
    }

    public function edit($id)
    {
        $usuario = DB::table('tblusuarios')->where('idusuarios', $id)->first();
        return response()->json($usuario);
    }

    public function toggle($id)
    {
        $usuario = DB::table('tblusuarios')->where('idusuarios', $id)->first();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $nuevoEstado = $usuario->activo ? 0 : 1;

        DB::table('tblusuarios')
            ->where('idusuarios', $id)
            ->update([
                'activo' => $nuevoEstado,
                'fechaactualizacion' => Carbon::now()
            ]);

        return response()->json(['message' => 'Estado actualizado correctamente']);
    }
}
