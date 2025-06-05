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
        'idjuicio',      // nuevo
        'sintesis',      // nuevo
        'activo',
        'fecharegistro'
    ];

    // Relaciones
    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'idsolicitante', 'idsolicitante');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'idmateria', 'idmateria');
    }
    public function tipoJuicio()
    {
        return $this->belongsTo(TipoJuicio::class, 'idjuicio');
    }

}
