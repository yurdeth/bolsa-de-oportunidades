<?php

namespace App\Http\Controllers;

use App\Models\Proyectos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProyectosController extends Controller
{
    public function index()
    {
        $proyectos = Proyectos::all();

        if ($proyectos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay proyectos registrados'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_empresa' => 'integer|exists:empresas,id',
            'id_estado_oferta' => 'integer|exists:estados_oferta,id',
            'id_modalidad' => 'integer|exists:modalidades_trabajo,id',
            'id_tipo_proyecto' => 'integer|exists:tipos_proyecto,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'requisitos' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'fecha_limite_aplicacion' => 'date',
            'estado_proyecto' => 'boolean',
            'cupos_disponibles' => 'integer',
            'ubicacion' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $proyecto = Proyectos::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ], 201);
    }

    public function show($id)
    {
        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_empresa' => 'integer|exists:empresas,id',
            'id_estado_oferta' => 'integer|exists:estados_oferta,id',
            'id_modalidad' => 'integer|exists:modalidades_trabajo,id',
            'id_tipo_proyecto' => 'integer|exists:tipos_proyecto,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'titulo' => 'string|max:200',
            'descripcion' => 'string',
            'requisitos' => 'string',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'fecha_limite_aplicacion' => 'date',
            'estado_proyecto' => 'boolean',
            'cupos_disponibles' => 'integer',
            'ubicacion' => 'string',
        ];

        foreach ($rules as $key => $value) {
            if ($request->has($key)) {
                $validations[$key] = $value;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            $proyecto->$key = $request->$key;
        }

        $proyecto->save();

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    public function destroy($id)
    {
        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $proyecto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado'
        ]);
    }
}
