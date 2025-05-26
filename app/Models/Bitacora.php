<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'tblbitacora';
    protected $primaryKey = 'idbitacora';
    public $timestamps = false;

    protected $fillable = [
        'idusuarios', 'clvacciones', 'observaciones', 'activo', 'fecharegistro', 'fechaactualizacion'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuarios');
    }

    public function accion()
    {
        return $this->belongsTo(Accion::class, 'clvacciones', 'clvacciones');
    }
}
