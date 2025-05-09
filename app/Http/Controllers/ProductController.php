<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    // Mostrar la vista de productos
    public function index()
    {
        return view('modules.products');
    }

    // Obtener los datos de los productos (para DataTables)
    public function getData(Request $request)
    {
        $activo = $request->get('activo', 'S');

        $productos = Product::where('activo', $activo)->select(['id', 'nombre', 'precio', 'activo']);

        return DataTables::of($productos)->make(true);
    }

    // Crear un nuevo producto
    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            $product = new Product();
            $product->nombre = $request->nombre;
            $product->precio = $request->precio;
            $product->activo = 'S';
            $product->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Producto registrado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al registrar el producto: ' . $e->getMessage()
            ]);
        }
    }

    // Actualizar un producto
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            $product = Product::findOrFail($request->id);
            $product->nombre = $request->nombre;
            $product->precio = $request->precio;
            $product->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Producto actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al actualizar el producto: ' . $e->getMessage()
            ]);
        }
    }

    // Activar o desactivar un producto
    public function toggle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        try {
            $product = Product::findOrFail($request->id);
            $product->activo = ($product->activo == 'S') ? 'N' : 'S';  // Cambiar el estado
            $product->save();

            return response()->json([
                'status' => 'success',
                'msg' => 'Estado del producto actualizado correctamente.',
                'new_status' => $product->activo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al actualizar el estado del producto: ' . $e->getMessage()
            ]);
        }
    }

    // Eliminar un producto
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        try {
            $product = Product::findOrFail($request->id);
            $product->delete();

            return response()->json([
                'status' => 'success',
                'msg' => 'Producto eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error al eliminar el producto: ' . $e->getMessage()
            ]);
        }
    }

}

