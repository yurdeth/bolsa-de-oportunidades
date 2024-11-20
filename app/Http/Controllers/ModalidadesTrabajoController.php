<?php

namespace App\Http\Controllers;

use App\Models\ModalidadesTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModalidadesTrabajoController extends Controller
{
    public function index()
    {
        $modalidades = ModalidadesTrabajo::all();

        if ($modalidades->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron modalidades de trabajo',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Modalidades de trabajo recuperadas correctamente',
            'status' => true,
            'data' => $modalidades
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:50|unique:modalidades_trabajo',
            'descripcion' => 'string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $modalidad = ModalidadesTrabajo::create($request->all());

        return response()->json([
            'message' => 'Modalidad de trabajo creada correctamente',
            'status' => true,
            'data' => $modalidad
        ], 201);
    }

    public function show($id)
    {
        $modalidad = ModalidadesTrabajo::find($id);

        if (!$modalidad) {
            return response()->json([
                'message' => 'Modalidad de trabajo no encontrada',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Modalidad de trabajo recuperada correctamente',
            'status' => true,
            'data' => $modalidad
        ]);
    }

    public function update(Request $request, $id)
    {
        $modalidad = ModalidadesTrabajo::find($id);

        if (!$modalidad) {
            return response()->json([
                'message' => 'Modalidad de trabajo no encontrada',
                'status' => false
            ], 404);
        }

        $validations = [];
        $rules = [
            'nombre' => 'required|string|max:50|unique:modalidades_trabajo,nombre,' . $id,
            'descripcion' => 'string'
        ];

        foreach ($rules as $key => $rule) {
            if ($request->has($key)) {
                $validations[$key] = $rule;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            $modalidad->$key = $request->$key;
        }

        $modalidad->save();

        return response()->json([
            'message' => 'Modalidad de trabajo actualizada correctamente',
            'status' => true,
            'data' => $modalidad
        ]);
    }

    public function destroy($id)
    {
        $modalidad = ModalidadesTrabajo::find($id);

        if (!$modalidad) {
            return response()->json([
                'message' => 'Modalidad de trabajo no encontrada',
                'status' => false
            ], 404);
        }

        $modalidad->delete();

        return response()->json([
            'message' => 'Modalidad de trabajo eliminada correctamente',
            'status' => true
        ]);
    }
}
