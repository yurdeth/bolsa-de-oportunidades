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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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

        return response()->json([
            'success' => true,
            'data' => $proyectoAsignado
        ], 201);
    }

    /**
     * Display the specified resource.
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $id_estudiante) {
        //
    }

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
