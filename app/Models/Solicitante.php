<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    protected $table = 'tblsolicitante'; // nombre real de la tabla

    protected $primaryKey = 'idsolicitante';

    public $timestamps = false; // si no usas created_at y updated_at

    protected $fillable = [
        'nombre',
        'apellidopaterno',
        'apellidomaterno',
        'telefono',
        'rfc',
        'curp',
        'activo',
        'fecharegistro',
        'fechaactualizacion'
    ];
}
