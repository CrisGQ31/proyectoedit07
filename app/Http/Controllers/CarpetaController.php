<?php

namespace App\Http\Controllers;

use App\Models\Carpeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarpetaController extends Controller
{
    // Mostrar la vista principal
    public function index()
    {
        return view('modules.carpetas');
    }

    // Obtener datos para DataTables
    public function data()
    {
        $carpetas = Carpeta::with(['solicitante', 'materia', 'juicio'])
            ->select('tblcarpetas.*')
            ->orderBy('idcarpeta', 'desc')
            ->get();

        return datatables()->of($carpetas)
            ->addColumn('solicitante', function ($row) {
                if (!$row->solicitante) return '';
                return $row->solicitante->nombre . ' ' .
                    $row->solicitante->apellidopaterno . ' ' .
                    $row->solicitante->apellidomaterno;
            })
            ->addColumn('materia', fn($row) => $row->materia->tipomateria ?? '')
            ->addColumn('juicio', fn($row) => $row->juicio->tipo ?? '')
            ->addColumn('acciones', function ($row) {
                $btns = '<button class="btn btn-sm btn-primary btn-edit" data-id="' . $row->idcarpeta . '">Editar</button> ';
                $btns .= ($row->activo)
                    ? '<button class="btn btn-sm btn-warning btn-toggle" data-id="' . $row->idcarpeta . '">Desactivar</button> '
                    : '<button class="btn btn-sm btn-success btn-toggle" data-id="' . $row->idcarpeta . '">Activar</button> ';
                $btns .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->idcarpeta . '">Eliminar</button>';
                return $btns;
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    // Guardar nuevo registro o actualizar existente
    public function store(Request $request)
    {
        $rules = [
            'idsolicitante' => 'required|exists:tblsolicitante,idsolicitante',
            'idmateria'     => 'required|exists:tblmaterias,idmateria',
            'idjuicio'      => 'required|exists:tbltipojuicio,idjuicio',
            'sintesis'      => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->idcarpeta) {
            // Actualizar
            $carpeta = Carpeta::findOrFail($request->idcarpeta);
            $carpeta->idsolicitante      = $request->idsolicitante;
            $carpeta->idmateria          = $request->idmateria;
            $carpeta->idjuicio           = $request->idjuicio;
            $carpeta->sintesis           = $request->sintesis;
            $carpeta->fechaactualizacion = now();
            $carpeta->save();

            return response()->json(['message' => 'Carpeta actualizada correctamente']);
        } else {
            // Crear nuevo: activo = 1 (activo)
            Carpeta::create([
                'idsolicitante'      => $request->idsolicitante,
                'idmateria'          => $request->idmateria,
                'idjuicio'           => $request->idjuicio,
                'sintesis'           => $request->sintesis,
                'activo'             => 1,
                'fecharegistro'      => now(),
                'fechaactualizacion' => null,
            ]);

            return response()->json(['message' => 'Carpeta creada correctamente']);
        }
    }

    // Obtener datos para editar
    public function edit($id)
    {
        $carpeta = Carpeta::findOrFail($id);
        return response()->json($carpeta);
    }

    // Activar o desactivar carpeta
    public function toggle($id)
    {
        $carpeta = Carpeta::findOrFail($id);

        // Cambiar el estado activo (0/1)
        $carpeta->activo = !$carpeta->activo;
        $carpeta->fechaactualizacion = now();
        $carpeta->save();

        return response()->json(['message' => 'Estado actualizado correctamente']);
    }

    // Eliminar carpeta
    public function destroy($id)
    {
        $carpeta = Carpeta::findOrFail($id);
        $carpeta->delete();

        return response()->json(['message' => 'Carpeta eliminada correctamente']);
    }
}
