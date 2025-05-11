<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProveedorController extends Controller
{
    // Mostrar la vista principal
    public function index()
    {
        return view('modules.proveedores');
    }

    // Obtener los datos para DataTables
    public function getData(Request $request)
    {
        $activo = $request->get('activo', 'S');

        $proveedores = Proveedor::where('activo', $activo)->select(['id', 'nombre', 'contacto', 'telefono', 'email', 'activo']);

        return DataTables::of($proveedores)->make(true);
    }

    // Crear proveedor
    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $proveedor = new Proveedor();
            $proveedor->nombre = $request->nombre;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono = $request->telefono;
            $proveedor->email = $request->email;
            $proveedor->activo = 'S';
            $proveedor->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Proveedor registrado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al registrar el proveedor: ' . $e->getMessage()
            ]);
        }
    }

    // Actualizar proveedor
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:proveedores,id',
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        try {
            $proveedor = Proveedor::findOrFail($request->id);
            $proveedor->nombre = $request->nombre;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono = $request->telefono;
            $proveedor->email = $request->email;
            $proveedor->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Proveedor actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al actualizar el proveedor: ' . $e->getMessage()
            ]);
        }
    }

    // Activar o desactivar proveedor
    public function toggle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:proveedores,id',
            'activo' => 'required|in:S,N',
        ]);

        try {
            $proveedor = Proveedor::findOrFail($request->id);
            $proveedor->activo = $request->activo;
            $proveedor->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Proveedor actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al actualizar el proveedor: ' . $e->getMessage()
            ]);
        }
    }

    // Eliminar proveedor
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:proveedores,id',
        ]);

        try {
            $proveedor = Proveedor::findOrFail($request->id);
            $proveedor->delete();

            return response()->json([
                'status' => 'success',
                'msg' => 'Proveedor eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al eliminar el proveedor: ' . $e->getMessage()
            ]);
        }
    }

    // Consultar proveedor (para el modal de edición)
    public function edit($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $proveedor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al obtener el proveedor: ' . $e->getMessage()
            ]);
        }
    }


}

