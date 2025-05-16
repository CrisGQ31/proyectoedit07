<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'tblacciones'; // Asegúrate que este sea el nombre real en tu BD
    protected $primaryKey = 'clvacciones'; // Llave primaria

    public $timestamps = false; // Ya que usas tus propios campos de fecha

    protected $fillable = [
        'descripcion',
        'activo',
        'fecharegistro',
        'fechaactualizacion',
    ];
}
