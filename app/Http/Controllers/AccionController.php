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

        if ($request->activo) {
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

        if ($request->id) {
            $accion = Accion::findOrFail($request->id);
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

}
