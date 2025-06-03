<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use App\Helpers\BitacoraHelper; // ← IMPORTANTE

class PermisoController extends Controller
{
    public function index()
    {
        return view('modules.permisos');
    }

    public function data(Request $request)
    {
        $activo = $request->activo ?? 'S';
        $permisos = Permiso::where('activo', $activo)->get();
        return datatables()->of($permisos)->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        try {
            $permiso = Permiso::create([
                'descripcion' => $validated['descripcion'],
                'activo' => 'S',
                'fecharegistro' => now(),
                'fechaactualizacion' => now(),
            ]);

            // Bitácora
            BitacoraHelper::registrar('alta_permiso', 'Registro de permiso: ' . $permiso->descripcion);

            return response()->json(['status' => 'success', 'msg' => 'Permiso registrado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al guardar el permiso: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $permiso = Permiso::find($id);
        if (!$permiso) return response()->json(['status' => 'error', 'msg' => 'No encontrado']);
        return response()->json(['status' => 'success', 'data' => $permiso]);
    }

    public function update(Request $request, $id)
    {
        $permiso = Permiso::find($id);
        if (!$permiso) return response()->json(['status' => 'error', 'msg' => 'No encontrado']);

        $permiso->descripcion = $request->descripcion;
        $permiso->fechaactualizacion = now();
        $permiso->save();

        // Bitácora
        BitacoraHelper::registrar('edicion_permiso', 'Actualización de permiso: ' . $permiso->descripcion);

        return response()->json(['status' => 'success', 'msg' => 'Permiso actualizado']);
    }

    public function toggle(Request $request)
    {
        $permiso = Permiso::find($request->id);
        if (!$permiso) return response()->json(['status' => 'error', 'msg' => 'No encontrado']);

        $permiso->activo = $request->activo;
        $permiso->fechaactualizacion = now();
        $permiso->save();

        // Bitácora
        $accion = $request->activo === 'S' ? 'Activación de permiso: ' : 'Desactivación de permiso: ';
        BitacoraHelper::registrar('toggle_permiso', $accion . $permiso->descripcion);

        return response()->json(['status' => 'success', 'msg' => 'Estado actualizado']);
    }
}
