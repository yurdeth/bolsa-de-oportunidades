<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Empresas;
use App\Models\Estudiantes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller {
    /*
        Recibir: email, password
        Retornar: token, token_type, expires_at, información del usuario
    */
    public function login(Request $r) {
        $user = $r->email;
        $pass = $r->password;

        $user_current = User::where('email', $user)->first();
        if (!$user_current) {
            return response()->json([
                'errors' => [
                    'email' => ['Correo no registrado']
                ],
                'status' => false
            ], 401);
        }

        if (Auth::attempt(['email' => $user, 'password' => $pass])) {
            $tokenResult = Auth::user()->createToken('personal Access Token');
            $data['user'] = $user_current;

            if (!$user_current->estado_usuario) {
                return response()->json([
                    'errors' => [
                        'email' => ['Usuario deshabilitado']
                    ],
                    'status' => false
                ], 401);
            }

            $data['token'] = $tokenResult->accessToken;
            $data['token_type'] = 'Bearer';
            $data['expires_at'] = $tokenResult->token->expires_at;

            return response()->json(['data' => $data, 'status' => true], 200);
        } else {
            return response()->json([
                'errors' => [
                    'password' => ['Contraseña incorrecta']
                ],
                'status' => false
            ], 401);
        }
    }

    public function access_token(Request $r) {
        // Obtener el usuario autenticado
        $user = $r->user();

        // Generar un nuevo token personal
        $tokenResult = $user->createToken('Personal Access Token');

        // Configurar la expiración del token
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        // Preparar la respuesta
        $data = [
            'user' => $user,
            'token' => $tokenResult->accessToken, // Token de acceso
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at, // Fecha de expiración
        ];

        return response()->json(['data' => $data, 'status' => true], 200);
    }

    public function logout(Request $r) {
        $r->user()->token()->revoke();
        return response()->json(['message' => 'Sesión finalizada', 'status' => true], 200);
    }

    public function me(Request $r) {
        return response()->json(['user' => $r->user(), 'status' => true], 200);
    }

    public function register(Request $r) {
        /*
            Recibir: user_type (estudiante = 3, empresa = 4)
        */

        if ($r->has('user_type') && $r->user_type == 'estudiante' || $r->has('id_tipo_usuario') && $r->id_tipo_usuario == '3') {
            return (new EstudiantesController)->store($r);
            /**
             * Para evitar la duplicidad de código, se puede llamar al método store del controlador EstudiantesController
             * Datos requeridos para registrar estudiantes:
             * - user_type (estudiante), o id_tipo_usuario (3)
             * - email
             * - password
             * - id_tipo_usuario (3)
             * - estado_usuario (true)
             * - fecha_registro (hoy)
             * - token_recuperacion (cualquier cosa, no se ocupa en sí)
             * - token_expiracion (cualquier cosa, no se ocupa en sí)
             * - carnet
             * - nombres
             * - apellidos
             * - id_carrera (traer la lista de carreras a partir del filtrado de departamentos: /api/departamentos, /api/carreras/{id})
             * - año de estudio
             * - teléfono de contacto
             * - dirección de residencia
            */

            /*$rules = [
                'email' => 'required|email|unique:usuarios',
                'password' => 'required',
                'id_tipo_usuario' => 'required',
                'estado_usuario' => 'required',
                'fecha_registro' => 'required',
                'token_recuperacion' => 'required',
                'token_expiracion' => 'required',
                'carnet' => 'required|unique:estudiantes',
                'nombres' => 'required',
                'apellidos' => 'required',
                'id_carrera' => 'required',
                'anio_estudio' => 'required',
                'telefono' => 'required',
                'direccion' => 'required',
            ];

            $messages = [
                'email.required' => 'El correo es requerido',
                'email.email' => 'El correo es invalido',
                'email.unique' => 'El correo ya esta registrado',
                'password.required' => 'La contraseña es requerida',
                'id_tipo_usuario.required' => 'El tipo de usuario es requerido',
                'estado_usuario.required' => 'El estado es requerido',
                'fecha_registro.required' => 'La fecha de registro es requerida',
                'token_recuperacion.required' => 'El token de recuperacion es requerido',
                'token_expiracion.required' => 'El token de expiracion es requerido',
                'carnet.required' => 'El carnet es requerido',
                'carnet.unique' => 'El carnet ya esta registrado',
                'nombres.required' => 'Los nombres son requeridos',
                'apellidos.required' => 'Los apellidos son requeridos',
                'id_carrera.required' => 'La carrera es requerida',
                'anio_estudio.required' => 'El año de estudio es requerido',
                'telefono.required' => 'El telefono es requerido',
                'direccion.required' => 'La direccion es requerida',
            ];
            */
        }

        if ($r->has('user_type') && $r->user_type == 'empresa'  || $r->has('id_tipo_usuario') && $r->id_tipo_usuario == '4') {
            return (new EmpresasController())->store($r);

            /*$rules = [
                'email' => 'required|email|unique:usuarios',
                'password' => 'required',
                'id_tipo_usuario' => 'required',
                'estado_usuario' => 'required',
                'fecha_registro' => 'required',
                'token_recuperacion' => 'required',
                'token_expiracion' => 'required',
                'id_sector' => 'required',
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'sitio_web' => 'required',
                'descripcion' => 'required',
                'logo_url' => 'required'
            ];

            $messages = [
                'email.required' => 'El correo es requerido',
                'email.email' => 'El correo es invalido',
                'email.unique' => 'El correo ya esta registrado',
                'password.required' => 'La contraseña es requerida',
                'id_tipo_usuario.required' => 'El tipo de usuario es requerido',
                'estado_usuario.required' => 'El estado es requerido',
                'fecha_registro.required' => 'La fecha de registro es requerida',
                'token_recuperacion.required' => 'El token de recuperacion es requerido',
                'token_expiracion.required' => 'El token de expiracion es requerido',
                'id_sector.required' => 'El sector es requerido',
                'nombre.required' => 'El nombre es requerido',
                'direccion.required' => 'La direccion es requerida',
                'telefono.required' => 'El telefono es requerido',
                'sitio_web.required' => 'El sitio web es requerido',
                'descripcion.required' => 'La descripcion es requerida',
                'logo_url.required' => 'El logo es requerido',
            ];*/
        }

        /*$r->validate($rules, $messages);

        try {
            DB::beginTransaction();

            $user = User::create([
                'email' => $r->email,
                'password' => Hash::make($r->password),
                'id_tipo_usuario' => $r->user_type == 'estudiante' ? 3 : 4,
                'estado_usuario' => true,
                'fecha_registro' => Carbon::now(),
            ]);

            if ($r->has('user_type') && $r->user_type == 'estudiante') {
                Estudiantes::create([
                    'id_usuario' => $user->id,
                    'carnet' => $r->carnet,
                    'nombres' => $r->nombres,
                    'apellidos' => $r->apellidos,
                    'id_carrera' => $r->id_carrera,
                    'anio_estudio' => $r->anio_estudio,
                    'telefono' => $r->telefono,
                    'direccion' => $r->direccion,
                ]);
            }

            if ($r->has('user_type') && $r->user_type == 'empresa') {
                if ($r->hasFile('logo_url')) {
                    $path = $r->file('logo_url')->store('public/logos');
                    $url = Storage::url($path);
                }

                Empresas::create([
                    'id_usuario' => $user->id,
                    'id_sector' => $r->id_sector,
                    'nombre' => $r->nombre,
                    'direccion' => $r->direccion,
                    'telefono' => $r->telefono,
                    'sitio_web' => $r->sitio_web,
                    'descripcion' => $r->descripcion,
                    'logo_url' => $url,
                ]);
            }

            DB::commit();

            return response(['message' => 'Registro exitoso', 'status' => true], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(['message' => $th->getMessage(), 'status' => false], 500);
        }*/

        return response(['message' => 'Tipo de usuario no válido', 'status' => false], 400);
    }
}
