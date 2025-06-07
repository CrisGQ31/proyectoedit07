<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    protected $table = 'tblcarpetas';
    protected $primaryKey = 'idcarpeta';
    public $timestamps = false;

    protected $fillable = [
        'idsolicitante',
        'idmateria',
        'idjuicio',
        'sintesis',
        'activo',
        'fecharegistro',
        'fechaactualizacion',
    ];

    // Relaciones
    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'idsolicitante');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idmateria');
    }

    public function juicio()
    {
        return $this->belongsTo(TipoJuicio::class, 'idjuicio');
    }
}


