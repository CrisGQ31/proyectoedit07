<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitante;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SolicitanteController extends Controller
{
    public function index()
    {
        return view('modules.solicitantes');
    }

    public function data(Request $request)
    {
        $estado = $request->input('activo', 'S'); // Por defecto activos

        $data = Solicitante::where('activo', $estado)->get();

        return DataTables::of($data)->make(true);
    }

    public function store(Request $request)
    {
        try {
            // Validaciones del lado del servidor
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellidopaterno' => 'required|string|max:100',
                'apellidomaterno' => 'required|string|max:100',
                'telefono' => 'required|numeric|digits_between:8,15',
                'rfc' => 'required|string|max:13',
                'curp' => 'required|string|size:18',
            ]);

            $solicitante = new Solicitante($request->all());
            $solicitante->activo = 'S';
            $solicitante->fecharegistro = now();
            $solicitante->fechaactualizacion = now();
            $solicitante->save();

            return response()->json(['status' => 'success', 'msg' => 'Solicitante registrado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al guardar el solicitante: ' . $e->getMessage()]);
        }
    }



    public function edit($id)
    {
        $solicitante = Solicitante::find($id);

        if (!$solicitante) {
            return response()->json(['status' => 'error', 'msg' => 'Solicitante no encontrado.']);
        }

        return response()->json(['status' => 'success', 'data' => $solicitante]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validaciones
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellidopaterno' => 'required|string|max:100',
                'apellidomaterno' => 'required|string|max:100',
                'telefono' => 'required|numeric|digits_between:8,15',
                'rfc' => 'required|string|max:13',
                'curp' => 'required|string|size:18',
            ]);

            $solicitante = Solicitante::findOrFail($id);
            $solicitante->fill($request->all());
            $solicitante->fechaactualizacion = now();
            $solicitante->save();

            return response()->json(['status' => 'success', 'msg' => 'Solicitante actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al actualizar el solicitante: ' . $e->getMessage()]);
        }
    }


    public function toggle(Request $request)
    {
        try {
            $solicitante = Solicitante::find($request->id);

            if (!$solicitante) {
                return response()->json(['status' => 'error', 'msg' => 'Solicitante no encontrado.']);
            }

            $solicitante->activo = $request->activo;
            $solicitante->fechaactualizacion = now();
            $solicitante->save();

            $estado = $solicitante->activo === 'S' ? 'activado' : 'desactivado';

            return response()->json(['status' => 'success', 'msg' => "Solicitante $estado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al cambiar el estado del solicitante.']);
        }
    }

}
