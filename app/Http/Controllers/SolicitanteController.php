<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitante;
use Carbon\Carbon;
use DB;

class SolicitanteController extends Controller
{
    public function index()
    {
        return view('modules.solicitantes');
    }

    public function data($estado)
    {
        $solicitantes = Solicitante::where('activo', $estado)->get()->map(function ($item) {
            $item->nombre_completo = $item->nombre . ' ' . $item->apellidopaterno . ' ' . $item->apellidomaterno;
            $item->acciones = '
                <button class="btn btn-sm btn-primary" onclick="editarSolicitante(' . $item->idsolicitante . ')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="cambiarEstadoSolicitante(' . $item->idsolicitante . ')"><i class="fas fa-power-off"></i></button>
            ';
            return $item;
        });

        return response()->json(['data' => $solicitantes]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'apellidopaterno' => 'required|string',
                'apellidomaterno' => 'required|string',
                'telefono' => 'required|string',
                'rfc' => 'required|string',
                'curp' => 'required|string',
            ]);

            if ($request->idsolicitante) {
                $solicitante = Solicitante::find($request->idsolicitante);
                $solicitante->fechaactualizacion = now();
            } else {
                $solicitante = new Solicitante();
                $solicitante->activo = 1;
                $solicitante->fecharegistro = now();
            }

            $solicitante->nombre = $request->nombre;
            $solicitante->apellidopaterno = $request->apellidopaterno;
            $solicitante->apellidomaterno = $request->apellidomaterno;
            $solicitante->telefono = $request->telefono;
            $solicitante->rfc = $request->rfc;
            $solicitante->curp = $request->curp;
            $solicitante->save();

            return response()->json(['message' => 'Solicitante guardado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        return Solicitante::findOrFail($id);
    }

    public function toggle($id)
    {
        $solicitante = Solicitante::findOrFail($id);
        $solicitante->activo = $solicitante->activo ? 0 : 1;
        $solicitante->fechaactualizacion = Carbon::now();
        $solicitante->save();

        return response()->json(['message' => 'Estado actualizado correctamente.']);
    }
}
