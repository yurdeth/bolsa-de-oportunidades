<?php

namespace App\Http\Controllers;

use App\Models\SectoresIndustria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectoresIndustriaController extends Controller {
    /**
     * Obtiene la lista de sectores de industria.
     *
     * Este método recupera todos los sectores de industria registrados en la base de datos. Si no se encuentran
     * sectores de industria, devuelve una respuesta con un mensaje de error y un código de estado 404.
     * Si se encuentran sectores de industria, se devuelve una respuesta con los datos de los sectores.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los sectores de industria.
     */
    public function index() {
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

    /**
     * Almacena un nuevo sector de industria.
     *
     * Este método crea un nuevo sector de industria con la información proporcionada en la solicitud.
     * Si la información proporcionada no es válida, devuelve una respuesta con un mensaje de error y un código de estado 400.
     * Si la información es válida, crea el sector de industria y devuelve una respuesta con un mensaje de éxito y un código de estado 201.
     *
     * @param \Illuminate\Http\Request $request Solicitud con la información del nuevo sector de industria.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y el sector de industria creado.
     */
    public function store(Request $request) {
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

    /**
     * Obtiene un sector de industria específico.
     *
     * Este método obtiene un sector de industria específico según el ID proporcionado.
     * Si el sector de industria no se encuentra registrado, devuelve una respuesta con un mensaje de error y un código de estado 404.
     * Si el sector de industria se encuentra registrado, devuelve una respuesta con los datos del sector de industria.
     *
     * @param int $id ID del sector de industria.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y el sector de industria solicitado.
     */
    public function show($id) {
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

    /**
     * Actualiza un sector de industria.
     *
     * Este método actualiza un sector de industria según el ID proporcionado y la información recibida en la solicitud.
     * Si el sector de industria no se encuentra registrado, devuelve una respuesta con un mensaje de error y un código de estado 404.
     * Si la información proporcionada no es válida, devuelve una respuesta con un mensaje de error y un código de estado 400.
     * Si la información es válida, actualiza el sector de industria y devuelve una respuesta con un mensaje de éxito y un código de estado 200.
     *
     * @param \Illuminate\Http\Request $request Solicitud con la información del sector de industria a actualizar.
     * @param int $id ID del sector de industria.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y el sector de industria actualizado.
     */
    public function update(Request $request, $id) {
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

    /**
     * Elimina un sector de industria.
     *
     * Este método elimina un sector de industria según el ID proporcionado.
     * Si el sector de industria no se encuentra registrado, devuelve una respuesta con un mensaje de error y un código de estado 404.
     * Si el sector de industria se encuentra registrado, lo elimina y devuelve una respuesta con un mensaje de éxito y un código de estado 200.
     *
     * @param int $id ID del sector de industria.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación.
     */
    public function destroy($id) {
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
