<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'tblpermisos';
    protected $primaryKey = 'idpermiso';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['descripcion', 'activo', 'fecharegistro', 'fechaactualizacion'];
}
