<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller {
    /**
     * Muestra una lista de los estudiantes.
     *
     * Este método verifica si el usuario autenticado tiene el rol de administrador principal ('1')
     * o coordinador ('2'). Si no es así, redirige al usuario a la página de inicio.
     * Luego, obtiene la información de los estudiantes utilizando el método `getStudentsInfo`
     * del modelo `User`. Si no hay estudiantes registrados, devuelve una respuesta JSON con
     * un mensaje de error y un código de estado 404. Si hay estudiantes registrados, devuelve
     * una respuesta JSON con la información de los estudiantes y un código de estado 201.
     *
     * @return JsonResponse Respuesta JSON con la información de los estudiantes
     * o un mensaje de error.
     */
    public function index(): JsonResponse {
        if (Auth::user()->rol_id != '1' && Auth::user()->rol_id != '2') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $students = (new User())->getStudentsInfo();

        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes registrados'
            ], 404);
        }

        return response()->json([
            'success]' => true,
            'students' => $students,
        ], 201);
    }

    /**
     * Almacena un nuevo estudiante en la base de datos.
     *
     * Este método valida los datos de la solicitud para asegurarse de que todos los campos requeridos
     * estén presentes y sean válidos. Si la validación falla, devuelve una respuesta JSON con los
     * errores de validación y un código de estado 422. Si la validación es exitosa, crea un nuevo
     * usuario y un nuevo estudiante en la base de datos, inicia sesión con el nuevo usuario y
     * devuelve un token de acceso en la respuesta JSON con un código de estado 201. Si ocurre un
     * error durante el proceso, devuelve una respuesta JSON con un mensaje de error y un código
     * de estado 500.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del estudiante.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'carnet' => 'required|string|unique:students,carnet',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
            'career_id' => 'required|integer|exists:careers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrarte',
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $user = (new UsersController)->store($request, 4);

            $student = Students::create([
                "carnet" => $request->carnet,
                "career_id" => $request->career_id,
                "user_id" => $user->id,
            ]);

            Auth::login($user);
            $token = $user->createToken("token")->accessToken;

            return response()->json([
                "success" => true,
                "token" => $token,
                "token_type" => "Bearer",
                "redirect_to" => url("/"),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el estudiante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra la información de un estudiante específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para ver la información del estudiante.
     * Los permisos se limitan al propio usuario, al administrador principal ('1') y al coordinador ('2').
     * Si el usuario no tiene permiso, se redirige a la página de inicio.
     * Luego, obtiene la información del estudiante utilizando el método `getStudentsInfoById` del modelo `User`.
     * Si el estudiante no se encuentra, devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     * Si el estudiante se encuentra, devuelve una respuesta JSON con la información del estudiante y un código de estado 200.
     *
     * @param int $id El ID del estudiante.
     * @return JsonResponse Respuesta JSON con la información del estudiante o un mensaje de error.
     */
    public function show(int $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
            3. El coordinador ('2')
        */
        if (Auth::user()->id != $id &&
            Auth::user()->rol_id != '1' &&
            Auth::user()->rol_id != '2') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $student = (new User())->getStudentsInfoById($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'student' => $student
        ]);
    }

    /**
     * Actualiza la información de un estudiante específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para actualizar la información del estudiante.
     * Los permisos se limitan al propio usuario y al administrador principal ('1'). Si el usuario no tiene permiso,
     * se redirige a la página de inicio. Luego, busca al usuario y al estudiante en la base de datos.
     * Si ambos existen, valida los datos de la solicitud. Si la validación falla, devuelve una respuesta JSON
     * con los errores de validación y un código de estado 422. Si la validación es exitosa, actualiza la información
     * del usuario y del estudiante en la base de datos y devuelve una respuesta JSON con la información actualizada
     * del estudiante y un código de estado 200. Si el estudiante no se encuentra, devuelve una respuesta JSON con
     * un mensaje de error y un código de estado 404.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del estudiante.
     * @param int $id El ID del estudiante.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function update(Request $request, int $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
        */
        if (Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $user = User::find($id);
        $student = Students::where('user_id', '=', $id)->first();

        if ($user && $student) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'carnet' => 'required|string',
                'email' => 'required|email',
                'phone_number' => 'required|string',
                'password' => 'required|string',
                'career_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->password);
            $user->save();

            $student->carnet = $request->carnet;
            $student->career_id = $request->career_id;
            $student->save();

            return response()->json([
                'success' => true,
                'data' => $student
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }

    /**
     * Actualiza parcialmente la información de un estudiante específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para actualizar la información del estudiante.
     * Los permisos se limitan al propio usuario y al administrador principal ('1'). Si el usuario no tiene permiso,
     * se devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, busca al usuario y
     * al estudiante en la base de datos. Si el estudiante existe, valida los datos de la solicitud. Si la validación
     * falla, devuelve una respuesta JSON con los errores de validación y un código de estado 422. Si la validación
     * es exitosa, actualiza los campos proporcionados del usuario y del estudiante en la base de datos y devuelve
     * una respuesta JSON con un código de estado 201. Si el estudiante no se encuentra, devuelve una respuesta JSON
     * con un mensaje de error y un código de estado 404.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del estudiante.
     * @param int $id El ID del estudiante.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function partial(Request $request, int $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
        */
        if (Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $user = User::find($id);
        $student = Students::where('user_id', '=', $id)->first();

        if ($student) {
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'carnet' => 'string',
                'email' => 'email',
                'phone_number' => 'string',
                'password' => 'string',
                'career_id' => 'integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('carnet')) {
                $student->carnet = $request->carnet;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('phone_number')) {
                $user->phone_number = $request->phone_number;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->has('career_id')) {
                $student->career_id = $request->career_id;
            }

            $user->save();
            $student->save();

            return response()->json([
                'success' => true,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }

    /**
     * Elimina un estudiante específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para eliminar al estudiante.
     * Los permisos se limitan al propio usuario y al administrador principal ('1'). Si el usuario no tiene permiso,
     * se devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, busca al usuario en la base de datos.
     * Si el usuario existe y no es el usuario con ID 1, lo elimina y devuelve una respuesta JSON con un mensaje de éxito.
     * Si el usuario no se encuentra, devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     *
     * @param int $id El ID del estudiante.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function destroy(int $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
        */
        if (Auth::user()->id != $id &&
            Auth::user()->rol_id != '1') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        if ($id != 1) {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Estudiante eliminado'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }
}
