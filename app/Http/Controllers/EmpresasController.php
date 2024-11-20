<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresasController extends Controller
{
    public function index()
    {
        $empresas = Empresas::all();

        if ($empresas->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron empresas',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Empresas recuperadas correctamente',
            'status' => true,
            'data' => $empresas
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_usuario' => 'required|integer|exists:usuarios,id',
            'id_sector' => 'required|integer|exists:sectores_industria,id',
            'nombre' => 'required|string|max:200',
            'direccion' => 'string',
            'telefono' => 'string|max:20|unique:empresas',
            'sitio_web' => 'string|max:255',
            'descripcion' => 'string',
            'logo_url' => 'string|max:255',
            'verificada' => 'boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $empresa = Empresas::create($request->all());

        return response()->json([
            'message' => 'Empresa creada correctamente',
            'status' => true,
            'data' => $empresa
        ], 201);
    }

    public function show($id)
    {

        $empresa = Empresas::find($id);

        if (is_null($empresa)) {
            return response()->json([
                'message' => 'Empresa no encontrada',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Empresa recuperada correctamente',
            'status' => true,
            'data' => $empresa
        ]);

    }

    public function update(Request $request, $id)
    {

        $empresa = Empresas::find($id);

        if (is_null($empresa)) {
            return response()->json([
                'message' => 'Empresa no encontrada',
                'status' => false
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_usuario' => 'integer|exists:usuarios,id',
            'id_sector' => 'integer|exists:sectores_industria,id',
            'nombre' => 'string|max:200',
            'direccion' => 'required|string',
            'telefono' => 'string|max:20|unique:empresas,telefono,' . $id,
            'sitio_web' => 'string|max:255',
            'descripcion' => 'required|string',
            'logo_url' => 'string|max:255',
            'verificada' => 'boolean'
        ];

        foreach ($rules as $key => $value) {
            if ($request->has($key)) {
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
            if ($request->has($key)) {
                $empresa->$key = $request->$key;
            }
        }

        $empresa->save();

        return response()->json([
            'message' => 'Empresa actualizada correctamente',
            'status' => true,
            'data' => $empresa
        ]);
    }

    public function destroy($id)
    {

        $empresa = Empresas::find($id);

        if (is_null($empresa)) {
            return response()->json([
                'message' => 'Empresa no encontrada',
                'status' => false
            ], 404);
        }

        $empresa->delete();

        return response()->json([
            'message' => 'Empresa eliminada correctamente',
            'status' => true
        ]);

    }
}
