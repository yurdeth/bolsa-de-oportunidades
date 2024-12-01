<?php

namespace App\Http\Controllers;

use App\Models\Aplicaciones;
use App\Models\Notificaciones;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificacionesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {
        if (Auth::user()->id_tipo_usuario == 4){
            $id_empresa = $this->getIdEmpresa();
            $notificaciones = (new Notificaciones)->getNotificacionesEmpresa($id_empresa);

            return response()->json([
                'success' => true,
                'data' => $notificaciones
            ]);
        }

        if (Auth::user()->id_tipo_usuario == 2){
            $id_coordinador = $this->getIdCoordinador();
            $notificaciones = (new Notificaciones)->getNotificacionesCoordinador($id_coordinador);

            return response()->json([
                'success' => true,
                'data' => $notificaciones
            ]);
        }

        $notificaciones = Aplicaciones::select('aplicaciones.id_estado_aplicacion',
            'aplicaciones.id as id_aplicacion',
            'estudiantes.nombres',
            'estudiantes.apellidos',
            'estados_aplicacion.nombre as estado_aplicacion',
            'proyectos.titulo')
            ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
            ->join('proyectos', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
            ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
            ->where('aplicaciones.id_estado_aplicacion', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notificaciones
        ]);
    }

    public function contarNotificaciones(): JsonResponse {
        if (Auth::user()->id_tipo_usuario == 2){
            $id_coordinador = $this->getIdCoordinador();
            $notificaciones = (new Notificaciones)->getNotificacionesCoordinador($id_coordinador);
            $notificaciones = count($notificaciones);

            return response()->json([
                'success' => true,
                'data' => $notificaciones
            ]);
        }

        if (Auth::user()->id_tipo_usuario == 4){
            $id_empresa = $this->getIdEmpresa();
            $notificaciones = (new Notificaciones)->getNotificacionesEmpresa($id_empresa);
            $notificaciones = count($notificaciones);

            return response()->json([
                'success' => true,
                'data' => $notificaciones
            ]);
        }

        $notificaciones = Aplicaciones::select('aplicaciones.id_estado_aplicacion',
            'aplicaciones.id as id_aplicacion',
            'estudiantes.nombres',
            'estudiantes.apellidos',
            'estados_aplicacion.nombre as estado_aplicacion',
            'proyectos.titulo')
            ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
            ->join('proyectos', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
            ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
            ->where('aplicaciones.id_estado_aplicacion', 1)
            ->get();

        $notificaciones = count($notificaciones);

        return response()->json([
            'success' => true,
            'data' => $notificaciones
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $itemNotificacion): JsonResponse {
        $rules = [
            'id_tipo_notificacion' => 'required|integer|exists:tipo_notificacion,id',
            'id_usuario' => 'required|integer|exists:usuarios,id',
            'id_proyecto' => 'integer|exists:proyectos,id',
            'mensaje' => 'required|string',
        ];

        $messages = [
            'id_tipo_notificacion.required' => 'El tipo de notificación es requerido',
            'id_tipo_notificacion.integer' => 'El tipo de notificación debe ser un número entero',
            'id_tipo_notificacion.exists' => 'El tipo de notificación no existe',
            'id_usuario.required' => 'El usuario es requerido',
            'id_usuario.integer' => 'El usuario debe ser un número entero',
            'id_usuario.exists' => 'El usuario no existe',
            'id_proyecto.integer' => 'El proyecto debe ser un número entero',
            'id_proyecto.exists' => 'El proyecto no existe',
            'id_proyecto.required' => 'El proyecto es requerido',
            'mensaje.required' => 'El mensaje es requerido',
            'mensaje.string' => 'El mensaje debe ser una cadena de texto',
        ];

        $validator = Validator::make([
            'id_tipo_notificacion' => $itemNotificacion['id_tipo_notificacion'],
            'id_usuario' => $itemNotificacion['id_usuario'],
            'id_proyecto' => $itemNotificacion['id_proyecto'],
            'mensaje' => $itemNotificacion['mensaje'],
        ], $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors()
            ], 400);
        }

        // Crear la notificación
        $notificacion = Notificaciones::create([
            'id_tipo_notificacion' => $itemNotificacion['id_tipo_notificacion'],
            'id_usuario' => $itemNotificacion['id_usuario'],
            'id_proyecto' => $itemNotificacion['id_proyecto'],
            'mensaje' => $itemNotificacion['mensaje'],
            'leido' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notificación creada exitosamente',
            'data' => $notificacion
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    public function marcarComoLeida(Request $request): JsonResponse {
        $rules = [
            'id_notificacion' => 'required|integer|exists:notificaciones,id',
        ];

        $messages = [
            'id_notificacion.required' => 'El id de la notificación es requerido',
            'id_notificacion.integer' => 'El id de la notificación debe ser un número entero',
            'id_notificacion.exists' => 'La notificación no existe',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors()
            ], 400);
        }

        $notificacion = Notificaciones::find($request->id_notificacion);
        $notificacion->leido = true;
        $notificacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
            'data' => $notificacion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    private function getIdEmpresa() {
        $id_empresa = DB::table('usuarios')
            ->select('empresas.id as id_empresa')
            ->join('empresas', 'usuarios.id', '=', 'empresas.id_usuario')
            ->where('usuarios.id', Auth::user()->id)
            ->first();

        return $id_empresa->id_empresa;
    }

    private function getIdCoordinador() {
        $id_coordinador = DB::table('usuarios')
            ->select('coordinadores.id as id_coordinador')
            ->join('coordinadores', 'usuarios.id', '=', 'coordinadores.id_usuario')
            ->where('usuarios.id', Auth::user()->id)
            ->first();

        return $id_coordinador->id_coordinador;
    }
}
