<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bitacora;
use Yajra\DataTables\Facades\DataTables;

class BitacoraController extends Controller
{
    public function index()
    {
        return view('modules.bitacora');
    }

    public function data(Request $request)
    {
        $query = Bitacora::with(['usuario', 'accion']);

        return DataTables::of($query)
            ->addColumn('nombre_usuario', function ($bitacora) {
                return $bitacora->usuario->nombre . ' ' . $bitacora->usuario->apellidopaterno;
            })
            ->addColumn('descripcion_accion', function ($bitacora) {
                return $bitacora->accion->descripcion ?? '';
            })
            ->rawColumns(['nombre_usuario', 'descripcion_accion'])
            ->make(true);
    }
}
