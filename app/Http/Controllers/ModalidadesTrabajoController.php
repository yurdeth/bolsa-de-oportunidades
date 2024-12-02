<?php

namespace App\Http\Controllers;

use App\Models\ModalidadesTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModalidadesTrabajoController extends Controller {
    /**
     * Muestra una lista de todas las modalidades de trabajo registradas.
     *
     * Este método recupera todas las modalidades de trabajo almacenadas en la base de datos.
     * Si no se encuentran registros, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve una lista de modalidades de trabajo junto con un mensaje de confirmación.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de las modalidades de trabajo
     * o un mensaje de error si no se encuentran registros.
     */
    public function index() {
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

    /**
     * Almacena una nueva modalidad de trabajo en la base de datos.
     *
     * Este método almacena una nueva modalidad de trabajo en la base de datos.
     * Si los datos proporcionados no son válidos, devuelve un mensaje de error con un código de estado 400.
     * En caso de éxito, devuelve un mensaje de confirmación junto con los datos de la modalidad de trabajo creada.
     *
     * @param \Illuminate\Http\Request $request Datos de la modalidad de trabajo a almacenar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de la modalidad de trabajo creada
     * o un mensaje de error si los datos no son válidos.
     */
    public function store(Request $request) {
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

    /**
     * Muestra los datos de una modalidad de trabajo específica.
     *
     * Este método recupera los datos de una modalidad de trabajo específica almacenada en la base de datos.
     * Si la modalidad de trabajo no se encuentra, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve los datos de la modalidad de trabajo solicitada junto con un mensaje de confirmación.
     *
     * @param int $id Identificador de la modalidad de trabajo
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de la modalidad de trabajo
     * o un mensaje de error si no se encuentra el registro.
     */
    public function show($id) {
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

    /**
     * Actualiza los datos de una modalidad de trabajo específica.
     *
     * Este método actualiza los datos de una modalidad de trabajo específica en la base de datos.
     * Si la modalidad de trabajo no se encuentra, devuelve un mensaje de error con un código de estado 404.
     * Si los datos proporcionados no son válidos, devuelve un mensaje de error con un código de estado 400.
     * En caso de éxito, devuelve un mensaje de confirmación junto con los datos de la modalidad de trabajo actualizada.
     *
     * @param \Illuminate\Http\Request $request Datos de la modalidad de trabajo a actualizar
     * @param int $id Identificador de la modalidad de trabajo
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de la modalidad de trabajo actualizada
     * o un mensaje de error si los datos no son válidos.
     */
    public function update(Request $request, $id) {
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

    /**
     * Elimina una modalidad de trabajo específica de la base de datos.
     *
     * Este método elimina una modalidad de trabajo específica de la base de datos.
     * Si la modalidad de trabajo no se encuentra, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve un mensaje de confirmación.
     *
     * @param int $id Identificador de la modalidad de trabajo
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de confirmación
     * o un mensaje de error si la modalidad de trabajo no se encuentra.
     */
    public function destroy($id) {
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
