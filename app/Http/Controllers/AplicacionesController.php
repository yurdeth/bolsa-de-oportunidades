<?php

namespace App\Http\Controllers;

use App\Models\Aplicaciones;
use App\Models\EstadoSolicitud;
use App\Models\Proyectos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AplicacionesController extends Controller {
    public function index() {
        $aplicaciones = Aplicaciones::all();

        if ($aplicaciones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay aplicaciones registradas'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aplicaciones
        ]);
    }

    public function store(Request $request) {
        $rules = [
            'id_estudiante' => 'integer|exists:estudiantes,id',
            'id_proyecto' => 'integer|exists:proyectos,id',
            'id_estado_aplicacion' => 'integer|exists:estados_aplicacion,id',
            'comentarios_empresa' => 'string',
        ];

        $messages = [
            'id_estudiante.integer' => 'El estudiante debe ser un número entero',
            'id_estudiante.exists' => 'El estudiante no existe',
            'id_proyecto.integer' => 'El proyecto debe ser un número entero',
            'id_proyecto.exists' => 'El proyecto no existe',
            'id_estado_aplicacion.integer' => 'El estado de la aplicación debe ser un número entero',
            'id_estado_aplicacion.exists' => 'El estado de la aplicación no existe',
            'comentarios_empresa.string' => 'Los comentarios de la empresa deben ser una cadena de texto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $estadoOferta = Proyectos::find($request->id_proyecto)->id_estado_oferta;

        if ($estadoOferta != 1) {
            return response()->json([
                'success' => false,
                'message' => 'La oferta de este proyecto no está activa'
            ], 400);
        }

        $estudianteAplica = Aplicaciones::where('id_estudiante', $request->id_estudiante)
            ->where('id_proyecto', $request->id_proyecto)
            ->first();

        if ($estudianteAplica) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has aplicado a este proyecto'
            ], 400);
        }

        $proyectoAsignado = DB::table('proyectos_asignados')
            ->where('id_proyecto', $request->id_proyecto)
            ->where('id_estudiante', $request->id_estudiante)
            ->first();

        $id_usuario = DB::table('estudiantes')
            ->where('id', $request->id_estudiante)
            ->value('id_usuario');

        if ($proyectoAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante ya tiene asignado un proyecto'
            ], 400);
        }

        $aplicacion = Aplicaciones::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ], 201);
    }

    public function showByEstadoAplicaion($id_estudiante) {
        $aplicaciones_activas = Aplicaciones::where('id_estudiante', $id_estudiante)->where('id_estado_aplicacion', 1)->get();

        if ($aplicaciones_activas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay aplicaciones activas para el estudiante'
            ], 404);
        }

        $proyectos_activos = Proyectos::whereIn('id', $aplicaciones_activas->pluck('id_proyecto'))->get();
    }

    public function show($id) {
        $aplicacion = Aplicaciones::find($id);

        if (is_null($aplicacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Aplicación no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ]);
    }

    public function findByEstudiante($id_estudiante) {
        $aplicaciones = Aplicaciones::where('id_estudiante', $id_estudiante)->get();

        if ($aplicaciones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay aplicaciones registradas para el estudiante'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $aplicaciones
        ]);
    }

    public function update(Request $request, $id) {
        $aplicacion = Aplicaciones::find($id);

        if (is_null($aplicacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Aplicación no encontrada'
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_estudiante' => 'integer|exists:estudiantes,id',
            'id_proyecto' => 'integer|exists:proyectos,id',
            'id_estado_aplicacion' => 'integer|exists:estados_aplicacion,id',
            'comentarios_empresa' => 'string',
        ];

        $messages = [
            'id_estudiante.integer' => 'El estudiante debe ser un número entero',
            'id_estudiante.exists' => 'El estudiante no existe',
            'id_proyecto.integer' => 'El proyecto debe ser un número entero',
            'id_proyecto.exists' => 'El proyecto no existe',
            'id_estado_aplicacion.integer' => 'El estado de la aplicación debe ser un número entero',
            'id_estado_aplicacion.exists' => 'El estado de la aplicación no existe',
            'comentarios_empresa.string' => 'Los comentarios de la empresa deben ser una cadena de texto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $aplicacion->id_estudiante = $request->id_estudiante;
        $aplicacion->id_proyecto = $request->id_proyecto;
        $aplicacion->id_estado_aplicacion = $request->id_estado_aplicacion;
        $aplicacion->comentarios_empresa = $request->comentarios_empresa;

        $aplicacion->save();

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ]);
    }

    public function destroy($id) {
        $aplicacion = Aplicaciones::find($id);

        if (is_null($aplicacion)) {
            return response()->json([
                'success' => false,
                'message' => 'Aplicación no encontrada'
            ], 404);
        }

        $aplicacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aplicación eliminada'
        ]);
    }

    private function actualizarEstadoSolicitud(Request $request, int $estadoAprobado, int $estadoDenegado): JsonResponse {
        $data = $request->all();
        $estadoSolicitud = Aplicaciones::where('id_proyecto', $data['id_proyecto'])
            ->where('id_estudiante', $data['id_estudiante'])
            ->first();

        if (!$estadoSolicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
        }

        $proyecto = Proyectos::find($data['id_proyecto']);
        $cuposDisponibles = $proyecto->cupos_disponibles;

        if ($data['approved'] == 'true' && $cuposDisponibles == 0) {
            // Rechazar todas las solicitudes que no fueron aprobadas
            $solicitudesNoAprobadas = Aplicaciones::where('id_proyecto', $data['id_proyecto'])
                ->where('id_estado_aplicacion', '!=', $estadoAprobado)
                ->get();

            $solicitudesNoAprobadas->each(function ($solicitud) use ($estadoDenegado) {
                $solicitud->id_estado_aplicacion = $estadoDenegado;
                $solicitud->save();
            });

            return response()->json([
                'success' => false,
                'message' => 'No hay más cupos disponibles para este proyecto. Se han rechazado todas las solicitudes'
            ], 400);
        }

        $estadoSolicitud->id_estado_aplicacion = $data['approved'] == 'true' ? $estadoAprobado : $estadoDenegado;
        if ($data['approved'] == 'true' && Auth::user()->id_tipo_usuario == 2) {
            $this->asignarEstudianteProyecto($request);
            $this->actualizarCupos($request);
        }

        $estadoSolicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud actualizada'
        ]);
    }

    public function gestionarSolicitudes(Request $request): JsonResponse {
        if (Auth::user()->id_tipo_usuario == 2) {
            return $this->solicitudesCoordinador($request);
        }

        if (Auth::user()->id_tipo_usuario == 4) {
            return $this->solicitudesEmpresa($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ruta no encontrada en este servidor'
        ]);
    }

    public function solicitudesEmpresa(Request $request): JsonResponse {
        return $this->actualizarEstadoSolicitud($request, 2, 5);
    }

    public function solicitudesCoordinador(Request $request): JsonResponse {
        return $this->actualizarEstadoSolicitud($request, 3, 4);
    }

    public function asignarEstudianteProyecto(Request $request) {
        return ((new ProyectosAsignadosController())->store($request));
    }

    public function actualizarCupos(Request $request): JsonResponse {
        $proyecto = Proyectos::find($request->id_proyecto);

        if ($proyecto->cupos_disponibles == 0 && $proyecto->id_estado_oferta == 4) {
            return response()->json([
                'success' => false,
                'message' => 'No hay cupos disponibles'
            ], 400);
        }

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $proyecto->cupos_disponibles = $proyecto->cupos_disponibles - 1;
        $proyecto->id_estado_oferta = 4;
        $proyecto->save();

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }
}
