<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Proyectos;
use App\Models\Aplicaciones;
use App\Models\Estudiantes;
use App\Models\User;
use Dompdf\Dompdf;

class DashboardController extends Controller {
    /**
     * Muestra estadísticas y datos personalizados basados en el rol del usuario autenticado.
     *
     * Este método devuelve diferentes conjuntos de datos y estadísticas según el tipo de usuario:
     * - Administradores (id_tipo_usuario = 1): estadísticas globales de usuarios, proyectos, aplicaciones y sus estados.
     * - Coordinadores (id_tipo_usuario = 2): estadísticas específicas para la carrera que gestionan, incluyendo proyectos y aplicaciones.
     * - Estudiantes (id_tipo_usuario = 3): devuelve un mensaje indicando que no tienen acceso a esta ruta.
     * - Empresas (id_tipo_usuario = 4): devuelve un mensaje indicando que esta sección está en construcción.
     *
     * Si el tipo de usuario no coincide con ninguno de los anteriores, se devuelve un mensaje de error indicando
     * que no hay una ruta configurada para este usuario.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con datos estadísticos o mensajes de error según el tipo de usuario.
     */
    public function index() {
        $user = Auth::user();

        if ($user->id_tipo_usuario == 1) {

            $totalUsers = User::count();
            $activeRequests = Aplicaciones::where('id_estado_aplicacion', 1)->count();
            $activeProjects = Proyectos::where('id_estado_oferta', 1)->count();
            $totalStudent = Estudiantes::count();

            /* Numeros de usuario por rol(id_tipo_usuario) */
            $dataUserbyRol = DB::table('usuarios')
                ->join('tipos_usuario', 'usuarios.id_tipo_usuario', '=', 'tipos_usuario.id')
                ->select('tipos_usuario.nombre as rol', DB::raw('count(*) as total'))
                ->groupBy('tipos_usuario.nombre')
                ->get();

            $dataAplicacionesByStatus = DB::table('aplicaciones')
                ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
                ->select('estados_aplicacion.nombre as status', DB::raw('count(*) as total'))
                ->groupBy('estados_aplicacion.nombre')
                ->get();

            return response()->json([
                'totalUsers' => $totalUsers,
                'activeRequests' => $activeRequests,
                'activeProjects' => $activeProjects,
                'totalStudent' => $totalStudent,
                'dataUserbyRol' => $dataUserbyRol,
                'dataAplicacionesByStatus' => $dataAplicacionesByStatus
            ], 200);
        }

        if ($user->id_tipo_usuario == 2) {
            $info_coordinador = $user->info_coordinador->first();

            if ($info_coordinador == null) {
                return response()->json([
                    'message' => 'No tienes permisos para acceder a esta sección.'
                ], 403);
            }

            $activeProjects = Proyectos::where('id_estado_oferta', 1)->where('id_carrera', $info_coordinador->id_carrera)->count();
            $totalStudent = Estudiantes::where('id_carrera', $info_coordinador->id_carrera)->count();

            $idsProyectos = Proyectos::where('id_carrera', $info_coordinador->id_carrera)->pluck('id');
            $activeRequests = Aplicaciones::where('id_estado_aplicacion', 1)->whereIn('id_proyecto', $idsProyectos)->count();

            /* Numeros de proyecto por estado(id_estado_oferta) */
            $dataProyectosbyStatus = DB::table('proyectos')
                ->join('estados_oferta', 'proyectos.id_estado_oferta', '=', 'estados_oferta.id')
                ->where('proyectos.id_carrera', $info_coordinador->id_carrera)
                ->select('estados_oferta.nombre_estado as rol', DB::raw('count(*) as total'))
                ->groupBy('estados_oferta.nombre_estado')
                ->get();

            $dataAplicacionesByStatus = DB::table('aplicaciones')
                ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
                ->join('proyectos', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
                ->where('proyectos.id_carrera', $info_coordinador->id_carrera)
                ->select('estados_aplicacion.nombre as status', DB::raw('count(*) as total'))
                ->groupBy('estados_aplicacion.nombre')
                ->get();

            return response()->json([
                'activeRequests' => $activeRequests,
                'activeProjects' => $activeProjects,
                'totalStudent' => $totalStudent,
                'dataProyectosbyStatus' => $dataProyectosbyStatus,
                'dataAplicacionesByStatus' => $dataAplicacionesByStatus
            ], 200);
        }

        if ($user->id_tipo_usuario == 3) {
            return response()->json(['status' => 'success', 'message' => 'este no puede acceder a esta ruta'], 200);
        }

        if ($user->id_tipo_usuario == 4) {
            return response()->json(['status' => 'success', 'message' => 'este espacio se encuentra en construccion', 200]);
        }

        return response()->json(['status' => 'success', 'message' => 'no se ha encontrado una ruta para el usuario'], 404);
    }
    public function reporteProyectos(){     
            $proyectos = DB::table('proyectos')
            ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
            ->join('estados_oferta', 'proyectos.id_estado_oferta', '=', 'estados_oferta.id')
            ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
            ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
            ->join('carreras', 'proyectos.id_carrera', '=', 'carreras.id')
            ->select(
                'proyectos.titulo',
                'empresas.nombre as empresa',
                'proyectos.descripcion',
                'proyectos.requisitos',
                'estados_oferta.nombre_estado as estado',
                'modalidades_trabajo.nombre as modalidad',
                'tipos_proyecto.nombre as tipo',
                'carreras.nombre_carrera as carrera',
                'proyectos.fecha_inicio',
                'proyectos.fecha_fin',
                'proyectos.cupos_disponibles'
            )
            ->get();

        $html = "
        <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; }
                    th { background-color: #f4f4f4; text-align: left; }
                </style>
            </head>
            <body>
                <h1>Reporte de Proyectos</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Empresa</th>
                            <th>Descripción</th>
                            <th>Requisitos</th>
                            <th>Estado</th>
                            <th>Modalidad</th>
                            <th>Tipo</th>
                            <th>Carrera</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Cupos Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($proyectos as $proyecto) {
            $html .= "
                        <tr>
                            <td>{$proyecto->titulo}</td>
                            <td>{$proyecto->empresa}</td>
                            <td>{$proyecto->descripcion}</td>
                            <td>{$proyecto->requisitos}</td>
                            <td>{$proyecto->estado}</td>
                            <td>{$proyecto->modalidad}</td>
                            <td>{$proyecto->tipo}</td>
                            <td>{$proyecto->carrera}</td>
                            <td>{$proyecto->fecha_inicio}</td>
                            <td>{$proyecto->fecha_fin}</td>
                            <td>{$proyecto->cupos_disponibles}</td>
                        </tr>";
        }

        $html .= "
                    </tbody>
                </table>
            </body>
        </html>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response()->streamDownload(
            fn() => print($dompdf->output()),
            "reporte_proyectos.pdf"
        );
    }
    public function reporteEmpresas()
{
    $empresas = DB::table('empresas')
    ->join('sectores_industria', 'empresas.id_sector', '=', 'sectores_industria.id')
    ->join('usuarios', 'empresas.id_usuario', '=', 'usuarios.id')
    ->where('usuarios.id_tipo_usuario', 4)  // Agregar el filtro aquí
    ->select(
        'empresas.nombre',
        'usuarios.email as contacto',
        'empresas.direccion',
        'empresas.telefono',
        'empresas.sitio_web',
        'empresas.descripcion',
        'sectores_industria.nombre as sector',
        'empresas.verificada'
    )
    ->get();


    $html = "
    <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f4f4f4; text-align: left; }
            </style>
        </head>
        <body>
            <h1>Reporte de Empresas</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Sitio Web</th>
                        <th>Descripción</th>
                        <th>Sector</th>
                        <th>Verificada</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($empresas as $empresa) {
        $verificada = $empresa->verificada ? 'Sí' : 'No';
        $html .= "
                    <tr>
                        <td>{$empresa->nombre}</td>
                        <td>{$empresa->contacto}</td>
                        <td>{$empresa->direccion}</td>
                        <td>{$empresa->telefono}</td>
                        <td>{$empresa->sitio_web}</td>
                        <td>{$empresa->descripcion}</td>
                        <td>{$empresa->sector}</td>
                        <td>{$verificada}</td>
                    </tr>";
    }

    $html .= "
                </tbody>
            </table>
        </body>
    </html>";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    return response()->streamDownload(
        fn() => print($dompdf->output()),
        "reporte_empresas.pdf"
    );
}


    public function reporteAplicaciones()
{
    $aplicaciones = DB::table('aplicaciones')
    ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
    ->join('proyectos', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
    ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
    ->select(
        'estudiantes.nombres as estudiante',
        'estudiantes.apellidos as apellidos',
        'proyectos.titulo as proyecto',
        'estados_aplicacion.nombre as estado',
        'aplicaciones.comentarios_empresa'
    )
    ->get();


    $html = "
    <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f4f4f4; text-align: left; }
            </style>
        </head>
        <body>
            <h1>Reporte de Aplicaciones</h1>
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Proyecto</th>
                        <th>Estado</th>
                        <th>Comentarios Empresa</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($aplicaciones as $aplicacion) {
        $html .= "
                    <tr>
                        <td>{$aplicacion->estudiante}</td>
                        <td>{$aplicacion->proyecto}</td>
                        <td>{$aplicacion->estado}</td>
                        <td>{$aplicacion->comentarios_empresa}</td>
                    </tr>";
    }

    $html .= "
                </tbody>
            </table>
        </body>
    </html>";


    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    return response()->streamDownload(
        fn() => print($dompdf->output()),
        "reporte_aplicaciones.pdf"
    );
    }


}
