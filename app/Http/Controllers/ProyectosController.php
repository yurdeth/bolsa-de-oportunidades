<?php

namespace App\Http\Controllers;

use App\Models\Proyectos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Kreait\Firebase\Factory;


class ProyectosController extends Controller {
    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function index() {
        $proyectos = ((new Proyectos())->getProyectos(null));

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function indexCoordinador() {
        $infoCoordinador = Auth::user()->info_coordinador;
        $id_carrera = $infoCoordinador[0]->id_carrera;

        $query = DB::table('proyectos')
            ->select(
                'proyectos.id as id_proyecto',
                'empresas.nombre as nombre_empresa',
                'proyectos.titulo as titulo_proyecto',
                'proyectos.descripcion as descripcion_proyeto',
                'proyectos.requisitos as requisitos_proyecto',
                'estados_oferta.nombre_estado as estado_oferta',
                'modalidades_trabajo.nombre as modalidad',
                'proyectos.fecha_inicio as fecha_inicio_proyecto',
                'proyectos.fecha_fin as fecha_fin_proyecto',
                'proyectos.fecha_limite_aplicacion as fecha_limite_aplicacion',
                'proyectos.estado_proyecto as estado_proyecto',
                'proyectos.cupos_disponibles as cupos_disponibles',
                'tipos_proyecto.nombre as tipo_proyecto',
                'proyectos.ubicacion as ubicacion_proyecto',
                'carreras.nombre_carrera as nombre_carrera'
            )
            ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
            ->join('estados_oferta', 'proyectos.id_estado_oferta', '=', 'estados_oferta.id')
            ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
            ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
            ->join('carreras', 'proyectos.id_carrera', '=', 'carreras.id')
            ->where('proyectos.id_carrera', $id_carrera);

        $proyectos = $query->get();

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    /**
     * Obtiene la cantidad de proyectos disponibles.
     *
     * Este método recupera la cantidad de proyectos disponibles utilizando el método `count` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con la cantidad de proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y la cantidad de proyectos.
     */
    public function countProjects(): JsonResponse {
        $count_proyectos = Proyectos::count();

        return response()->json([
            'success' => true,
            'cantidad' => $count_proyectos
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function indexBelongs() {
        $proyectos = Proyectos::all();

        if ($proyectos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron proyectos'
            ], 404);
        }

        $proyectos = $proyectos->map(function ($proyecto) {
            $proyecto->requisitos = explode(',', $proyecto->requisitos);
            return $proyecto;
        });

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function findByEmpresa($id) {
        $proyectos = Proyectos::where('id_empresa', $id)
            ->with('empresa_table')
            ->with('estado_oferta_table')
            ->with('modalidad_trabajo_table')
            ->with('tipo_proyecto_table')
            ->with('carrera_table')
            ->get();

        /*        if ($proyectos->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontraron proyectos'
                    ], 404);
                }*/

        $proyectos = $proyectos->map(function ($proyecto) {
            $proyecto->requisitos = explode(',', $proyecto->requisitos);
            return $proyecto;
        });

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    private function sendMessage($title, $body) {
        $serviceAccount = resource_path('bolsadeoportunidades-7c88c-firebase-adminsdk-3gn0r-35810b4c83.json');
        $databaseURL = env('FIREBASE_DB_URL');

        $factory = (new Factory())
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri($databaseURL);

        $messaging = $factory->createMessaging();

        $message = [
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'topic' => 'all'
        ];

        $response = $messaging->send($message);
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function store(Request $request) {
        if (Auth::user()->id_tipo_usuario != 4) { // <- Solamente las empresas pueden crear proyectos
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $rules = [
            'id_empresa' => 'integer|exists:empresas,id',
            'id_estado_oferta' => 'integer|exists:estados_oferta,id',
            'id_modalidad' => 'integer|exists:modalidades_trabajo,id',
            'id_tipo_proyecto' => 'integer|exists:tipos_proyecto,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'requisitos' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'fecha_limite_aplicacion' => 'date',
            'estado_proyecto' => 'boolean',
            'cupos_disponibles' => 'integer',
            'ubicacion' => 'required|string',
        ];

        $messages = [
            'id_empresa.integer' => 'El campo id_empresa debe ser un número entero',
            'id_empresa.exists' => 'La empresa seleccionada no existe',
            'id_estado_oferta.integer' => 'El campo id_estado_oferta debe ser un número entero',
            'id_estado_oferta.exists' => 'El estado de oferta seleccionado no existe',
            'id_modalidad.integer' => 'El campo id_modalidad debe ser un número entero',
            'id_modalidad.exists' => 'La modalidad seleccionada no existe',
            'id_tipo_proyecto.integer' => 'El campo id_tipo_proyecto debe ser un número entero',
            'id_tipo_proyecto.exists' => 'El tipo de proyecto seleccionado no existe',
            'id_carrera.integer' => 'El campo id_carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'titulo.required' => 'El campo título es obligatorio',
            'titulo.string' => 'El campo título debe ser una cadena de texto',
            'titulo.max' => 'El campo título debe tener un máximo de 200 caracteres',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto',
            'requisitos.required' => 'El campo requisitos es obligatorio',
            'requisitos.string' => 'El campo requisitos debe ser una cadena de texto',
            'fecha_inicio.required' => 'El campo fecha_inicio es obligatorio',
            'fecha_inicio.date' => 'El campo fecha_inicio debe ser una fecha',
            'fecha_fin.required' => 'El campo fecha_fin es obligatorio',
            'fecha_fin.date' => 'El campo fecha_fin debe ser una fecha',
            'fecha_limite_aplicacion.date' => 'El campo fecha_limite_aplicacion debe ser una fecha',
            'estado_proyecto.boolean' => 'El campo estado_proyecto debe ser un booleano',
            'cupos_disponibles.integer' => 'El campo cupos_disponibles debe ser un número entero',
            'ubicacion.required' => 'El campo ubicacion es obligatorio',
            'ubicacion.string' => 'El campo ubicacion debe ser una cadena de texto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $proyecto = Proyectos::create($request->all());

        $this->sendMessage(strval($proyecto->titulo), strval($proyecto->descripcion));

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ], 201);

    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function show($id): JsonResponse {
        $proyecto = ((new Proyectos())->getProyectos($id));

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function update(Request $request, $id) {
        if (Auth::user()->id_tipo_usuario != 4) { // <- Solamente la empresa dueña del proyecto puede modificarlo
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }
        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $rules = [
            'id_empresa' => 'integer|exists:empresas,id',
            'id_estado_oferta' => 'integer|exists:estados_oferta,id',
            'id_modalidad' => 'integer|exists:modalidades_trabajo,id',
            'id_tipo_proyecto' => 'integer|exists:tipos_proyecto,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'titulo' => 'string|max:200',
            'descripcion' => 'string',
            'requisitos' => 'string',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'fecha_limite_aplicacion' => 'date',
            'estado_proyecto' => 'boolean',
            'cupos_disponibles' => 'integer',
            'ubicacion' => 'string',
        ];

        $messages = [
            'id_empresa.integer' => 'El campo id_empresa debe ser un número entero',
            'id_empresa.exists' => 'La empresa seleccionada no existe',
            'id_estado_oferta.integer' => 'El campo id_estado_oferta debe ser un número entero',
            'id_estado_oferta.exists' => 'El estado de oferta seleccionado no existe',
            'id_modalidad.integer' => 'El campo id_modalidad debe ser un número entero',
            'id_modalidad.exists' => 'La modalidad seleccionada no existe',
            'id_tipo_proyecto.integer' => 'El campo id_tipo_proyecto debe ser un número entero',
            'id_tipo_proyecto.exists' => 'El tipo de proyecto seleccionado no existe',
            'id_carrera.integer' => 'El campo id_carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'titulo.string' => 'El campo título debe ser una cadena de texto',
            'titulo.max' => 'El campo título debe tener un máximo de 200 caracteres',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto',
            'requisitos.string' => 'El campo requisitos debe ser una cadena de texto',
            'fecha_inicio.date' => 'El campo fecha_inicio debe ser una fecha',
            'fecha_fin.date' => 'El campo fecha_fin debe ser una fecha',
            'fecha_limite_aplicacion.date' => 'El campo fecha_limite_aplicacion debe ser una fecha',
            'estado_proyecto.boolean' => 'El campo estado_proyecto debe ser un booleano',
            'cupos_disponibles.integer' => 'El campo cupos_disponibles debe ser un número entero',
            'ubicacion.string' => 'El campo ubicacion debe ser una cadena de texto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if($request->has('ubicacion')){
            $proyecto->ubicacion = $request->ubicacion;
        }

        if($request->has('cupos_disponibles')){
            $proyecto->cupos_disponibles = $request->cupos_disponibles;
        }

        if ($request->has('id_empresa')) {
            $proyecto->id_empresa = $request->input('id_empresa');
        }
        if ($request->has('id_estado_oferta')) {
            $proyecto->id_estado_oferta = $request->input('id_estado_oferta');
        }
        if ($request->has('id_modalidad')) {
            $proyecto->id_modalidad = $request->input('id_modalidad');
        }
        if ($request->has('id_tipo_proyecto')) {
            $proyecto->id_tipo_proyecto = $request->input('id_tipo_proyecto');
        }
        if ($request->has('id_carrera')) {
            $proyecto->id_carrera = $request->input('id_carrera');
        }
        if ($request->has('titulo')) {
            $proyecto->titulo = $request->input('titulo');
        }
        if ($request->has('descripcion')) {
            $proyecto->descripcion = $request->input('descripcion');
        }
        if ($request->has('requisitos')) {
            $proyecto->requisitos = $request->input('requisitos');
        }

        $proyecto->save();

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function destroy($id) {
        // Solamente los estudiantes no podrán eliminar proyectos
        if (Auth::user()->id_tipo_usuario == 3) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $proyecto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado'
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function getInteresados(Request $request): JsonResponse {
        // Los estudiantes no pueden ver los interesados en un proyecto
        if (Auth::user()->id_tipo_usuario == 3) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $interesados = ((new Proyectos())->getEstudiantesInteresadosEnProyecto($request->id));

        return response()->json([
            'success' => true,
            'data' => $interesados
        ]);
    }

    /**
     * Obtiene la lista de proyectos disponibles.
     *
     * Este método recupera todos los proyectos disponibles utilizando el método `getProyectos` del modelo `Proyectos`.
     * Devuelve una respuesta JSON con los proyectos recuperados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la operación y los proyectos.
     */
    public function getAprobados(Request $request): JsonResponse {
        // Solamente el coordinador puede ver los estudiantes que han sido aprobados por la empresa
        if (Auth::user()->id_tipo_usuario != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $infoCoordinador = Auth::user()->info_coordinador;
        $id_carrera = $infoCoordinador[0]->id_carrera;

        $interesados = ((new Proyectos())->getEstudiantesAprobadosEnProyecto($request->id, $id_carrera));

        return response()->json([
            'success' => true,
            'data' => $interesados
        ]);
    }
}
