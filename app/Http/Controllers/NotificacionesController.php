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
     * Muestra las notificaciones según el tipo de usuario autenticado.
     *
     * Este método devuelve diferentes tipos de notificaciones basadas en el rol del usuario autenticado:
     * - Empresas (id_tipo_usuario = 4): recupera las notificaciones específicas para la empresa.
     * - Coordinadores (id_tipo_usuario = 2): recupera las notificaciones específicas para el coordinador.
     * - Otros usuarios (por defecto): recupera notificaciones sobre solicitudes de aplicación activas de estudiantes a proyectos.
     *
     * Dependiendo del tipo de usuario, se llama a métodos distintos para obtener las notificaciones pertinentes y se devuelve
     * una respuesta JSON con los datos de las notificaciones correspondientes.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con las notificaciones correspondientes al tipo de usuario.
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

    /**
     * Devuelve la cantidad de notificaciones según el tipo de usuario autenticado.
     *
     * Este método devuelve la cantidad de notificaciones basadas en el rol del usuario autenticado:
     * - Empresas (id_tipo_usuario = 4): recupera la cantidad de notificaciones específicas para la empresa.
     * - Coordinadores (id_tipo_usuario = 2): recupera la cantidad de notificaciones específicas para el coordinador.
     * - Otros usuarios (por defecto): recupera la cantidad de notificaciones sobre solicitudes de aplicación activas de estudiantes a proyectos.
     *
     * Dependiendo del tipo de usuario, se llama a métodos distintos para obtener la cantidad de notificaciones pertinentes y se devuelve
     * una respuesta JSON con la cantidad de notificaciones correspondientes.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la cantidad de notificaciones correspondientes al tipo de usuario.
     */
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
     * Crea una nueva notificación.
     *
     * Este método valida los datos proporcionados para la notificación, asegurándose de que todos los campos sean correctos
     * según las reglas de validación definidas. Si los datos son válidos, crea una nueva notificación en la base de datos.
     * Si hay algún error de validación, se devuelve una respuesta JSON con los detalles del error.
     *
     * @param array $itemNotificacion Datos de la notificación a crear, que incluyen el tipo, usuario, proyecto y mensaje.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado de la creación de la notificación, o un mensaje de error si los datos no son válidos.
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
     * Muestra una notificación específica.
     *
     * Este método recupera una notificación específica según el ID proporcionado. Si la notificación existe, se devuelve
     * una respuesta JSON con los datos de la notificación. Si la notificación no existe, se devuelve un mensaje de error.
     *
     * @param string $id ID de la notificación a mostrar.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de la notificación, o un mensaje de error si la notificación no existe.
     */
    public function show(string $id) {
        //
    }

    /**
     * Actualiza una notificación específica.
     *
     * Este método actualiza una notificación específica según el ID proporcionado. Si la notificación existe, se actualiza
     * el campo 'leido' a true y se devuelve una respuesta JSON con los datos de la notificación actualizada. Si la notificación
     * no existe, se devuelve un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request Datos de la notificación a actualizar, que incluyen el ID de la notificación.
     * @param string $id ID de la notificación a actualizar.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de la notificación actualizada, o un mensaje de error si la notificación no existe.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Marca una notificación como leída.
     *
     * Este método valida los datos proporcionados para marcar una notificación como leída, asegurándose de que el ID de la notificación
     * sea correcto según las reglas de validación definidas. Si los datos son válidos, actualiza el campo 'leido' de la notificación
     * a true y se devuelve una respuesta JSON con los datos de la notificación actualizada. Si hay algún error de validación, se devuelve
     * una respuesta JSON con los detalles del error.
     *
     * @param \Illuminate\Http\Request $request Datos de la notificación a marcar como leída, que incluyen el ID de la notificación.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado de marcar la notificación como leída, o un mensaje de error si los datos no son válidos.
     */
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
     * Elimina una notificación específica.
     *
     * Este método elimina una notificación específica según el ID proporcionado. Si la notificación existe, se elimina
     * de la base de datos y se devuelve una respuesta JSON con un mensaje de éxito. Si la notificación no existe, se devuelve
     * un mensaje de error.
     *
     * @param string $id ID de la notificación a eliminar.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de éxito, o un mensaje de error si la notificación no existe.
     */
    public function destroy(string $id) {
        //
    }

    /**
     * Obtiene el ID de la empresa a la que pertenece el usuario autenticado.
     *
     * Este método recupera el ID de la empresa a la que pertenece el usuario autenticado, consultando la base de datos
     * y obteniendo el ID de la empresa correspondiente al ID del usuario autenticado.
     *
     * @return int ID de la empresa a la que pertenece el usuario autenticado.
     */
    private function getIdEmpresa() {
        $id_empresa = DB::table('usuarios')
            ->select('empresas.id as id_empresa')
            ->join('empresas', 'usuarios.id', '=', 'empresas.id_usuario')
            ->where('usuarios.id', Auth::user()->id)
            ->first();

        return $id_empresa->id_empresa;
    }

    /**
     * Obtiene el ID del coordinador al que pertenece el usuario autenticado.
     *
     * Este método recupera el ID del coordinador al que pertenece el usuario autenticado, consultando la base de datos
     * y obteniendo el ID del coordinador correspondiente al ID del usuario autenticado.
     *
     * @return int ID del coordinador al que pertenece el usuario autenticado.
     */
    private function getIdCoordinador() {
        $id_coordinador = DB::table('usuarios')
            ->select('coordinadores.id as id_coordinador')
            ->join('coordinadores', 'usuarios.id', '=', 'coordinadores.id_usuario')
            ->where('usuarios.id', Auth::user()->id)
            ->first();

        return $id_coordinador->id_coordinador;
    }
}
