<?php

namespace App\Http\Controllers;

use App\Models\Estudiantes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EstudiantesController extends Controller {
    public function index(): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id_tipo_usuario != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiantes = Estudiantes::all();

        if ($estudiantes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes registrados'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estudiantes
        ]);
    }

    public function store(Request $request): JsonResponse {
        $rules = [
//            'id_usuario' => 'required|integer|exists:users,id',
            'id_carrera' => 'required|integer|exists:carreras,id',
            'carnet' => 'required|string|max:10|unique:estudiantes',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'anio_estudio' => 'required|integer',
            'telefono' => 'string|max:20|unique:estudiantes',
            'direccion' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_tipo_usuario' => 3,
            'estado_usuario' => true,
            'fecha_registro' => Carbon::now(),
        ]);

        $id_usuario = $user->id;

        $estudiante = Estudiantes::create([
            'id_usuario' => $id_usuario,
            'id_carrera' => $request->id_carrera,
            'carnet' => $request->carnet,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'anio_estudio' => $request->anio_estudio,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion
        ]);

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ], 201);
    }

    public function show($id): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id_tipo_usuario != 2 && Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiante = Estudiantes::find($id);

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ]);
    }

    public function update(Request $request, $id): JsonResponse {
        if (Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiante = Estudiantes::find($id);

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_usuario' => 'integer|exists:users,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'carnet' => 'required|string|max:10|unique:estudiantes,carnet,' . $id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'anio_estudio' => 'required|integer',
            'telefono' => 'string|max:20|unique:estudiantes',
            'direccion' => 'required|string'
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
            $estudiante->$key = $request->$key;
        }

        $estudiante->save();

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ]);
    }

    public function destroy($id): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiante = Estudiantes::find($id);

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $estudiante->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estudiante eliminado exitosamente'
        ]);
    }
}
