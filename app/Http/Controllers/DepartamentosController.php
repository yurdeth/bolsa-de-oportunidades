<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartamentosController extends Controller
{
    public function index() : JsonResponse
    {
        $data = Departamentos::all();

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'No hay departamentos registrados',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Departamentos recuperados correctamente',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {

        $validations = [
            'nombre_departamento' => 'required|string|max:255|unique:departamento'
        ];
        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        $departamento = Departamentos::create($request->all());

        return response()->json([
            'message' => 'Departamento creado correctamente',
            'data' => $departamento
        ], 201);
    }

    public function show($id)
    {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Departamento recuperado correctamente',
            'data' => $departamento
        ]);
    }

    public function update(Request $request, $id)
    {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado',
                'status' => false
            ], 404);
        }

        $validations = [
            'nombre_departamento' => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        if ($request->has('nombre_departamento')) {
            $departamento->nombre_departamento = $request->nombre_departamento;
        }

        $departamento->save();

        return response()->json([
            'message' => 'Departamento actualizado correctamente',
            'status' => true,
            'data' => $departamento
        ]);
    }

    public function destroy($id)
    {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado'
            ], 404);
        }

        $departamento->delete();

        return response()->json([
            'message' => 'Departamento eliminado correctamente'
        ]);
    }

    public function edit($id)
    {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Departamento recuperado correctamente',
            'status' => true,
            'data' => $departamento
        ]);
    }
}
