<?php

namespace App\Http\Controllers;

use App\Models\EstadosOferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadosOfertaController extends Controller {
    /**
     * Muestra una lista de todos los estados de oferta registrados.
     *
     * Este método recupera todos los estados de oferta almacenados en la base de datos.
     * Si no existen registros, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve una lista con los estados de oferta junto con un mensaje de confirmación.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de los estados de oferta
     * o un mensaje de error si no hay registros.
     */
    public function index() {
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

    /**
     * Almacena un nuevo estado de oferta en la base de datos.
     *
     * Este método valida los datos recibidos y, si son correctos, crea un nuevo estado de oferta.
     * Si los datos no son válidos, devuelve un mensaje de error con un código de estado 400.
     * En caso de éxito, devuelve el estado de oferta creado junto con un mensaje de confirmación.
     *
     * @param \Illuminate\Http\Request $request Datos de la petición
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del estado de oferta creado
     * o un mensaje de error si los datos no son válidos.
     */
    public function store(Request $request) {
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

    /**
     * Muestra un estado de oferta específico.
     *
     * Este método recupera un estado de oferta específico de la base de datos.
     * Si el estado de oferta no existe, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve el estado de oferta solicitado junto con un mensaje de confirmación.
     *
     * @param int $id Identificador del estado de oferta
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del estado de oferta
     * o un mensaje de error si no se encuentra el registro.
     */
    public function show($id) {
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

    /**
     * Actualiza un estado de oferta específico en la base de datos.
     *
     * Este método valida los datos recibidos y, si son correctos, actualiza un estado de oferta.
     * Si los datos no son válidos, devuelve un mensaje de error con un código de estado 400.
     * En caso de éxito, devuelve el estado de oferta actualizado junto con un mensaje de confirmación.
     *
     * @param \Illuminate\Http\Request $request Datos de la petición
     * @param int $id Identificador del estado de oferta
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del estado de oferta actualizado
     * o un mensaje de error si los datos no son válidos.
     */
    public function update(Request $request, $id) {
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

    /**
     * Elimina un estado de oferta específico de la base de datos.
     *
     * Este método elimina un estado de oferta de la base de datos.
     * Si el estado de oferta no existe, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve un mensaje de confirmación.
     *
     * @param int $id Identificador del estado de oferta
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de confirmación
     * o un mensaje de error si no se encuentra el registro.
     */
    public function destroy($id) {
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
