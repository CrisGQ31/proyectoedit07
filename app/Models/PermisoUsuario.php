<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermisoUsuario extends Model
{
    use HasFactory;

    protected $table = 'tblpermisosusuarios';
    protected $primaryKey = 'idpermisosusuarios';
    public $timestamps = false;

    protected $fillable = [
        'idusuarios',
        'idpermiso',
        'descripcion',
        'activo',
        'fecharegistro',
        'fechaactualizacion'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuarios', 'idusuarios');
    }

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'idpermiso', 'idpermiso');
    }
}
