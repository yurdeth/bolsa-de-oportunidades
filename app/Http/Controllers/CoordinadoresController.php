<?php

namespace App\Http\Controllers;

use App\Models\Coordinadores;
use App\Models\User;
use App\Rules\PhoneNumberRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $coordinadores = (new User)->getInfoCoordinador(null);

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
            'telefono' => ['string', 'max:20', 'unique:coordinadores', new PhoneNumberRule()],
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
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
            'password.min' => 'El campo contraseña debe tener un mínimo de 8 caracteres',
            'password_confirmation.required' => 'El campo confirmación de contraseña es obligatorio',
            'password_confirmation.same' => 'Las contraseñas no coinciden'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $telefono = str_starts_with($request->telefono, "+503") ? $request->telefono : "+503 " . $request->telefono;
        $telefono = preg_replace('/(\+503)\s?(\d{4})(\d{4})/', '$1 $2-$3', $telefono);

        $user = DB::table('coordinadores')
            ->select('telefono')
            ->where('telefono', $telefono)
            ->first();

        if ($user) {
            return response()->json([
                'message' => 'El teléfono ingresado ya está en uso',
                'status' => false
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
            'telefono' => $telefono
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

        $coordinador = (new User)->getInfoCoordinador($id);

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
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id != $id) {
            return response()->json([
                'message' => 'Ruta no encontrada en este servidor',
                'status' => false
            ]);
        }

        $coordinador = Coordinadores::where('id_usuario', $id)->first();

        if (is_null($coordinador)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        $rules = [
            'nombres' => 'string|max:100',
            'apellidos' => 'string|max:100',
            'id_departamento' => 'integer|exists:departamento,id',
            'telefono' => ['string', 'max:20', new PhoneNumberRule()],
            'id_carrera' => 'integer|exists:carreras,id',
        ];

        $messages = [
            'nombres.string' => 'El campo nombres debe ser una cadena de texto',
            'nombres.max' => 'El campo nombres debe tener un máximo de 100 caracteres',
            'apellidos.string' => 'El campo apellidos debe ser una cadena de texto',
            'apellidos.max' => 'El campo apellidos debe tener un máximo de 100 caracteres',
            'id_departamento.integer' => 'El campo departamento debe ser un número entero',
            'id_departamento.exists' => 'El departamento seleccionado no existe',
            'telefono.string' => 'El campo teléfono debe ser una cadena de texto',
            'telefono.max' => 'El campo teléfono debe tener un máximo de 20 caracteres',
            'telefono.unique' => 'El teléfono ingresado ya está registrado'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $telefono = str_starts_with($request->telefono, "+503") ? $request->telefono : "+503 " . $request->telefono;
        $telefono = preg_replace('/(\+503)\s?(\d{4})(\d{4})/', '$1 $2-$3', $telefono);

        $user = DB::table('coordinadores')
            ->select('telefono')
            ->where('telefono', $telefono)
            ->where('id_usuario', '!=', $id)
            ->first();

        if ($user) {
            return response()->json([
                'message' => 'El teléfono ingresado ya está en uso',
                'status' => false
            ], 400);
        }

        if ($request->has('nombres')) {
            $coordinador->nombres = $request->nombres;
        }

        if ($request->has('apellidos')) {
            $coordinador->apellidos = $request->apellidos;
        }

        if ($request->has('id_carrera')) {
            $coordinador->id_carrera = $request->id_carrera;
        }

        if ($request->has('telefono')) {
            $coordinador->telefono = $telefono;
        }

        if ($request->has('password')) {
            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
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

        $user = User::where('id', $id)->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'Coordinador no encontrado',
                'status' => false
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Coordinador eliminado correctamente',
            'status' => true
        ]);
    }
}
