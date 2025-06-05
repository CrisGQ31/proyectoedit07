<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisosUsuario extends Model
{
    use HasFactory;

    protected $table = 'tblpermisosusuarios';
    protected $primaryKey = 'idpermisosusuarios';
    public $timestamps = false; // Porque usamos campos personalizados de fecha

    protected $fillable = [
        'idusuarios',
        'idpermiso',
        'descripcion',
        'activo',
        'fecharegistro',
        'fechaactualizacion'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuarios', 'idusuarios');
    }

    // Relación con permiso
    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'idpermiso', 'idpermiso');
    }
}
