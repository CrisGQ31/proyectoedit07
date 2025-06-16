<?php

namespace App\Http\Controllers;

use App\Models\PermisoUsuario;
use App\Models\User;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PermisosUsuariosController extends Controller
{
    public function index()
    {
        $permisosUsuarios = PermisoUsuario::with(['usuario', 'permiso'])->get();
        return view('modules.permisosusuarios.index', compact('permisosUsuarios'));
    }

    public function create()
    {
        $usuarios = User::where('activo', 1)->get();
        $permisos = Permiso::where('activo', 1)->get();
        return view('modules.permisosusuarios.create', compact('usuarios', 'permisos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idusuarios' => 'required|exists:tblusuarios,idusuarios',
            'idpermiso' => 'required|exists:tblpermisos,idpermiso',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        PermisoUsuario::create([
            'idusuarios' => $request->idusuarios,
            'idpermiso' => $request->idpermiso,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo,
            'fecharegistro' => Carbon::now(),
            'fechaactualizacion' => Carbon::now(),
        ]);

        return redirect()->route('permisosusuarios.index')->with('success', 'Permiso asignado correctamente.');
    }

    public function edit($id)
    {
        $permisoUsuario = PermisoUsuario::findOrFail($id);
        $usuarios = User::where('activo', 1)->get();
        $permisos = Permiso::where('activo', 1)->get();
        return view('modules.permisosusuarios.edit', compact('permisoUsuario', 'usuarios', 'permisos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idusuarios' => 'required|exists:tblusuarios,idusuarios',
            'idpermiso' => 'required|exists:tblpermisos,idpermiso',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        $permisoUsuario = PermisoUsuario::findOrFail($id);

        $permisoUsuario->update([
            'idusuarios' => $request->idusuarios,
            'idpermiso' => $request->idpermiso,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo,
            'fechaactualizacion' => Carbon::now(),
        ]);

        return redirect()->route('permisosusuarios.index')->with('success', 'Permiso usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $permisoUsuario = PermisoUsuario::findOrFail($id);
        $permisoUsuario->activo = !$permisoUsuario->activo;
        $permisoUsuario->fechaactualizacion = Carbon::now();
        $permisoUsuario->save();

        return redirect()->route('permisosusuarios.index')->with('success', 'Estado del permiso usuario cambiado correctamente.');
    }
}
