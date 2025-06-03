<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accion;
use App\Models\Bitacora;
use DataTables;
use Carbon\Carbon;

class AccionController extends Controller
{
    public function index()
    {
        return view('modules.acciones');
    }

    public function data(Request $request)
    {
        $query = Accion::query();

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo); // Aquí ya esperas 1 o 0 directamente
        }

        return DataTables::of($query)->make(true);
    }


    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $accion = Accion::create([
            'descripcion' => $request->descripcion,
            'activo' => 'S',
            'fecharegistro' => Carbon::now(),
            'fechaactualizacion' => Carbon::now()
        ]);

        // Registrar en la bitácora (si deseas que se registre aquí)
        Bitacora::create([
            'idusuarios' => auth()->id(), // asegúrate de tener login
            'clvacciones' => $accion->clvacciones,
            'observaciones' => 'Registro de nueva acción.',
            'activo' => 1,
            'fecharegistro' => now(),
            'fechaactualizacion' => now()
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Acción registrada correctamente.']);
    }

    public function edit($id)
    {
        $accion = Accion::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $accion
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $accion = Accion::findOrFail($id);
        $accion->update([
            'descripcion' => $request->descripcion,
            'fechaactualizacion' => now()
        ]);

        Bitacora::create([
            'idusuarios' => auth()->id(),
            'clvacciones' => $accion->clvacciones,
            'observaciones' => 'Actualización de acción.',
            'activo' => 1,
            'fecharegistro' => now(),
            'fechaactualizacion' => now()
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Acción actualizada correctamente.']);
    }

    public function toggle(Request $request)
    {
        $accion = Accion::findOrFail($request->id);
        $accion->update([
            'activo' => $request->activo, //=== 'S', // ? 1 : 0,
            'fechaactualizacion' => now()
        ]);

        Bitacora::create([
            'idusuarios' => auth()->id(),
            'clvacciones' => $accion->clvacciones,
            'observaciones' => $accion->activo ? 'Activación de acción.' : 'Desactivación de acción.',
            'activo' => 1,
            'fecharegistro' => now(),
            'fechaactualizacion' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => $accion->activo ? 'Acción activada correctamente.' : 'Acción desactivada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $accion = Accion::findOrFail($id);
        $accion->delete();

        Bitacora::create([
            'idusuarios' => auth()->id(),
            'clvacciones' => $id,
            'observaciones' => 'Eliminación de acción.',
            'activo' => 0,
            'fecharegistro' => now(),
            'fechaactualizacion' => now()
        ]);

        return response()->json(['status' => 'success', 'msg' => 'Acción eliminada correctamente.']);
    }
}
