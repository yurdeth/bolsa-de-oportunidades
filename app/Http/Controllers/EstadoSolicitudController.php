<?php

namespace App\Http\Controllers;

use App\Models\EstadoSolicitud;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EstadoSolicitudController extends Controller {
    public function solicitudesEmpresa(Request $request): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 4) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $data = $request->all();
        $estadoSolicitud = EstadoSolicitud::where('id_proyecto', $data['id_proyecto'])
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

    public function getEstadoSolicitudEmpresa(Request $request): JsonResponse {
        $data = $request->all();
        $estadoSolicitud = new EstadoSolicitud();

        $response = $estadoSolicitud->getEstadoSolicitud($data['id']);

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    public function aprobarSolicitudCoordinador(Request $request): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $data = $request->all();
        $estadoSolicitud = new EstadoSolicitud();

        $estadoSolicitud->id_proyecto = $data['id_proyecto'];

        $estadoSolicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud aprobada'
        ]);
    }

    public function rechazarSolicitudCoordinador(Request $request): JsonResponse {
        // Limitar la asignación de proyectos a coordinadores unicamente
        if (Auth::user()->id_tipo_usuario != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $data = $request->all();
        $estadoSolicitud = new EstadoSolicitud();

        $estadoSolicitud->id_proyecto = $data['id_proyecto'];

        $estadoSolicitud->save();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud rechazada'
        ]);
    }

    public function getEstadoSolicitudCoordinador(Request $request): JsonResponse {
        $data = $request->all();
        $estadoSolicitud = new EstadoSolicitud();

        $response = $estadoSolicitud->getEstadoSolicitud($data['id']);

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }
}
