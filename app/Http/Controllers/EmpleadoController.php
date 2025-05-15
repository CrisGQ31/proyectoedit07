<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    // Mostrar la vista
    public function index()
    {
        return view('modules.empleados');
    }

    // Data para DataTables
    public function data(Request $request)
    {
        $query = Empleado::query();

        if ($request->activo) {
            $query->where('activo', $request->activo);
        }

        return DataTables::of($query)->make(true);
    }

    // Mostrar datos para editar
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $empleado
        ]);
    }

    // Guardar nuevo empleado
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidopaterno' => 'required|string|max:100',
            'apellidomaterno' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:100',
        ]);

        $empleado = new Empleado();
        $empleado->nombre = $request->nombre;
        $empleado->apellidopaterno = $request->apellidopaterno;
        $empleado->apellidomaterno = $request->apellidomaterno;
        $empleado->telefono = $request->telefono;
        $empleado->correo = $request->correo;
        $empleado->activo = 'S';
        $empleado->fecharegistro = now();
        $empleado->save();

        return response()->json([
            'status' => 'success',
            'msg' => 'Empleado registrado exitosamente.'
        ]);
    }

    // Actualizar empleado
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:empleados,idempleado',
            'nombre' => 'required|string|max:100',
            'apellidopaterno' => 'required|string|max:100',
            'apellidomaterno' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:100',
        ]);

        $empleado = Empleado::findOrFail($request->id);
        $empleado->nombre = $request->nombre;
        $empleado->apellidopaterno = $request->apellidopaterno;
        $empleado->apellidomaterno = $request->apellidomaterno;
        $empleado->telefono = $request->telefono;
        $empleado->correo = $request->correo;
        $empleado->fechaactualizacion = now();
        $empleado->save();

        return response()->json([
            'status' => 'success',
            'msg' => 'Empleado actualizado exitosamente.'
        ]);
    }

    // Cambiar estado activo/inactivo
    public function toggle(Request $request)
    {
        $empleado = Empleado::findOrFail($request->id);
        $empleado->activo = $request->activo;
        $empleado->fechaactualizacion = now();
        $empleado->save();

        return response()->json([
            'status' => 'success',
            'msg' => $empleado->activo == 'S' ? 'Empleado activado exitosamente.' : 'Empleado desactivado exitosamente.'
        ]);
    }
}
