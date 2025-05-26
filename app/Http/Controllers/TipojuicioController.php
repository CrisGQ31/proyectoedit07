<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoJuicio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class TipoJuicioController extends Controller
{
    public function index()
    {
        return view('modules.tipojuicio');
    }

    public function data($estado)
    {
        $activo = ($estado === 'S') ? 'S' : 'N';
        $datos = TipoJuicio::where('activo', $activo)->get();

        return datatables()->of($datos)->toJson();
    }

    public function store(Request $request)
    {
        try {
            $tipoJuicio = new TipoJuicio();
            $tipoJuicio->clvjuicio = $request->input('clvjuicio');
            $tipoJuicio->descripcion = $request->input('descripcion');
            $tipoJuicio->activo = 'S';
            $tipoJuicio->fecharegistro = Carbon::now();
            $tipoJuicio->fechaactualizacion = Carbon::now();
            $tipoJuicio->save();

            return response()->json(['status' => 'success', 'msg' => 'Tipo de juicio registrado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al guardar el tipo de juicio: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $tipoJuicio = TipoJuicio::find($id);

        if (!$tipoJuicio) {
            return response()->json(['status' => 'error', 'msg' => 'Tipo de juicio no encontrado']);
        }

        return response()->json(['status' => 'success', 'data' => $tipoJuicio]);
    }

    public function update(Request $request, $id)
    {
        try {
            $tipoJuicio = TipoJuicio::find($id);

            if (!$tipoJuicio) {
                return response()->json(['status' => 'error', 'msg' => 'Tipo de juicio no encontrado']);
            }

            $tipoJuicio->clvjuicio = $request->input('clvjuicio');
            $tipoJuicio->descripcion = $request->input('descripcion');
            $tipoJuicio->fechaactualizacion = Carbon::now();
            $tipoJuicio->save();

            return response()->json(['status' => 'success', 'msg' => 'Tipo de juicio actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function toggle(Request $request, $id)
    {
        try {
            $tipoJuicio = TipoJuicio::find($id);

            if (!$tipoJuicio) {
                return response()->json(['status' => 'error', 'msg' => 'Tipo de juicio no encontrado']);
            }

            $tipoJuicio->activo = $request->input('activo');
            $tipoJuicio->fechaactualizacion = Carbon::now();
            $tipoJuicio->save();

            $msg = $request->input('activo') === 'S' ? 'activado' : 'desactivado';

            return response()->json(['status' => 'success', 'msg' => "Tipo de juicio $msg correctamente."]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al cambiar el estado: ' . $e->getMessage()]);
        }
    }
}
