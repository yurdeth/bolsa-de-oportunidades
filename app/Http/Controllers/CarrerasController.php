<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarrerasController extends Controller {

    /**
     * Muestra una lista de todas las carreras registradas.
     *
     * Este método recupera todas las carreras almacenadas en la base de datos. Si no hay
     * carreras registradas, devuelve un mensaje indicando que no se encontraron registros
     * junto con un código de estado 404. En caso de éxito, devuelve una lista de todas las
     * carreras y un indicador de estado positivo.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que contiene los datos de las carreras
     * o un mensaje de error si no existen registros.
     */
    public function index() {
        $carreras = Carreras::all();
        if ($carreras->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay carreras registradas'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $carreras
        ]);
    }

    /**
     * Almacena una nueva carrera en la base de datos.
     *
     * Este método almacena una nueva carrera en la base de datos. Antes de guardar la carrera,
     * se validan los datos proporcionados por el usuario. Si los datos no son válidos, se
     * devuelve un mensaje de error con los detalles de la validación y un código de estado 400.
     * Si la carrera se guarda correctamente, se devuelve un mensaje de éxito junto con los datos
     * de la carrera y un código de estado 201.
     *
     * @param \Illuminate\Http\Request $request Datos de la carrera a almacenar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si la carrera se guardó correctamente
     * o si hubo un error de validación.
     */
    public function store(Request $request) {
        $validations = [
            'id_departamento' => 'required|integer|exists:departamento,id',
            'codigo_carrera' => 'required|string|max:10|unique:carreras',
            'nombre_carrera' => 'required|string|max:10'
        ];

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        $carrera = Carreras::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Carrera creada correctamente',
            'data' => $carrera
        ], 201);

    }

    /**
     * Muestra los datos de una carrera específica.
     *
     * Este método recupera los datos de una carrera específica en la base de datos. Si la carrera
     * no se encuentra, se devuelve un mensaje de error con un código de estado 404. En caso de
     * éxito, se devuelve un mensaje positivo junto con los datos de la carrera.
     *
     * @param int $id Identificador de la carrera a buscar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que contiene los datos de la carrera
     * o un mensaje de error si no se encontró la carrera.
     */
    public function show($id) {
        $carrera = Carreras::find($id);

        if (is_null($carrera)) {
            return response()->json([
                'status' => false,
                'message' => 'Carrera no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $carrera
        ]);
    }

    /**
     * Actualiza los datos de una carrera específica.
     *
     * Este método actualiza los datos de una carrera específica en la base de datos. Antes de
     * actualizar la carrera, se validan los datos proporcionados por el usuario. Si los datos
     * no son válidos, se devuelve un mensaje de error con los detalles de la validación y un
     * código de estado 400. Si la carrera se actualiza correctamente, se devuelve un mensaje
     * de éxito junto con los datos de la carrera.
     *
     * @param \Illuminate\Http\Request $request Datos de la carrera a actualizar.
     * @param int $id Identificador de la carrera a actualizar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si la carrera se actualizó correctamente
     * o si hubo un error de validación.
     */
    public function update(Request $request, $id) {
        $carrera = Carreras::find($id);

        if (is_null($carrera)) {
            return response()->json([
                'status' => false,
                'message' => 'Carrera no encontrada'
            ], 404);
        }

        $validations = [];
        $fields = [
            'id_departamento' => 'required|integer|exists:departamento,id',
            'codigo_carrera' => 'required|string|max:10|unique:carreras,codigo_carrera,' . $id,
            'nombre_carrera' => 'required|string|max:100'];

        foreach ($fields as $field => $validation) {
            if ($request->has($field)) {
                $validations[$field] = $validation;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            if ($request->has($key)) {
                $carrera->$key = $request->$key;
            }
        }

        $carrera->save();

        return response()->json([
            'status' => true,
            'message' => 'Carrera actualizada correctamente',
            'data' => $carrera
        ]);
    }

    /**
     * Elimina una carrera específica de la base de datos.
     *
     * Este método elimina una carrera específica de la base de datos. Si la carrera no se encuentra,
     * se devuelve un mensaje de error con un código de estado 404. Si la carrera se elimina correctamente,
     * se devuelve un mensaje de éxito.
     *
     * @param int $id Identificador de la carrera a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si la carrera se eliminó correctamente
     * o si no se encontró la carrera.
     */
    public function destroy($id) {
        $carrera = Carreras::find($id);

        if (is_null($carrera)) {
            return response()->json([
                'status' => false,
                'message' => 'Carrera no encontrada'
            ], 404);
        }

        $carrera->delete();

        return response()->json([
            'status' => true,
            'message' => 'Carrera eliminada correctamente'
        ]);
    }

    /**
     * Obtiene una lista de carreras por departamento.
     *
     * Este método recupera una lista de carreras por departamento. Si no hay carreras registradas
     * para el departamento especificado, se devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, se devuelve una lista de carreras y un indicador de estado positivo.
     *
     * @param int $id_departamento Identificador del departamento.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que contiene los datos de las carreras
     * o un mensaje de error si no existen registros.
     */
    public function getCarrerasByDepartamento($id_departamento): JsonResponse {
        $carreras = Carreras::where('id_departamento', $id_departamento)->get();
        if ($carreras->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay carreras registradas para este departamento'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => $carreras
        ]);
    }
}
