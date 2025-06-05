<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoJuicio;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class TipoJuicioController extends Controller
{
    public function index()
    {
        return view('modules.tipojuicio');
    }

    public function data($estado)
    {
        $query = TipoJuicio::where('activo', $estado);

        return DataTables::of($query)->make(true);
    }

    public function store(Request $request)
    {
        try {
            $nuevo = new TipoJuicio();
            $nuevo->clvjuicio = $request->clvjuicio;
            $nuevo->tipo = $request->tipo;
            $nuevo->activo = 'S';
            $nuevo->fecharegistro = Carbon::now();
            $nuevo->fechaactualizacion = Carbon::now();
            $nuevo->save();

            return response()->json(['status' => 'success', 'msg' => 'Tipo de juicio registrado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al registrar el tipo de juicio.']);
        }
    }

    public function edit($id)
    {
        $tipo = TipoJuicio::find($id);
        if ($tipo) {
            return response()->json(['status' => 'success', 'data' => $tipo]);
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Tipo de juicio no encontrado.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $tipo = TipoJuicio::find($id);
            if (!$tipo) {
                return response()->json(['status' => 'error', 'msg' => 'Tipo de juicio no encontrado.']);
            }

            $tipo->clvjuicio = $request->clvjuicio;
            $tipo->tipo = $request->tipo;
            $tipo->fechaactualizacion = Carbon::now();
            $tipo->save();

            return response()->json(['status' => 'success', 'msg' => 'Tipo de juicio actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al actualizar el tipo de juicio.']);
        }
    }

    public function toggle($id, Request $request)
    {
        try {
            $tipo = TipoJuicio::find($id);
            if (!$tipo) {
                return response()->json(['status' => 'error', 'msg' => 'Tipo de juicio no encontrado.']);
            }

            $tipo->activo = $request->activo;
            $tipo->fechaactualizacion = Carbon::now();
            $tipo->save();

            return response()->json(['status' => 'success', 'msg' => 'Estado actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al cambiar el estado.']);
        }
    }
}
