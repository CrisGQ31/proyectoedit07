<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function createUserAdmin()
    {
        User::create([
            'name' => 'Juan Martin Castillo Peña',
            'email' => 'martin.castillo@pjedomex.gob.mx',
            'password' => Hash::make('123456'),
            'photo' => '',
            'status' => 1,
            'id_branch' => 0,
            'role' => 'Administrador',
            'last_login' => '',
        ]);
        return 'Usuario creado';
    }
}
