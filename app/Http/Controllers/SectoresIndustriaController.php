<?php

namespace App\Http\Controllers;

use App\Models\SectoresIndustria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectoresIndustriaController extends Controller
{
    public function index()
    {
        $sectores = SectoresIndustria::all();

        if ($sectores->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron sectores de industria',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Sectores de industria recuperados correctamente',
            'status' => true,
            'data' => $sectores
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:100|unique:sectores_industria',
            'descripcion' => 'string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $sector = SectoresIndustria::create($request->all());

        return response()->json([
            'message' => 'Sector de industria creado correctamente',
            'status' => true,
            'data' => $sector
        ], 201);
    }

    public function show($id)
    {
        $sector = SectoresIndustria::find($id);

        if (is_null($sector)) {
            return response()->json([
                'message' => 'Sector de industria no encontrado',
                'status' => false
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $sector
        ]);
    }

    public function update(Request $request, $id)
    {
        $sector = SectoresIndustria::find($id);

        if (is_null($sector)) {
            return response()->json([
                'message' => 'Sector de industria no encontrado',
                'status' => false
            ], 404);
        }

        $rules = [
            'nombre' => 'string|max:100|unique:sectores_industria,nombre,' . $id,
            'descripcion' => 'string'
        ];

        foreach ($rules as $key => $value) {
            if ($request->has($key)) {
                $sector->$key = $request->$key;
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        foreach ($rules as $key => $value) {
            if ($request->has($key)) {
                $sector->$key = $request->$key;
            }
        }

        $sector->save();

        return response()->json([
            'message' => 'Sector de industria actualizado correctamente',
            'status' => true,
            'data' => $sector
        ]);
    }

    public function destroy($id)
    {
        $sector = SectoresIndustria::find($id);

        if (is_null($sector)) {
            return response()->json([
                'message' => 'Sector de industria no encontrado',
                'status' => false
            ], 404);
        }

        $sector->delete();

        return response()->json([
            'message' => 'Sector de industria eliminado correctamente',
            'status' => true
        ]);
    }
}
