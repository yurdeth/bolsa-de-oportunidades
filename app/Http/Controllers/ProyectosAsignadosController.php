<?php

namespace App\Http\Controllers;

use App\Models\ProyectosAsignados;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProyectosAsignadosController extends Controller {
    /**
     * Muestra los proyectos asignados según el tipo de usuario autenticado.
     *
     * Este método verifica el tipo de usuario autenticado y, en función de este, devuelve los proyectos asignados correspondientes:
     * - Si el usuario es de tipo 3 (estudiante), se devuelve un mensaje de error indicando que la ruta no está permitida.
     * - Si el usuario es de tipo 4 (empresa), se filtran los proyectos asignados a esa empresa.
     * - Para otros tipos de usuario, se recuperan todos los proyectos asignados sin filtrado específico.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los proyectos asignados correspondientes según el tipo de usuario.
     */
    public function index(): JsonResponse {
        if (Auth::user()->id_tipo_usuario == 3) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ], 403);
        }

        $id_empresa = DB::table('empresas')
            ->where('id_usuario', Auth::user()->id)
            ->value('id');

        if (Auth::user()->id_tipo_usuario == 4){
            $proyectosAsignados = (new ProyectosAsignados)->filterByEmpresa($id_empresa);
        } else{
            $proyectosAsignados = (new ProyectosAsignados)->getProyectosAsignados();
        }

        return response()->json([
            'success' => true,
            'data' => $proyectosAsignados
        ]);
    }

    /**
     * Asigna un proyecto a un estudiante.
     *
     * Este método permite a un coordinador asignar un proyecto a un estudiante.
     * Primero valida que el usuario autenticado sea un coordinador, luego valida los datos de entrada.
     * Después, verifica que el estudiante no tenga un proyecto asignado previamente, asigna el proyecto al estudiante,
     * y finalmente elimina cualquier aplicación previa que el estudiante haya hecho a otros proyectos.
     *
     * @param \Illuminate\Http\Request $request Datos del proyecto y estudiante a asignar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación.
     */
    public function store(Request $request): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 2) { // <- Solamente el coordinador puede asignar proyectos
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ], 403);
        }

        // Validar los datos
        $rules = [
            'id_proyecto' => 'required|integer|exists:proyectos,id',
            'id_estudiante' => 'required|integer|exists:estudiantes,id',
        ];

        $messages = [
            'id_proyecto.required' => 'El proyecto es requerido',
            'id_proyecto.integer' => 'El proyecto debe ser un número entero',
            'id_proyecto.exists' => 'El proyecto no existe',
            'id_estudiante.required' => 'El estudiante es requerido',
            'id_estudiante.integer' => 'El estudiante debe ser un número entero',
            'id_estudiante.exists' => 'El estudiante no existe',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $proyectoAsignado = DB::table('proyectos_asignados')
            ->where('id_proyecto', $request->id_proyecto)
            ->where('id_estudiante', $request->id_estudiante)
            ->first();

        if ($proyectoAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante ya tiene asignado un proyecto'
            ], 400);
        }

        // Asignar el proyecto al estudiante
        $proyectoAsignado = ProyectosAsignados::create($request->all());

        // Eliminar las aplicaciones del estudiante a los demás proyectos
        DB::table('aplicaciones')
            ->where('id_estudiante', $request->id_estudiante)
            ->where('id_proyecto', '!=', $request->id_proyecto)
            ->delete();

        return response()->json([
            'success' => true,
            'data' => $proyectoAsignado
        ], 201);
    }

    /**
     * Muestra un proyecto asignado específico.
     *
     * Este método permite a un coordinador o empresa ver un proyecto asignado específico.
     * Primero valida que el usuario autenticado sea un coordinador o empresa, luego recupera el proyecto asignado.
     *
     * @param string $id Identificador del proyecto asignado.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el proyecto asignado correspondiente.
     */
    public function show(string $id): JsonResponse {
        // Solamente coordinadores y empresas pueden ver los proyectos asignados
        if (Auth::user()->id_tipo_usuario != 2 || Auth::user()->id_tipo_usuario != 4) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ], 403);
        }

        $proyectoAsignado = (new ProyectosAsignados)->getProyectosAsignados($id);

        if (!$proyectoAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto asignado no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $proyectoAsignado
        ], 200);
    }

    public function update(Request $request, string $id) {
        //
    }

    public function destroy(string $id, string $id_estudiante) {
        //
    }

    /**
     * Retira un estudiante de un proyecto asignado.
     *
     * Este método permite a un estudiante retirarse de un proyecto asignado.
     * Primero valida que el usuario autenticado sea un estudiante, luego recupera el proyecto asignado.
     * Después, actualiza el estado de la aplicación del estudiante a "Retirado".
     *
     * @param \Illuminate\Http\Request $request Datos del proyecto y estudiante a retirar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación.
     */
    public function retirar(Request $request) {
        $id_estudiante = $request->id_estudiante;
        $id = $request->id_proyecto;

        if (Auth::user()->id_tipo_usuario != 4){
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ], 403);
        }

        $proyectoAsignado = DB::table('proyectos_asignados')
            ->where('id_proyecto', $id)
            ->first();

        if (!$proyectoAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto asignado no encontrado'
            ], 404);
        }

        DB::table('aplicaciones')
            ->where('id_proyecto', $id)
            ->where('id_estudiante', $id_estudiante)
            ->update(['id_estado_aplicacion' => 6]);

        return response()->json([
            'success' => true,
            'message' => 'La solicitud de expulsión ha sido enviada correctamente al coordinador de la carrera'
        ]);
    }

    /**
     * Confirma la expulsión de un estudiante de un proyecto asignado.
     *
     * Este método permite a un coordinador expulsar a un estudiante de un proyecto asignado. Primero, valida que el usuario
     * autenticado sea un coordinador. Luego, busca si el proyecto está asignado al estudiante. Si se encuentra, elimina la
     * asignación del proyecto al estudiante, actualiza el estado de la aplicación del estudiante a "denegada" (id_estado_aplicacion = 5),
     * y finalmente incrementa los cupos disponibles para el proyecto.
     *
     * @param \Illuminate\Http\Request $request Datos del estudiante y el proyecto.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación.
     */
    public function confirmarExpulsion(Request $request) {
        $id_estudiante = $request->id_estudiante;
        $id = $request->id_proyecto;

        if (Auth::user()->id_tipo_usuario != 2){
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ], 403);
        }

        $proyectoAsignado = DB::table('proyectos_asignados')
            ->where('id_proyecto', $id)
            ->first();

        if (!$proyectoAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto asignado no encontrado'
            ], 404);
        }

        DB::table('proyectos_asignados')
            ->where('id_proyecto', $id)
            ->where('id_estudiante', $id_estudiante)
            ->delete();

        DB::table('aplicaciones')
            ->where('id_proyecto', $id)
            ->where('id_estudiante', $id_estudiante)
            ->update(['id_estado_aplicacion' => 5]);

        DB::table('proyectos')
            ->where('id', $id)
            ->increment('cupos_disponibles');

        return response()->json([
            'success' => true,
            'message' => 'Estudiante eliminado del proyecto'
        ], 200);
    }
}
