<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarpetaController extends Controller
{
    public function index()
    {
        $solicitantes = DB::table('tblsolicitante')
            ->where('activo', 'S')
            ->orderBy('nombre')
            ->get();

        $materias = DB::table('tblmateria')
            ->where('activo', 'S')
            ->orderBy('tipomateria')
            ->get();

        $juicios = DB::table('tbltipojuicio')
            ->where('activo', 'S')
            ->orderBy('tipo')
            ->get();

        return view('modules.carpetas', compact('solicitantes', 'materias', 'juicios'));
    }

    public function data(Request $request)
    {
        $activo = $request->get('activo') === 'N' ? 'N' : 'S';

        $data = DB::table('tblcarpeta')
            ->select(
                'tblcarpeta.idcarpeta',
                'tblcarpeta.fecharegistro',
                DB::raw("CONCAT(s.nombre, ' ', s.apellidopaterno, ' ', s.apellidomaterno) AS nombre_solicitante"),
                'm.tipomateria AS materia',
                'j.tipo AS tipo_juicio'
            )
            ->join('tblsolicitante as s', 's.idsolicitante', '=', 'tblcarpeta.idsolicitante')
            ->join('tblmateria as m', 'm.idmateria', '=', 'tblcarpeta.idmateria')
            ->join('tbltipojuicio as j', 'j.idjuicio', '=', 'tblcarpeta.idjuicio')
            ->where('tblcarpeta.activo', $activo)
            ->orderByDesc('tblcarpeta.fecharegistro')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            DB::table('tblcarpeta')->insert([
                'idsolicitante' => $request->idsolicitante,
                'idmateria'     => $request->idmateria,
                'idjuicio'      => $request->idjuicio,
                'sintesis'      => $request->sintesis,
                'activo'        => 'S',
                'fecharegistro' => Carbon::now(),
                'fechaactualizacion' => Carbon::now(),
            ]);

            return response()->json(['status' => 'success', 'msg' => 'Carpeta registrada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al registrar la carpeta.']);
        }
    }

    public function edit($id)
    {
        $carpeta = DB::table('tblcarpeta')->where('idcarpeta', $id)->first();

        if ($carpeta) {
            return response()->json(['status' => 'success', 'data' => $carpeta]);
        }

        return response()->json(['status' => 'error', 'msg' => 'Carpeta no encontrada.']);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('tblcarpeta')
                ->where('idcarpeta', $id)
                ->update([
                    'idsolicitante' => $request->idsolicitante,
                    'idmateria'     => $request->idmateria,
                    'idjuicio'      => $request->idjuicio,
                    'sintesis'      => $request->sintesis,
                    'fechaactualizacion' => Carbon::now(),
                ]);

            return response()->json(['status' => 'success', 'msg' => 'Carpeta actualizada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al actualizar la carpeta.']);
        }
    }

    public function toggle($id)
    {
        try {
            $carpeta = DB::table('tblcarpeta')->where('idcarpeta', $id)->first();

            if (!$carpeta) {
                return response()->json(['status' => 'error', 'msg' => 'Carpeta no encontrada.']);
            }

            $nuevoEstado = $carpeta->activo === 'S' ? 'N' : 'S';

            DB::table('tblcarpeta')
                ->where('idcarpeta', $id)
                ->update([
                    'activo' => $nuevoEstado,
                    'fechaactualizacion' => Carbon::now()
                ]);

            return response()->json(['status' => 'success', 'msg' => 'Estado actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al cambiar el estado.']);
        }
    }

    public function delete($id)
    {
        try {
            DB::table('tblcarpeta')->where('idcarpeta', $id)->delete();
            return response()->json(['status' => 'success', 'msg' => 'Carpeta eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error al eliminar la carpeta.']);
        }
    }
}
