<?php

namespace App\Http\Controllers;

use App\Models\EstadoSolicitud;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EstadoSolicitudController extends Controller {
    /**
     * Actualiza el estado de una solicitud de aplicación de un estudiante a un proyecto, según la decisión de la empresa.
     *
     * Este método permite que una empresa apruebe o deniegue una solicitud de un estudiante para un proyecto.
     * La ruta está restringida a usuarios con tipo de usuario 4 (empresa).
     * Si el usuario autenticado no es una empresa, se devuelve un mensaje de error con un código de estado adecuado.
     * Si la solicitud no se encuentra en la base de datos, también se devuelve un mensaje de error.
     * Dependiendo de la decisión ('approved' => 'true' o 'false'), se actualiza el estado de la solicitud a aprobado o denegado.
     *
     * @param \Illuminate\Http\Request $request Los datos de la solicitud de aplicación.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la solicitud actualizada
     * o un mensaje de error si no se encuentra la solicitud o si el usuario no tiene permisos.
     */
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

    /**
     * Obtiene el estado de una solicitud de aplicación de un estudiante a un proyecto.
     *
     * Este método permite obtener el estado de una solicitud de aplicación de un estudiante a un proyecto.
     * La ruta está restringida a usuarios con tipo de usuario 4 (empresa).
     * Si el usuario autenticado no es una empresa, se devuelve un mensaje de error con un código de estado adecuado.
     * Si la solicitud no se encuentra en la base de datos, también se devuelve un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request Los datos de la solicitud de aplicación.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la solicitud
     * o un mensaje de error si no se encuentra la solicitud o si el usuario no tiene permisos.
     */
    public function getEstadoSolicitudEmpresa(Request $request): JsonResponse {
        $data = $request->all();
        $estadoSolicitud = new EstadoSolicitud();

        $response = $estadoSolicitud->getEstadoSolicitud($data['id']);

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Actualiza el estado de una solicitud de aplicación de un estudiante a un proyecto, según la decisión del coordinador.
     *
     * Este método permite que un coordinador apruebe o deniegue una solicitud de un estudiante para un proyecto.
     * La ruta está restringida a usuarios con tipo de usuario 2 (coordinador).
     * Si el usuario autenticado no es un coordinador, se devuelve un mensaje de error con un código de estado adecuado.
     * Si la solicitud no se encuentra en la base de datos, también se devuelve un mensaje de error.
     * Dependiendo de la decisión ('approved' => 'true' o 'false'), se actualiza el estado de la solicitud a aprobado o denegado.
     *
     * @param \Illuminate\Http\Request $request Los datos de la solicitud de aplicación.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la solicitud actualizada
     * o un mensaje de error si no se encuentra la solicitud o si el usuario no tiene permisos.
     */
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

    /**
     * Actualiza el estado de una solicitud de aplicación de un estudiante a un proyecto, según la decisión del coordinador.
     *
     * Este método permite que un coordinador apruebe o deniegue una solicitud de un estudiante para un proyecto.
     * La ruta está restringida a usuarios con tipo de usuario 2 (coordinador).
     * Si el usuario autenticado no es un coordinador, se devuelve un mensaje de error con un código de estado adecuado.
     * Si la solicitud no se encuentra en la base de datos, también se devuelve un mensaje de error.
     * Dependiendo de la decisión ('approved' => 'true' o 'false'), se actualiza el estado de la solicitud a aprobado o denegado.
     *
     * @param \Illuminate\Http\Request $request Los datos de la solicitud de aplicación.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la solicitud actualizada
     * o un mensaje de error si no se encuentra la solicitud o si el usuario no tiene permisos.
     */
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

    /**
     * Obtiene el estado de una solicitud de aplicación de un estudiante a un proyecto.
     *
     * Este método permite obtener el estado de una solicitud de aplicación de un estudiante a un proyecto.
     * La ruta está restringida a usuarios con tipo de usuario 2 (coordinador).
     * Si el usuario autenticado no es un coordinador, se devuelve un mensaje de error con un código de estado adecuado.
     * Si la solicitud no se encuentra en la base de datos, también se devuelve un mensaje de error.
     *
     * @param \Illuminate\Http\Request $request Los datos de la solicitud de aplicación.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado de la solicitud
     * o un mensaje de error si no se encuentra la solicitud o si el usuario no tiene permisos.
     */
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
