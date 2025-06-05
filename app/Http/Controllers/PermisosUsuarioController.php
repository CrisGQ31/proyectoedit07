<?php

namespace App\Http\Controllers;

use App\Models\PermisosUsuario;
use App\Models\Usuario;
use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisosUsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::where('activo', 'S')->get();
        $permisos = Permiso::where('activo', 'S')->get();

        return view('modules.permisosusuarios', compact('usuarios', 'permisos'));
    }

    public function data()
    {
        $permisosUsuarios = PermisosUsuario::with(['usuario', 'permiso'])->get();

        $data = $permisosUsuarios->map(function($item) {
            return [
                'id' => $item->idpermisosusuarios,
                'usuario' => $item->usuario ? $item->usuario->nombre . ' ' . $item->usuario->apellidopaterno : 'N/A',
                'permiso' => $item->permiso ? $item->permiso->descripcion : 'N/A',
                'descripcion' => $item->descripcion,
                'activo' => $item->activo,
                'fecharegistro' => $item->fecharegistro,
                'fechaactualizacion' => $item->fechaactualizacion,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idusuarios' => 'required|exists:tblusuarios,idusuarios',
            'idpermiso' => 'required|exists:tblpermisos,idpermiso',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $permisoUsuario = PermisosUsuario::create([
            'idusuarios' => $request->idusuarios,
            'idpermiso' => $request->idpermiso,
            'descripcion' => $request->descripcion,
            'activo' => 'S',
            'fecharegistro' => now(),
            'fechaactualizacion' => now(),
        ]);

        return response()->json(['message' => 'Permiso usuario creado exitosamente']);
    }

    public function edit($id)
    {
        $permisoUsuario = PermisosUsuario::findOrFail($id);
        return response()->json($permisoUsuario);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idusuarios' => 'required|exists:tblusuarios,idusuarios',
            'idpermiso' => 'required|exists:tblpermisos,idpermiso',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|in:S,N',
        ]);

        $permisoUsuario = PermisosUsuario::findOrFail($id);
        $permisoUsuario->update([
            'idusuarios' => $request->idusuarios,
            'idpermiso' => $request->idpermiso,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo,
            'fechaactualizacion' => now(),
        ]);

        return response()->json(['message' => 'Permiso usuario actualizado exitosamente']);
    }

    public function toggle($id)
    {
        $permisoUsuario = PermisosUsuario::findOrFail($id);
        $permisoUsuario->activo = ($permisoUsuario->activo === 'S') ? 'N' : 'S';
        $permisoUsuario->fechaactualizacion = now();
        $permisoUsuario->save();

        return response()->json(['message' => 'Estado actualizado correctamente']);
    }

    public function destroy($id)
    {
        $permisoUsuario = PermisosUsuario::findOrFail($id);
        $permisoUsuario->delete();

        return response()->json(['message' => 'Permiso usuario eliminado']);
    }
}
