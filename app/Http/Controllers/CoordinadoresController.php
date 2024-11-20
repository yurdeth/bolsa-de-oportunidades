<?php

namespace App\Http\Controllers;

use App\Models\Coordinadores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoordinadoresController extends Controller
{
    public function index()
    {
        $coordinadores = Coordinadores::all();

        if ($coordinadores->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron coordinadores',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Coordinadores recuperados correctamente',
            'status' => true,
            'data' => $coordinadores
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_usuario' => 'required|integer|exists:usuarios,id',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'id_departamento' => 'required|integer|exists:departamento,id',
            'telefono' => 'string|max:20|unique:coordinadores'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
    }

    public function show($id)
    {
        $coordinador = Coordinadores::find($id);

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Coordinador recuperado correctamente',
            'status' => true,
            'data' => $coordinador
        ]);
    }

    public function update(Request $request, $id)
    {
        $coordinador = Coordinadores::find($id);

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_usuario' => 'integer|exists:usuarios,id',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'id_departamento' => 'integer|exists:departamento,id',
            'telefono' => 'string|max:20|unique:coordinadores,telefono,' . $id
        ];

        foreach($rules as $key => $value) {
            if (!$request->has($key)) {
                $validations[$key] = $value;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            $coordinador->$key = $request->$key;
        }

        $coordinador->save();

        return response()->json([
            'message' => 'Coordinador actualizado correctamente',
            'status' => true,
            'data' => $coordinador
        ]);
    }

    public function destroy($id)
    {
        $coordinador = Coordinadores::find($id);

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        $coordinador->delete();

        return response()->json([
            'message' => 'Coordinador eliminado correctamente',
            'status' => true
        ]);
    }
}
