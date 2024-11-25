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

        return response()->json(['status' => 'success', 'message' => 'no se ha encontrado una ruta para el usuario'], 404);
    }
}