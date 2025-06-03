<?php

namespace App\Helpers;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraHelper
{
    public static function registrar($clvaccion, $observacion, $activo = 1)
    {
        Bitacora::create([
            'idusuarios' => Auth::id(),
            'clvacciones' => $clvaccion,
            'observaciones' => $observacion,
            'activo' => $activo,
            'fecharegistro' => now(),
            'fechaactualizacion' => now()
        ]);
    }
}
