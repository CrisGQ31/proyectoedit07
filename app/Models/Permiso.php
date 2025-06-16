<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'tblpermisos'; // ← ya lo tienes

    protected $primaryKey = 'idpermiso'; // ← también ya lo tienes

    public $timestamps = false; // ← correcto si no usas created_at y updated_at

    protected $fillable = ['descripcion', 'activo', 'fecharegistro', 'fechaactualizacion']; // ← ¡Este es importante!


    public function permisosUsuarios()
    {
        return $this->hasMany(PermisosUsuario::class, 'idpermiso');
    }

}

