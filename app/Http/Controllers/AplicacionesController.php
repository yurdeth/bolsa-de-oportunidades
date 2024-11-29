<?php

namespace App\Http\Controllers;

use App\Models\Aplicaciones;
use App\Models\EstadoSolicitud;
use App\Models\Proyectos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AplicacionesController extends Controller
{
    public function index()
    {
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

    public function store(Request $request)
    {
        $rules = [
            'id_estudiante' => 'integer|exists:estudiantes,id',
            'id_proyecto' => 'integer|exists:proyectos,id',
            'id_estado_aplicacion' => 'integer|exists:estados_aplicacion,id',
            'comentarios_empresa' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $aplicacion = Aplicaciones::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ], 201);
    }

    public function showByEstadoAplicaion($id_estudiante)
    {
        $aplicaciones_activas = Aplicaciones::where('id_estudiante', $id_estudiante)->where('id_estado_aplicacion', 1)->get();

        if ($aplicaciones_activas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay aplicaciones activas para el estudiante'
            ], 404);
        }

        $proyectos_activos = Proyectos::whereIn('id', $aplicaciones_activas->pluck('id_proyecto'))->get();
    }

    public function show($id)
    {
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


    public function findByEstudiante($id_estudiante)
    {
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

    public function update(Request $request, $id)
    {
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

        foreach ($rules as $key => $value) {
            if ($request->has($key)) {
                $validations[$key] = $value;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            $aplicacion->$key = $request->$key;
        }

        $aplicacion->save();

        return response()->json([
            'success' => true,
            'data' => $aplicacion
        ]);
    }

    public function destroy($id)
    {
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

    public function gestionarSolicitures(Request $request): JsonResponse {
        if (Auth::user()->id_tipo_usuario == 2){
            $this->solicitudesCoordinador($request);
        }

        if (Auth::user()->id_tipo_usuario == 4){
            $this->solicitudesEmpresa($request);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ruta no encontrada en este servidor'
        ]);
    }

    public function solicitudesEmpresa(Request $request): JsonResponse {
        $data = $request->all();
        Log::info($data);
        $estadoSolicitud = Aplicaciones::where('id_proyecto', $data['id_proyecto'])
            ->where('id_estudiante', $data['id_estudiante'])
            ->first();

        if (!$estadoSolicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
        }

        if ($data['approved'] == 'true') {
            $estadoSolicitud->id_estado_aplicacion = 2; // <- Aprobada por la empresa
        } else {
            $estadoSolicitud->id_estado_aplicacion = 5; // <- Denegada por la empresa
        }

        $estadoSolicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud actualizada'
        ]);
    }

    public function solicitudesCoordinador(Request $request): JsonResponse {
        $data = $request->all();
        Log::info($data);
        $estadoSolicitud = Aplicaciones::where('id_proyecto', $data['id_proyecto'])
            ->where('id_estudiante', $data['id_estudiante'])
            ->first();

        if (!$estadoSolicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ]);
        }

        if ($data['approved'] == 'true') {
            $estadoSolicitud->id_estado_aplicacion = 3; // <- Aprobada por el coordinador
        } else {
            $estadoSolicitud->id_estado_aplicacion = 4; // <- Rechazada por el coordinador
        }

        $estadoSolicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud actualizada'
        ]);
    }
}
