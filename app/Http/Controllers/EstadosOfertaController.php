<?php

namespace App\Http\Controllers;

use App\Models\EstadosOferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadosOfertaController extends Controller
{
    public function index()
    {
        $estados = EstadosOferta::all();

        if ($estados->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estados de oferta registrados'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estados
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre_estado' => 'required|string|max:50|unique:estados_oferta'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $estado = EstadosOferta::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $estado
        ], 201);
    }

    public function show($id)
    {
        $estado = EstadosOferta::find($id);

        if (is_null($estado)) {
            return response()->json([
                'success' => false,
                'message' => 'Estado de oferta no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estado
        ]);
    }

    public function update(Request $request, $id)
    {
        $estado = EstadosOferta::find($id);

        if (is_null($estado)) {
            return response()->json([
                'success' => false,
                'message' => 'Estado de oferta no encontrado'
            ], 404);
        }

        $validations = [];
        $rules = [
            'nombre_estado' => 'required|string|max:50|unique:estados_oferta,nombre_estado,' . $id
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
            $estado->$key = $request->$key;
        }

        $estado->save();

        return response()->json([
            'success' => true,
            'data' => $estado
        ]);
    }

    public function destroy($id)
    {
        $estado = EstadosOferta::find($id);

        if (is_null($estado)) {
            return response()->json([
                'success' => false,
                'message' => 'Estado de oferta no encontrado'
            ], 404);
        }

        $estado->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estado de oferta eliminado exitosamente'
        ]);
    }
}
