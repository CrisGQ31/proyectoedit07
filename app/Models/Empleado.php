<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'tblempleados'; // nombre exacto de la tabla

    protected $primaryKey = 'idempleado'; // clave primaria de la tabla

    public $timestamps = false; // desactiva created_at y updated_at automáticos

    protected $fillable = [
        'nombre',
        'apellidopaterno',
        'apellidomaterno',
        'telefono',
        'correo',
        'activo',
        'fecharegistro',
        'fechaactualizacion'
    ];
}
