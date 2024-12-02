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
    /**
     * Muestra una lista de todas las aplicaciones.
     *
     * Este método recupera todas las aplicaciones registradas en la base de datos.
     * Si no hay aplicaciones registradas, devuelve una respuesta JSON indicando
     * que no se encontraron registros con un código de estado 404.
     * Si hay aplicaciones, devuelve una respuesta JSON con los datos de las aplicaciones
     * y un indicador de éxito.
     *
     * @return JsonResponse Respuesta JSON que contiene los datos de las aplicaciones
     * o un mensaje de error si no se encontraron registros.
     */
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

    /**
     * Crea una nueva aplicación de estudiante a un proyecto.
     *
     * Este método valida los datos de entrada, asegurándose de que cumplan con las reglas de negocio,
     * incluyendo la existencia de relaciones entre estudiantes, proyectos y estados de aplicación.
     * Además, verifica que el proyecto esté activo, que el estudiante no haya aplicado previamente
     * al proyecto, y que el estudiante no tenga un proyecto ya asignado. Si todas las condiciones
     * se cumplen, se registra la aplicación en la base de datos.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la aplicación.
     * @return JsonResponse Respuesta JSON con los datos de la nueva aplicación o mensajes
     * de error en caso de fallas de validación o restricciones de negocio.
     */
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

    /**
     * Muestra las aplicaciones activas de un estudiante según su estado.
     *
     * Este método recupera todas las aplicaciones activas de un estudiante específico
     * basándose en su estado de aplicación. Si no se encuentran aplicaciones activas,
     * se devuelve un mensaje indicando que no hay registros disponibles. Además,
     * se obtienen los proyectos relacionados con las aplicaciones activas.
     *
     * @param int $id_estudiante El identificador del estudiante para el cual se buscan las aplicaciones activas.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con las aplicaciones activas o un mensaje de error si no existen.
     */
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

    /**
     * Muestra una aplicación específica.
     *
     * Este método recupera una aplicación específica basada en su identificador.
     * Si la aplicación no se encuentra, se devuelve un mensaje indicando que no se encontró
     * el registro con un código de estado 404. Si se encuentra la aplicación, se devuelve
     * una respuesta JSON con los datos de la aplicación y un indicador de éxito.
     *
     * @param int $id El identificador de la aplicación que se desea recuperar.
     * @return JsonResponse Respuesta JSON con los datos de la aplicación o un mensaje de error si no se encontró.
     */
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

    /**
     * Muestra las aplicaciones de un estudiante.
     *
     * Este método recupera todas las aplicaciones de un estudiante específico basándose en su identificador.
     * Si no se encuentran aplicaciones para el estudiante, se devuelve un mensaje indicando que no hay registros
     * disponibles. Si se encuentran aplicaciones, se devuelve una respuesta JSON con los datos de las aplicaciones
     * y un indicador de éxito.
     *
     * @param int $id_estudiante El identificador del estudiante para el cual se buscan las aplicaciones.
     * @return JsonResponse Respuesta JSON con las aplicaciones del estudiante o un mensaje de error si no existen.
     */
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

    /**
     * Muestra las aplicaciones de un proyecto.
     *
     * Este método recupera todas las aplicaciones de un proyecto específico basándose en su identificador.
     * Si no se encuentran aplicaciones para el proyecto, se devuelve un mensaje indicando que no hay registros
     * disponibles. Si se encuentran aplicaciones, se devuelve una respuesta JSON con los datos de las aplicaciones
     * y un indicador de éxito.
     *
     * @param int $id_proyecto El identificador del proyecto para el cual se buscan las aplicaciones.
     * @return JsonResponse Respuesta JSON con las aplicaciones del proyecto o un mensaje de error si no existen.
     */
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

    /**
     * Elimina una aplicación específica.
     *
     * Este método elimina una aplicación específica basada en su identificador.
     * Si la aplicación no se encuentra, se devuelve un mensaje indicando que no se encontró
     * el registro con un código de estado 404. Si se elimina la aplicación, se devuelve
     * una respuesta JSON con un mensaje de éxito.
     *
     * @param int $id El identificador de la aplicación que se desea eliminar.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
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

    /**
     * Actualiza el estado de una solicitud.
     *
     * Este método actualiza el estado de una solicitud de aplicación a un proyecto.
     * Si la solicitud no se encuentra, se devuelve un mensaje indicando que no se encontró
     * el registro con un código de estado 404. Si se actualiza el estado de la solicitud,
     * se devuelve una respuesta JSON con un mensaje de éxito.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la solicitud.
     * @param int $estadoAprobado El identificador del estado de solicitud aprobado.
     * @param int $estadoDenegado El identificador del estado de solicitud denegado.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
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

    /**
     * Gestiona las solicitudes de aplicación a proyectos.
     *
     * Este método gestiona las solicitudes de aplicación a proyectos, actualizando el estado
     * de las solicitudes según la aprobación o rechazo de las mismas. Si el usuario autenticado
     * es un coordinador, se actualiza el estado de las solicitudes de los estudiantes. Si el usuario
     * autenticado es una empresa, se actualiza el estado de las solicitudes de las empresas.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la solicitud.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
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

    /**
     * Actualiza el estado de una solicitud de aplicación a un proyecto.
     *
     * Este método actualiza el estado de una solicitud de aplicación a un proyecto.
     * Si el usuario autenticado es un coordinador, se actualiza el estado de las solicitudes
     * de los estudiantes. Si el usuario autenticado es una empresa, se actualiza el estado
     * de las solicitudes de las empresas.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la solicitud.
     * @param int $estadoAprobado El identificador del estado de solicitud aprobado.
     * @param int $estadoDenegado El identificador del estado de solicitud denegado.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
    public function solicitudesEmpresa(Request $request): JsonResponse {
        return $this->actualizarEstadoSolicitud($request, 2, 5);
    }

    /**
     * Actualiza el estado de una solicitud de aplicación a un proyecto.
     *
     * Este método actualiza el estado de una solicitud de aplicación a un proyecto.
     * Si el usuario autenticado es un coordinador, se actualiza el estado de las solicitudes
     * de los estudiantes. Si el usuario autenticado es una empresa, se actualiza el estado
     * de las solicitudes de las empresas.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la solicitud.
     * @param int $estadoAprobado El identificador del estado de solicitud aprobado.
     * @param int $estadoDenegado El identificador del estado de solicitud denegado.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
    public function solicitudesCoordinador(Request $request): JsonResponse {
        return $this->actualizarEstadoSolicitud($request, 3, 4);
    }

    /**
     * Asigna un estudiante a un proyecto.
     *
     * Este método asigna un estudiante a un proyecto específico.
     * Si el estudiante ya está asignado a un proyecto, se devuelve un mensaje indicando
     * que el estudiante ya tiene un proyecto asignado. Si el proyecto no tiene cupos disponibles,
     * se devuelve un mensaje indicando que no hay cupos disponibles. Si se asigna el estudiante
     * al proyecto, se devuelve una respuesta JSON con un mensaje de éxito.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la asignación.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
    public function asignarEstudianteProyecto(Request $request) {
        return ((new ProyectosAsignadosController())->store($request));
    }

    /**
     * Actualiza los cupos de un proyecto.
     *
     * Este método actualiza los cupos de un proyecto específico.
     * Si el proyecto no tiene cupos disponibles, se devuelve un mensaje indicando
     * que no hay cupos disponibles. Si se actualizan los cupos del proyecto,
     * se devuelve una respuesta JSON con un mensaje de éxito.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la actualización.
     * @return JsonResponse Respuesta JSON con un mensaje de éxito o un mensaje de error si no se encontró.
     */
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
