<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoJuicio extends Model
{
    protected $table = 'tbltipojuicio';
    protected $primaryKey = 'idjuicio';
    public $incrementing = true;
    public $timestamps = false;

    // Asegura que la clave primaria es un entero (opcional, pero recomendable si se usa UUID en otros modelos)
    protected $keyType = 'int';

    protected $fillable = [
        'clvjuicio',
        'descripcion',
        'activo',
        'fecharegistro',
        'fechaactualizacion',
    ];
}

