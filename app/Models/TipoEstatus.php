<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEstatus extends Model
{
    protected $table = 'tbltipoestatus';
    protected $primaryKey = 'idtipoestatus';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'descripcion',
        'observaciones',
        'activo',
        'fecharegistro',
        'fechaactualizacion',
    ];
}
