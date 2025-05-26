<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'tblmaterias';
    protected $primaryKey = 'idmateria';
    public $timestamps = false;

    protected $fillable = [
        'clvmateria',
        'tipomateria',
        'descripcion',
        'activo',
        'fecharegistro',
        'fechaactualizacion',
    ];

    protected $dates = [
        'fecharegistro',
        'fechaactualizacion',
    ];
}
