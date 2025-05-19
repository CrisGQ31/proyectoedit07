<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accion;
use Yajra\DataTables\Facades\DataTables;

class AccionController extends Controller
{
    // Mostrar la vista
    public function index()
    {
        return view('modules.acciones');
    }

    // Obtener datos para DataTables
    public function data(Request $request)
    {
        $query = Accion::query();

        if ($request->has('activo') && in_array($request->activo, ['S', 'N'])) {
            $query->where('activo', $request->activo);
        }

        return DataTables::of($query)->make(true);
    }

    // Obtener un registro para editar
    public function edit($id)
    {
        $accion = Accion::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $accion
        ]);
    }

    // Guardar o actualizar
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

            $id = $request->hddIdAccion ?? $request->id;
            if ($id) {
            $accion = Accion::findOrFail($id);
//        if ($request->id) {
//            $accion = Accion::findOrFail($request->id);
            $accion->descripcion = $request->descripcion;
            $accion->fechaactualizacion = now();
        } else {
            $accion = new Accion();
            $accion->descripcion = $request->descripcion;
            $accion->activo = 'S';
            $accion->fecharegistro = now();
        }

        $accion->save();

        return response()->json([
            'status' => 'success',
            'msg' => $request->id ? 'Acción actualizada correctamente.' : 'Acción registrada correctamente.'
        ]);
    }

    // Cambiar estado activo/inactivo
    public function toggle(Request $request)
    {
        $accion = Accion::findOrFail($request->id);
        $accion->activo = $request->activo;
        $accion->fechaactualizacion = now();
        $accion->save();

        return response()->json([
            'status' => 'success',
            'msg' => $accion->activo === 'S' ? 'Acción activada correctamente.' : 'Acción desactivada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        try {
            $accion = Accion::findOrFail($id);
            $accion->delete();

            return response()->json(['status' => 'success', 'msg' => 'Acción eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'No se pudo eliminar la acción.']);
        }
    }


}
