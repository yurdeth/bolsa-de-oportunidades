<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarrerasController extends Controller
{
    public function index()
    {
        $carreras = Carreras::all();
        if ($carreras->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay carreras registradas'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $carreras
        ]);
    }

    public function store(Request $request)
    {
        $validations = [
            'id_departamento' => 'required|integer|exists:departamento,id',
            'codigo_carrera' => 'required|string|max:10|unique:carreras',
            'nombre_carrera' => 'required|string|max:10'
        ];

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        $carrera = Carreras::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Carrera creada correctamente',
            'data' => $carrera
        ], 201);

    }

    public function show($id)
    {
        $carrera = Carreras::find($id);

        if (is_null($carrera)) {
            return response()->json([
                'status' => false,
                'message' => 'Carrera no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $carrera
        ]);
    }

    public function update(Request $request, $id)
    {
        $carrera = Carreras::find($id);

        if (is_null($carrera)) {
            return response()->json([
                'status' => false,
                'message' => 'Carrera no encontrada'
            ], 404);
        }

        $validations = [];
        $fields = [
            'id_departamento' => 'required|integer|exists:departamento,id',
            'codigo_carrera' => 'required|string|max:10|unique:carreras,codigo_carrera,' . $id,
            'nombre_carrera' => 'required|string|max:100'];

        foreach ($fields as $field => $validation) {
            if ($request->has($field)) {
                $validations[$field] = $validation;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            if ($request->has($key)) {
                $carrera->$key = $request->$key;
            }
        }

        $carrera->save();

        return response()->json([
            'status' => true,
            'message' => 'Carrera actualizada correctamente',
            'data' => $carrera
        ]);
    }

    public function destroy($id)
    {
        $carrera = Carreras::find($id);

        if (is_null($carrera)) {
            return response()->json([
                'status' => false,
                'message' => 'Carrera no encontrada'
            ], 404);
        }

        $carrera->delete();

        return response()->json([
            'status' => true,
            'message' => 'Carrera eliminada correctamente'
        ]);
    }

    public function getCarrerasByDepartamento($id_departamento): JsonResponse {
        $carreras = Carreras::where('id_departamento', $id_departamento)->get();
        if ($carreras->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay carreras registradas para este departamento'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $carreras
        ]);
    }
}
