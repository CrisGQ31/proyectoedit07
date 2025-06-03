<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accion extends Model
{
    protected $table = 'tblacciones';
    protected $primaryKey = 'clvacciones';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'activo',
        'fecharegistro',
        'fechaactualizacion'
    ];

    // Relación con la bitácora
    public function bitacoras(): HasMany
    {
        return $this->hasMany(Bitacora::class, 'clvacciones', 'clvacciones');
    }
}
