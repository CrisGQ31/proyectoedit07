<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bitacora;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class BitacoraController extends Controller
{
    public function index()
    {
        return view('modules.bitacora');
    }

    public function data()
    {
        $bitacoras = DB::table('tblbitacora as b')
            ->join('tblusuarios as u', 'b.idusuarios', '=', 'u.idusuarios')
            ->join('tblacciones as a', 'b.clvacciones', '=', 'a.clvacciones')
            ->select(
                'b.idbitacora',
                'u.nombre', // ← Aquí estás trayendo el campo que necesita la vista
                'a.descripcion',
                'b.observaciones',
                'b.fecharegistro'
            );

        return datatables()->of($bitacoras)->make(true);
    }
}
