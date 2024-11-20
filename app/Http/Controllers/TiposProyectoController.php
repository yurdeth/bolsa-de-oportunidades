<?php

namespace App\Http\Controllers;

use App\Models\TiposProyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TiposProyectoController extends Controller
{
    public function index()
    {
        $tipo = TiposProyecto::all();

        if ($tipo->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay tipos de proyecto registrados'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tipo
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:50|unique:tipos_proyecto',
            'numero_horas' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $tipo = TiposProyecto::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $tipo
        ], 201);
    }

    public function show($id)
    {
        $tipo = TiposProyecto::find($id);

        if (is_null($tipo)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tipo
        ]);
    }

    public function update(Request $request, $id)
    {
        $tipo = TiposProyecto::find($id);

        if (is_null($tipo)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de proyecto no encontrado'
            ], 404);
        }

        $validations = [];
        $rules = [
            'nombre' => 'required|string|max:50|unique:tipos_proyecto,nombre,' . $id,
            'numero_horas' => 'required|integer'
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
            if ($request->has($key)) {
                $tipo->$key = $request->$key;
            }
        }

        $tipo->save();

        return response()->json([
            'success' => true,
            'data' => $tipo
        ]);

    }

    public function destroy($id)
    {
        $tipo = TiposProyecto::find($id);

        if (is_null($tipo)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de proyecto no encontrado'
            ], 404);
        }

        $tipo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de proyecto eliminado'
        ]);
    }
}
