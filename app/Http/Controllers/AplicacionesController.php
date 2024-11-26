<?php

namespace App\Http\Controllers;

use App\Models\Aplicaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AplicacionesController extends Controller
{
    public function index()
    {
        $aplicaciones = Aplicaciones::all();

        if ($aplicaciones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay aplicaciones registradas'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aplicaciones
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_estudiante' => 'integer|exists:estudiantes,id',
            'id_proyecto' => 'integer|exists:proyectos,id',
            'id_estado_aplicacion' => 'integer|exists:estados_aplicacion,id',
            'comentarios_empresa' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $aplicacion = Aplicaciones::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ], 201);
    }

    public function show($id)
    {
        $aplicacion = Aplicaciones::find($id);

        if (is_null($aplicacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Aplicación no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ]);
    }


    public function findByEstudiante($id_estudiante)
    {
        $aplicaciones = Aplicaciones::where('id_estudiante', $id_estudiante)->get();

        if ($aplicaciones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay aplicaciones registradas para el estudiante'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aplicaciones
        ]);
    }

    public function update(Request $request, $id)
    {
        $aplicacion = Aplicaciones::find($id);

        if (is_null($aplicacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Aplicación no encontrada'
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_estudiante' => 'integer|exists:estudiantes,id',
            'id_proyecto' => 'integer|exists:proyectos,id',
            'id_estado_aplicacion' => 'integer|exists:estados_aplicacion,id',
            'comentarios_empresa' => 'string',
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
            $aplicacion->$key = $request->$key;
        }

        $aplicacion->save();

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ]);
    }

    public function destroy($id)
    {
        $aplicacion = Aplicaciones::find($id);

        if (is_null($aplicacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Aplicación no encontrada'
            ], 404);
        }

        $aplicacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aplicación eliminada'
        ]);
    }
}
