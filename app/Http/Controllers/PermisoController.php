<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use Carbon\Carbon;

class PermisoController extends Controller
{
    public function index()
    {
        return view('modules.permisos');
    }

    public function data()
    {
        $permisos = Permiso::all();

        return response()->json([
            'data' => $permisos->map(function ($permiso) {
                $botones = '
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-warning" onclick="openModalPermiso(' . $permiso->idpermiso . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="toggleEstadoPermiso(' . $permiso->idpermiso . ')">
                        <i class="fas fa-toggle-on"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarPermiso(' . $permiso->idpermiso . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ';

                return [
                    'idpermiso' => $permiso->idpermiso,
                    'descripcion' => $permiso->descripcion,
                    'activo' => $permiso->activo ? 'Activo' : 'Inactivo',
                    'fecharegistro' => $permiso->fecharegistro,
                    'fechaactualizacion' => $permiso->fechaactualizacion,
                    'acciones' => $botones
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'descripcion' => 'required|string|max:255'
            ]);

            $permiso = $request->idpermiso
                ? Permiso::findOrFail($request->idpermiso)
                : new Permiso();

            $permiso->descripcion = $request->descripcion;
            $permiso->activo = $permiso->activo ?? 1;
            $permiso->fecharegistro = $permiso->fecharegistro ?? Carbon::now();
            $permiso->fechaactualizacion = Carbon::now();
            $permiso->save();

            return response()->json(['message' => 'Permiso guardado correctamente']);
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $ex->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al guardar el permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);
        return response()->json($permiso);
    }

    public function toggle(Request $request)
    {
        $permiso = Permiso::findOrFail($request->id);
        $permiso->activo = !$permiso->activo;
        $permiso->fechaactualizacion = Carbon::now();
        $permiso->save();

        return response()->json(['message' => 'Estado actualizado']);
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return response()->json(['message' => 'Permiso eliminado permanentemente']);
    }
}

