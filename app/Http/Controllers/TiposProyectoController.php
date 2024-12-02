<?php

namespace App\Http\Controllers;

use App\Models\TiposProyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TiposProyectoController extends Controller {
    /**
     * Obtiene la lista de tipos de proyecto.
     *
     * Este método recupera todos los tipos de proyecto registrados en la base de datos. Si no se encuentran tipos de
     * proyecto, devuelve una respuesta con un mensaje de error y un código de estado 404.
     * Si se encuentran tipos de proyecto, se devuelve una respuesta con los datos de los tipos.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los tipos de proyecto.
     */
    public function index() {
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

    /**
     * Almacena un nuevo tipo de proyecto.
     *
     * Este método almacena un nuevo tipo de proyecto en la base de datos. Si los datos proporcionados no son válidos,
     * devuelve una respuesta con un mensaje de error y un código de estado 400.
     * Si los datos son válidos, se almacena el tipo de proyecto y se devuelve una respuesta con los datos del tipo
     * almacenado y un código de estado 201.
     *
     * @param \Illuminate\Http\Request $request Datos de la petición.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los datos del tipo de proyecto.
     */
    public function store(Request $request) {
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

    /**
     * Obtiene un tipo de proyecto específico.
     *
     * Este método obtiene un tipo de proyecto específico de la base de datos. Si el tipo de proyecto no se encuentra,
     * devuelve una respuesta con un mensaje de error y un código de estado 404.
     * Si el tipo de proyecto se encuentra, se devuelve una respuesta con los datos del tipo.
     *
     * @param int $id Identificador del tipo de proyecto.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los datos del tipo de proyecto.
     */
    public function show($id) {
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

    /**
     * Actualiza un tipo de proyecto.
     *
     * Este método actualiza un tipo de proyecto en la base de datos. Si el tipo de proyecto no se encuentra, devuelve
     * una respuesta con un mensaje de error y un código de estado 404.
     * Si los datos proporcionados no son válidos, devuelve una respuesta con un mensaje de error y un código de estado
     * 400.
     * Si los datos son válidos, se actualiza el tipo de proyecto y se devuelve una respuesta con los datos del tipo
     * actualizado.
     *
     * @param \Illuminate\Http\Request $request Datos de la petición.
     * @param int $id Identificador del tipo de proyecto.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los datos del tipo de proyecto.
     */
    public function update(Request $request, $id) {
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

    /**
     * Elimina un tipo de proyecto.
     *
     * Este método elimina un tipo de proyecto de la base de datos. Si el tipo de proyecto no se encuentra, devuelve una
     * respuesta con un mensaje de error y un código de estado 404.
     * Si el tipo de proyecto se encuentra, se elimina el tipo de proyecto y se devuelve una respuesta con un mensaje de
     * éxito.
     *
     * @param int $id Identificador del tipo de proyecto.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación.
     */
    public function destroy($id) {
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
