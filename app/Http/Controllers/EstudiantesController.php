<?php

namespace App\Http\Controllers;

use App\Models\Estudiantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstudiantesController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiantes::all();

        if ($estudiantes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes registrados'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estudiantes
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_usuario' => 'required|integer|exists:users,id',
            'id_carrera' => 'required|integer|exists:carreras,id',
            'carnet' => 'required|string|max:10|unique:estudiantes',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'anio_estudio' => 'required|integer',
            'telefono' => 'string|max:20|unique:estudiantes',
            'direccion' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $estudiante = Estudiantes::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ], 201);
    }

    public function show($id)
    {
        $estudiante = Estudiantes::find($id);

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ]);
    }

    public function update(Request $request, $id)
    {
        $estudiante = Estudiantes::find($id);

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_usuario' => 'integer|exists:users,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'carnet' => 'required|string|max:10|unique:estudiantes,carnet,' . $id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'anio_estudio' => 'required|integer',
            'telefono' => 'string|max:20|unique:estudiantes',
            'direccion' => 'required|string'
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
            $estudiante->$key = $request->$key;
        }

        $estudiante->save();

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ]);
    }

    public function destroy($id)
    {
        $estudiante = Estudiantes::find($id);

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $estudiante->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estudiante eliminado exitosamente'
        ]);
    }
}
