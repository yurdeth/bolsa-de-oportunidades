<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Proyectos;
use App\Models\Aplicaciones;
use App\Models\Estudiantes;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
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
                ->select('estados_oferta.nombre_estado as rol', DB::raw('count(*) as total'))
                ->groupBy('estados_oferta.nombre_estado')
                ->get();

            $dataAplicacionesByStatus = DB::table('aplicaciones')
                ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
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
            return response()->json(['status' => 'success', 'message' => 'este no puede acceder a esta ruta'], 403);
        }

        if ($user->id_tipo_usuario == 4) {

        }

        return response()->json(['status' => 'success', 'message' => 'no se ha encontrado una ruta para el usuario'], 404);
    }
}
