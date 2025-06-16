<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\PermisosUsuarios;




class User extends Authenticatable
{
    protected $table = 'tblusuarios';
    protected $primaryKey = 'idusuarios';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidopaterno',
        'apellidomaterno',
        'correo',
        'password',
        'activo',
        'fecharegistro',
        'fechaactualizacion'
    ];

    protected $hidden = [
        'password',
    ];

    public function permisosUsuarios()
    {
        return $this->hasMany(PermisosUsuarios::class, 'idusuarios');
    }


}
