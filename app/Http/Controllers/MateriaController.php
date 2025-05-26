<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class MateriaController extends Controller
{
    public function index()
    {
        return view('modules.materias');
    }

    public function data(Request $request)
    {
        try {
            $estado = $request->input('activo', 'S');
            $data = Materia::where('activo', $estado)->get();

            return DataTables::of($data)->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'clvmateria' => 'required|string|max:50',
                'tipomateria' => 'required|string|max:50',
                'descripcion' => 'required|string|max:255',
            ]);

            $materia = new Materia($request->all());
            $materia->activo = 'S';
            $materia->fecharegistro = now();
            $materia->fechaactualizacion = now();
            $materia->save();

            return response()->json(['status' => 'success', 'msg' => 'Materia registrada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $materia = Materia::find($id);

        if (!$materia) {
            return response()->json(['status' => 'error', 'msg' => 'Materia no encontrada.']);
        }

        return response()->json(['status' => 'success', 'data' => $materia]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'clvmateria' => 'required|string|max:50',
                'tipomateria' => 'required|string|max:50',
                'descripcion' => 'required|string|max:255',
            ]);

            $materia = Materia::findOrFail($id);
            $materia->fill($request->all());
            $materia->fechaactualizacion = now();
            $materia->save();

            return response()->json(['status' => 'success', 'msg' => 'Materia actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function toggle($id)
    {
        try {
            $materia = Materia::find($id);

            if (!$materia) {
                return response()->json(['status' => 'error', 'msg' => 'Materia no encontrada.']);
            }

            $materia->activo = $materia->activo === 'S' ? 'N' : 'S';
            $materia->fechaactualizacion = Carbon::now();
            $materia->save();

            $estado = $materia->activo === 'S' ? 'activada' : 'desactivada';

            return response()->json(['status' => 'success', 'msg' => "Materia $estado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al cambiar el estado.']);
        }
    }
}
