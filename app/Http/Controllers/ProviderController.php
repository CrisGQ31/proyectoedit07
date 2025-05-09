<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;

class ProviderController extends Controller
{
    // Mostrar formulario para agregar proveedor
    public function create()
    {
        return view('providers.create');
    }

    // Guardar nuevo proveedor
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:15',
            'contact_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);

        Provider::create($request->all());

        return redirect()->route('providers.index')->with('success', 'Proveedor creado con éxito.');
    }

    // Mostrar lista de proveedores
    public function index()
    {
        $providers = Provider::all();
        return view('providers.index', compact('providers'));
    }

    // Mostrar formulario para editar proveedor
    public function edit($id)
    {
        $provider = Provider::findOrFail($id);
        return view('providers.edit', compact('provider'));
    }

    // Actualizar proveedor
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:15',
            'contact_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);

        $provider = Provider::findOrFail($id);
        $provider->update($request->all());

        return redirect()->route('providers.index')->with('success', 'Proveedor actualizado con éxito.');
    }

    // Eliminar proveedor
    public function destroy($id)
    {
        $provider = Provider::findOrFail($id);
        $provider->delete();

        return redirect()->route('providers.index')->with('success', 'Proveedor eliminado con éxito.');
    }
}
