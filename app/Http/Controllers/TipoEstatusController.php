<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEstatus;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TipoEstatusController extends Controller
{
    public function index()
    {
        return view('modules.tipoestatus');
    }

    public function data(Request $request)
    {
        $activo = $request->input('activo', 'S');
        $data = TipoEstatus::where('activo', $activo)->get();
        return DataTables::of($data)->make(true);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'descripcion' => 'required|string|max:100',
                'observaciones' => 'nullable|string|max:255',
            ]);

            TipoEstatus::create([
                'descripcion' => $request->descripcion,
                'observaciones' => $request->observaciones,
                'activo' => 'S',
                'fecharegistro' => now(),
                'fechaactualizacion' => now()
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Tipo de status registrado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $tipo = TipoEstatus::find($id);
        if (!$tipo) {
            return response()->json(['status' => 'error', 'msg' => 'Tipo de status no encontrado.']);
        }

        return response()->json(['status' => 'success', 'data' => $tipo]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'descripcion' => 'required|string|max:100',
                'observaciones' => 'nullable|string|max:255',
            ]);

            $tipo = TipoEstatus::findOrFail($id);
            $tipo->descripcion = $request->descripcion;
            $tipo->observaciones = $request->observaciones;
            $tipo->fechaactualizacion = now();
            $tipo->save();

            return response()->json(['status' => 'success', 'msg' => 'Tipo de status actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function toggle($id)
    {
        try {
            $tipo = TipoEstatus::find($id);
            if (!$tipo) {
                return response()->json(['status' => 'error', 'msg' => 'Tipo de status no encontrado.']);
            }

            $tipo->activo = $tipo->activo === 'S' ? 'N' : 'S';
            $tipo->fechaactualizacion = now();
            $tipo->save();

            $estado = $tipo->activo === 'S' ? 'activado' : 'desactivado';
            return response()->json(['status' => 'success', 'msg' => "Tipo de status $estado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al cambiar el estado.']);
        }
    }
}
