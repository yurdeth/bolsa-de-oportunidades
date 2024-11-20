<?php

namespace App\Http\Controllers;

use App\Models\Coordinadores;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CoordinadoresController extends Controller {
    public function index(): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1) {
            return response()->json([
//                'message' => 'No tienes permisos para realizar esta acción',
                'message' => 'Ruta no encontrada en este servidor',
                'status' => false
            ]);
        }

        $coordinadores = Coordinadores::all();

        if ($coordinadores->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron coordinadores',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Coordinadores recuperados correctamente',
            'status' => true,
            'data' => $coordinadores
        ]);
    }

    public function store(Request $request): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1) {
            return response()->json([
                'message' => 'Ruta no encontrada en este servidor',
                'status' => false
            ]);
        }

        $rules = [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'id_carrera' => 'required|integer|exists:carreras,id',
            'telefono' => 'string|max:20|unique:coordinadores',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:8'
        ];

        $messages = [
            'nombres.required' => 'El campo nombres es obligatorio',
            'nombres.string' => 'El campo nombres debe ser una cadena de texto',
            'nombres.max' => 'El campo nombres debe tener un máximo de 100 caracteres',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'apellidos.string' => 'El campo apellidos debe ser una cadena de texto',
            'apellidos.max' => 'El campo apellidos debe tener un máximo de 100 caracteres',
            'id_carrera.required' => 'El campo carrera es obligatorio',
            'id_carrera.integer' => 'El campo carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'telefono.string' => 'El campo teléfono debe ser una cadena de texto',
            'telefono.max' => 'El campo teléfono debe tener un máximo de 20 caracteres',
            'telefono.unique' => 'El teléfono ingresado ya está registrado',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida',
            'email.unique' => 'El correo electrónico ingresado ya está registrado',
            'password.required' => 'El campo contraseña es obligatorio',
            'password.string' => 'El campo contraseña debe ser una cadena de texto',
            'password.min' => 'El campo contraseña debe tener un mínimo de 8 caracteres'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_tipo_usuario' => 2,
            'estado_usuario' => true,
            'fecha_registro' => Carbon::now(),
        ]);

        $id_usuario = $user->id;

        $coordinador = Coordinadores::create([
            'id_usuario' => $id_usuario,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'id_carrera' => $request->id_carrera,
            'telefono' => $request->telefono
        ]);

        return response()->json([
            'message' => 'Coordinador creado correctamente',
            'status' => true,
            'data' => $coordinador
        ], 201);
    }

    public function show($id): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1) {
            return response()->json([
//                'message' => 'No tienes permisos para realizar esta acción',
                'message' => 'Ruta no encontrada en este servidor',
                'status' => false
            ]);
        }

        $coordinador = Coordinadores::find($id);

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        return response()->json([
            'message' => 'Coordinador recuperado correctamente',
            'status' => true,
            'data' => $coordinador
        ]);
    }

    public function update(Request $request, $id): JsonResponse {
        if (Auth::user()->id != $id) {
            return response()->json([
//                'message' => 'No tienes permisos para realizar esta acción',
                'message' => 'Ruta no encontrada en este servidor',
                'status' => false
            ]);
        }

        $coordinador = Coordinadores::find($id);

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_usuario' => 'integer|exists:usuarios,id',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'id_departamento' => 'integer|exists:departamento,id',
            'telefono' => 'string|max:20|unique:coordinadores,telefono,' . $id
        ];

        foreach ($rules as $key => $value) {
            if (!$request->has($key)) {
                $validations[$key] = $value;
            }
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        foreach ($validations as $key => $value) {
            $coordinador->$key = $request->$key;
        }

        $coordinador->save();

        return response()->json([
            'message' => 'Coordinador actualizado correctamente',
            'status' => true,
            'data' => $coordinador
        ]);
    }

    public function destroy($id): JsonResponse {
        if (Auth::user()->id != $id && Auth::user()->id_tipo_usuario != 1) {
            return response()->json([
//                'message' => 'No tienes permisos para realizar esta acción',
                'message' => 'Ruta no encontrada en este servidor',
                'status' => false
            ]);
        }

        $coordinador = Coordinadores::find($id);

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        $coordinador->delete();

        return response()->json([
            'message' => 'Coordinador eliminado correctamente',
            'status' => true
        ]);
    }
}
