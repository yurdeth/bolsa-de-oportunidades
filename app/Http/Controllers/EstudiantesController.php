<?php

namespace App\Http\Controllers;

use App\Models\Estudiantes;
use App\Models\User;
use App\Rules\FormatCarnetRule;
use App\Rules\FormatEmailEstudianteRule;
use App\Rules\PhoneNumberRule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EstudiantesController extends Controller {
    /**
     * Muestra una lista de todos los estudiantes registrados.
     *
     * Este método verifica si el usuario autenticado tiene los permisos adecuados para acceder a esta funcionalidad.
     * Solo los usuarios con tipo de usuario 1 (administrador) o 2 (coordinador) pueden utilizar esta ruta.
     * Si el usuario no tiene los permisos necesarios, devuelve un mensaje de error.
     * En caso de éxito, recupera la información de los estudiantes y la devuelve en una respuesta JSON.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos de los estudiantes
     * o un mensaje de error si el usuario no tiene permisos.
     */
    public function index(): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id_tipo_usuario != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiantes = (new User)->getInfoEstudiante(null);

        return response()->json([
            'success' => true,
            'data' => $estudiantes
        ]);
    }

    /**
     * Registra un nuevo estudiante en la base de datos.
     *
     * Este método permite que un usuario se registre como estudiante en la plataforma.
     * Los datos del estudiante se validan con las reglas definidas en el método.
     * Si los datos no son válidos, se devuelve un mensaje de error con los campos que no cumplen con las reglas.
     * Si el teléfono ingresado ya está registrado, se devuelve un mensaje de error.
     * Si el correo electrónico ingresado ya está registrado, se devuelve un mensaje de error.
     * Si el registro es exitoso, se crea un nuevo usuario y se genera un token de acceso.
     *
     * @param \Illuminate\Http\Request $request Los datos del estudiante a registrar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el mensaje de éxito y los datos del estudiante registrado
     * o un mensaje de error si los datos no son válidos.
     */
    public function store(Request $request): JsonResponse {
        $rules = [
            'id_carrera' => 'required|integer|exists:carreras,id',
            'carnet' => ['required', 'string', 'max:10', 'unique:estudiantes', new FormatCarnetRule()],
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'anio_estudio' => 'required|integer',
            'telefono' => ['string', 'max:20', 'unique:estudiantes', new PhoneNumberRule()],
            'direccion' => 'required|string',
            'email' => ['required', 'email', 'unique:usuarios', new FormatEmailEstudianteRule()],
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ];

        $messages = [
            'id_carrera.required' => 'El campo carrera es obligatorio',
            'id_carrera.integer' => 'El campo carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'carnet.required' => 'El campo carnet es obligatorio',
            'carnet.string' => 'El campo carnet debe ser una cadena de texto',
            'carnet.max' => 'El campo carnet debe tener un máximo de 10 caracteres',
            'carnet.unique' => 'El carnet ingresado ya está registrado',
            'nombres.required' => 'El campo nombres es obligatorio',
            'nombres.string' => 'El campo nombres debe ser una cadena de texto',
            'nombres.max' => 'El campo nombres debe tener un máximo de 100 caracteres',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'apellidos.string' => 'El campo apellidos debe ser una cadena de texto',
            'apellidos.max' => 'El campo apellidos debe tener un máximo de 100 caracteres',
            'anio_estudio.required' => 'El campo año de estudio es obligatorio',
            'anio_estudio.integer' => 'El campo año de estudio debe ser un número entero',
            'telefono.string' => 'El campo teléfono debe ser una cadena de texto',
            'telefono.max' => 'El campo teléfono debe tener un máximo de 20 caracteres',
            'telefono.unique' => 'El teléfono ingresado ya está registrado',
            'direccion.required' => 'El campo dirección es obligatorio',
            'direccion.string' => 'El campo dirección debe ser una cadena de texto',
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
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $telefono = str_starts_with($request->telefono, "+503") ? $request->telefono : "+503 " . $request->telefono;
        $telefono = preg_replace('/(\+503)\s?(\d{4})(\d{4})/', '$1 $2-$3', $telefono);

        $user = DB::table('estudiantes')
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
            'telefono' => $telefono,
            'direccion' => $request->direccion
        ]);

        $tokenResult = $user->createToken('Personal Access Token');

        // Configurar la expiración del token
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $data = [
            'user' => $user,
            'token' => $tokenResult->accessToken, // Token de acceso
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at, // Fecha de expiración
        ];

        return response()->json([
            'message' => 'Estudiante registrado correctamente',
            'data' => $data,
            'success' => true,
        ], 201);
    }

    /**
     * Muestra la información de un estudiante específico.
     *
     * Este método verifica si el usuario autenticado tiene los permisos adecuados para acceder a esta funcionalidad.
     * Solo los usuarios con tipo de usuario 1 (administrador), 2 (coordinador) o el propio estudiante pueden utilizar esta ruta.
     * Si el usuario no tiene los permisos necesarios, devuelve un mensaje de error.
     * Si el estudiante no se encuentra en la base de datos, también se devuelve un mensaje de error.
     *
     * @param int $id El ID del estudiante a mostrar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del estudiante
     * o un mensaje de error si el estudiante no se encuentra.
     */
    public function show($id): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id_tipo_usuario != 2 && Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiante = (new User)->getInfoEstudiante($id);

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

    /**
     * Actualiza la información de un estudiante específico.
     *
     * Este método verifica si el usuario autenticado tiene los permisos adecuados para acceder a esta funcionalidad.
     * Solo los usuarios con tipo de usuario 1 (administrador), 2 (coordinador) o el propio estudiante pueden utilizar esta ruta.
     * Si el usuario no tiene los permisos necesarios, devuelve un mensaje de error.
     * Si el estudiante no se encuentra en la base de datos, también se devuelve un mensaje de error.
     * Los datos del estudiante se validan con las reglas definidas en el método.
     * Si los datos no son válidos, se devuelve un mensaje de error con los campos que no cumplen con las reglas.
     * Si el teléfono ingresado ya está registrado, se devuelve un mensaje de error.
     * Si el correo electrónico ingresado ya está registrado, se devuelve un mensaje de error.
     * Si la actualización es exitosa, se devuelve un mensaje de éxito y los datos del estudiante actualizados.
     *
     * @param \Illuminate\Http\Request $request Los datos del estudiante a actualizar.
     * @param int $id El ID del estudiante a actualizar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el mensaje de éxito y los datos del estudiante actualizados
     * o un mensaje de error si los datos no son válidos.
     */
    public function update(Request $request, $id): JsonResponse {
        if (Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiante = Estudiantes::where('id_usuario', $id)->first();

        if (is_null($estudiante)) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        $rules = [
            'id_carrera' => 'integer|exists:carreras,id',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'anio_estudio' => 'required|integer',
            'telefono' => 'string|max:20|unique:estudiantes',
            'direccion' => 'required|string',
        ];

        $messages = [
            'id_carrera.integer' => 'El campo id_carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'nombres.required' => 'El campo nombres es obligatorio',
            'nombres.string' => 'El campo nombres debe ser una cadena de texto',
            'nombres.max' => 'El campo nombres debe tener un máximo de 100 caracteres',
            'apellidos.required' => 'El campo apellidos es obligatorio',
            'apellidos.string' => 'El campo apellidos debe ser una cadena de texto',
            'apellidos.max' => 'El campo apellidos debe tener un máximo de 100 caracteres',
            'anio_estudio.required' => 'El campo año de estudio es obligatorio',
            'anio_estudio.integer' => 'El campo año de estudio debe ser un número entero',
            'direccion.required' => 'El campo dirección es obligatorio',
            'direccion.string' => 'El campo dirección debe ser una cadena de texto',
            'telefono.string' => 'El campo teléfono debe ser una cadena de texto',
            'telefono.max' => 'El campo teléfono debe tener un máximo de 20 caracteres',
            'telefono.unique' => 'El teléfono ingresado ya está registrado',
        ];

        $telefono_actual = DB::table('estudiantes')
            ->select('telefono')
            ->where('id_usuario', $id)
            ->first();

        if ($request->has('telefono')) {
            if ($request->telefono != $telefono_actual->telefono) {
                $rules['telefono'] = 'string|max:20|unique:estudiantes';

                $telefono = str_starts_with($request->telefono, "+503") ? $request->telefono : "+503 " . $request->telefono;
                $telefono = preg_replace('/(\+503)\s?(\d{4})(\d{4})/', '$1 $2-$3', $telefono);

                $user = DB::table('estudiantes')
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

                $estudiante->telefono = $telefono;
            } else{
                $rules['telefono'] = 'string|max:20';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($request->has('id_carrera')) {
            $estudiante->id_carrera = $request->id_carrera;
        }

        if ($request->has('carnet')) {
            $estudiante->carnet = $request->carnet;
        }

        if ($request->has('nombres')) {
            $estudiante->nombres = $request->nombres;
        }

        if ($request->has('apellidos')) {
            $estudiante->apellidos = $request->apellidos;
        }

        if ($request->has('anio_estudio')) {
            $estudiante->anio_estudio = $request->anio_estudio;
        }

        if ($request->has('direccion')) {
            $estudiante->direccion = $request->direccion;
        }

        if ($request->has('email')) {
            $estudiante->email = $request->email;
        }

        $estudiante->save();

        return response()->json([
            'success' => true,
            'data' => $estudiante
        ]);
    }

    /**
     * Elimina un estudiante específico de la base de datos.
     *
     * Este método verifica si el usuario autenticado tiene los permisos adecuados para acceder a esta funcionalidad.
     * Solo los usuarios con tipo de usuario 1 (administrador) o el propio estudiante pueden utilizar esta ruta.
     * Si el usuario no tiene los permisos necesarios, devuelve un mensaje de error.
     * Si el estudiante no se encuentra en la base de datos, también se devuelve un mensaje de error.
     * Si la eliminación es exitosa, se devuelve un mensaje de éxito.
     *
     * @param int $id El ID del estudiante a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el mensaje de éxito
     * o un mensaje de error si el estudiante no se encuentra.
     */
    public function destroy($id): JsonResponse {
        if (Auth::user()->id_tipo_usuario != 1 && Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $estudiante = User::find($id);

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
