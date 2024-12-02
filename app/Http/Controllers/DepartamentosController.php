<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartamentosController extends Controller {
    /**
     * Muestra una lista de todos los departamentos registrados.
     *
     * Este método recupera todos los departamentos almacenados en la base de datos.
     * Si no se encuentran registros, devuelve un mensaje de error con un código de estado 404.
     * En caso de éxito, devuelve una lista de los departamentos junto con un mensaje de confirmación.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de los departamentos
     * o un mensaje de error si no hay registros.
     */
    public function index(): JsonResponse {
        $data = Departamentos::all();

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'No hay departamentos registrados',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Departamentos recuperados correctamente',
            'data' => $data
        ]);
    }

    /**
     * Almacena un nuevo departamento en la base de datos.
     *
     * Este método almacena un nuevo departamento en la base de datos. Antes de guardar el departamento,
     * se validan los datos proporcionados por el usuario. Si los datos no son válidos, se devuelve un
     * mensaje de error con los detalles de la validación y un código de estado 400. Si el departamento
     * se guarda correctamente, se devuelve un mensaje de éxito junto con los datos del departamento y
     * un código de estado 201.
     *
     * @param \Illuminate\Http\Request $request Datos del departamento a almacenar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si el departamento se guardó correctamente
     * o si hubo un error de validación.
     */
    public function store(Request $request) {

        $validations = [
            'nombre_departamento' => 'required|string|max:255|unique:departamento'
        ];
        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        $departamento = Departamentos::create($request->all());

        return response()->json([
            'message' => 'Departamento creado correctamente',
            'data' => $departamento
        ], 201);
    }

    /**
     * Muestra los datos de un departamento específico.
     *
     * Este método recupera los datos de un departamento específico en la base de datos. Si el departamento
     * no se encuentra, se devuelve un mensaje de error con un código de estado 404. En caso de éxito,
     * devuelve los datos del departamento junto con un mensaje de confirmación.
     *
     * @param int $id ID del departamento a mostrar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del departamento
     * o un mensaje de error si no se encuentra el departamento.
     */
    public function show($id) {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Departamento recuperado correctamente',
            'data' => $departamento
        ]);
    }

    /**
     * Actualiza los datos de un departamento específico.
     *
     * Este método actualiza los datos de un departamento específico en la base de datos. Antes de actualizar
     * el departamento, se validan los datos proporcionados por el usuario. Si los datos no son válidos, se
     * devuelve un mensaje de error con los detalles de la validación y un código de estado 400. Si el
     * departamento se actualiza correctamente, se devuelve un mensaje de éxito junto con los datos del
     * departamento actualizado.
     *
     * @param \Illuminate\Http\Request $request Datos del departamento a actualizar.
     * @param int $id ID del departamento a actualizar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si el departamento se actualizó correctamente
     * o si hubo un error de validación.
     */
    public function update(Request $request, $id) {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado',
                'status' => false
            ], 404);
        }

        $validations = [
            'nombre_departamento' => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        if ($request->has('nombre_departamento')) {
            $departamento->nombre_departamento = $request->nombre_departamento;
        }

        $departamento->save();

        return response()->json([
            'message' => 'Departamento actualizado correctamente',
            'status' => true,
            'data' => $departamento
        ]);
    }

    /**
     * Elimina un departamento específico de la base de datos.
     *
     * Este método elimina un departamento específico de la base de datos. Si el departamento no se encuentra,
     * se devuelve un mensaje de error con un código de estado 404. Si el departamento se elimina correctamente,
     * se devuelve un mensaje de éxito.
     *
     * @param int $id ID del departamento a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que indica si el departamento se eliminó correctamente
     * o si no se encontró el departamento.
     */
    public function destroy($id) {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado'
            ], 404);
        }

        $departamento->delete();

        return response()->json([
            'message' => 'Departamento eliminado correctamente'
        ]);
    }

    /**
     * Muestra el formulario de edición de un departamento específico.
     *
     * Este método recupera los datos de un departamento específico en la base de datos y los devuelve
     * en un formulario de edición. Si el departamento no se encuentra, se devuelve un mensaje de error
     * con un código de estado 404. En caso de éxito, devuelve los datos del departamento junto con un
     * mensaje de confirmación.
     *
     * @param int $id ID del departamento a editar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del departamento
     * o un mensaje de error si no se encuentra el departamento.
     */
    public function edit($id) {
        $departamento = Departamentos::find($id);

        if (is_null($departamento)) {
            return response()->json([
                'message' => 'Departamento no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Departamento recuperado correctamente',
            'status' => true,
            'data' => $departamento
        ]);
    }
}
