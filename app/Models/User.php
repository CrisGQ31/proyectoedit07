<?php

//namespace App\Models;
//
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
//
//class  User extends Authenticatable
//{
//    use Notifiable;
//
//    protected $table = 'tblusuarios';
//    protected $primaryKey = 'idusuarios';
//    public $timestamps = false;
//
//    protected $fillable = [
//        'nombre',
//        'apellidopaterno',
//        'apellidomaterno',
//        'correo',
//        'contraseña',
//        'activo',
//        'fecharegistro',
//        'fechaactualizacion',
//    ];
//
//    protected $hidden = [
//        'contraseña',
//    ];
//
//    // Campo para login (correo en lugar de email)
//    public function getAuthIdentifierName()
//    {
//        return 'correo';
//    }
//
//    // Campo para la contraseña personalizada
//    public function getAuthPassword()
//    {
//        return $this->contraseña;
//    }
//}



//<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    protected $table = 'tblusuarios'; // ← nombre real de la tabla
    protected $primaryKey = 'idusuarios'; // ← clave primaria personalizada
    public $incrementing = true; // ← si es autoincremental
    public $timestamps = false; // ← si no usas created_at / updated_at

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class, 'idusuarios');
    }

}
