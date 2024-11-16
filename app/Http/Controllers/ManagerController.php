<?php

namespace App\Http\Controllers;

use App\Models\Managers;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller {
    /**
     * Muestra una lista de todos los coordinadores registrados.
     *
     * Este método verifica si el usuario autenticado tiene permiso para ver la lista de coordinadores.
     * Los permisos se limitan al administrador principal ('1') y al coordinador ('2'). Si el usuario
     * no tiene permiso, devuelve una respuesta JSON con un mensaje de error y un código de estado 403.
     * Luego, obtiene la información de los coordinadores utilizando el método `getManagersInfo` del modelo `User`.
     * Si no hay coordinadores registrados, devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     * Si hay coordinadores registrados, devuelve una respuesta JSON con la información de los coordinadores y un código de estado 201.
     *
     * @return JsonResponse Respuesta JSON con la lista de coordinadores o un mensaje de error.
     */
    public function index(): JsonResponse {
        if (Auth::user()->rol_id != '1' && Auth::user()->rol_id != '2') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe'
            ], 403);
        }

        $managers = (new User())->getManagersInfo();

        if ($managers->isEmpty()) {
            $data = [
                'success' => false,
                'message' => 'No hay coordinadores registrados'
            ];
            return response()->json($data, 404);
        }

        return response()->json([
            "success" => true,
            "managers" => $managers
        ], 201);
    }

    /**
     * Registra un nuevo coordinador en el sistema.
     *
     * Este método valida los datos de la solicitud para registrar un nuevo coordinador. Si la validación falla,
     * devuelve una respuesta JSON con los errores de validación y un código de estado 422. Si la validación es exitosa,
     * crea un nuevo usuario y un nuevo coordinador en la base de datos. Luego, autentica al usuario y genera un token de acceso.
     * Si ocurre un error durante el proceso, devuelve una respuesta JSON con un mensaje de error y un código de estado 500.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del coordinador.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|confirmed|min:6',
            'career_id' => 'required|integer|exists:careers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el coordinador',
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $user = (new UsersController)->store($request, 2);

            $manager = Managers::create([
                "career_id" => $request->career_id,
                "user_id" => $user->id,
            ]);

            $token = $manager->createToken("AuthToken")->accessToken;
            Auth::guard('web')->login($manager);

            return response()->json([
                'success' => true,
                'token' => $token,
                'token_type' => 'Bearer',
                'redirect_to' => url('/'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el coordinador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra la información de un coordinador específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para ver la información de un coordinador específico.
     * Los permisos se limitan al administrador principal ('1'), al coordinador ('2') y al coordinador cuyo ID coincide con el ID de la solicitud.
     * Si el usuario no tiene permiso, devuelve una respuesta JSON con un mensaje de error y un código de estado 403.
     * Luego, obtiene la información del coordinador utilizando el método `getManagersInfoById` del modelo `User`.
     * Si el coordinador no existe, devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     * Si el coordinador existe, devuelve una respuesta JSON con la información del coordinador y un código de estado 201.
     *
     * @param string $id El ID del coordinador.
     * @return JsonResponse Respuesta JSON con la información del coordinador o un mensaje de error.
     */
    public function show(string $id): JsonResponse {
        if (Auth::user()->rol_id != $id &&
            Auth::user()->rol_id != '1' &&
            Auth::user()->rol_id != '2') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe'
            ], 403);
        }

        $manager = (new User())->getManagersInfoById($id);

        if (!$manager) {
            $data = [
                'success' => false,
                'message' => 'Coordinador no encontrado'
            ];
            return response()->json($data, 404);
        }

        return response()->json($manager, 201);
    }

    /**
     * Actualiza la información de un coordinador específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para actualizar la información de un coordinador específico.
     * Los permisos se limitan al coordinador cuyo ID coincide con el ID de la solicitud. Si el usuario no tiene permiso,
     * devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, obtiene la información del coordinador
     * y la actualiza con los datos de la solicitud. Si ocurre un error durante el proceso, devuelve una respuesta JSON con un mensaje de error
     * y un código de estado 500. Si la actualización es exitosa, devuelve una respuesta JSON con un mensaje de éxito y un código de estado 201.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del coordinador.
     * @param string $id El ID del coordinador.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function update(Request $request, string $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
        */

        if (Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $user = User::find($id);
        $manager = Managers::where('user_id', $id)->first();

        if (!$manager) {
            $data = [
                'success' => false,
                'message' => 'Coordinador no encontrado'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $id,
            'password' => 'required|string|confirmed|min:6',
            'career_id' => 'required|integer|exists:careers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el coordinador',
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->password);
            $user->save();

            $manager->career_id = $request->career_id;
            $manager->save();

            return response()->json([
                'success' => true,
                'message' => 'Coordinador actualizado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el coordinador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza parcialmente la información de un coordinador específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para actualizar parcialmente la información de un coordinador específico.
     * Los permisos se limitan al coordinador cuyo ID coincide con el ID de la solicitud. Si el usuario no tiene permiso,
     * devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, obtiene la información del coordinador
     * y actualiza los campos especificados en la solicitud. Si ocurre un error durante el proceso, devuelve una respuesta JSON con un mensaje de error
     * y un código de estado 500. Si la actualización es exitosa, devuelve una respuesta JSON con un mensaje de éxito y un código de estado 201.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del coordinador.
     * @param string $id El ID del coordinador.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function partial(Request $request, string $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
        */

        if (Auth::user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $user = User::find($id);
        $manager = Managers::where('user_id', $id)->first();

        if ($manager) {
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'email' => 'email|unique:users,email' . $id,
                'phone_number' => 'string|unique:users,phone_number,' . $id,
                'password' => 'string|confirmed|min:6',
                'career_id' => 'integer|exists:careers,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el coordinador',
                    'error' => $validator->errors()
                ], 422);
            }

            if ($request->has('name')) {
                $user->name = $request->name;
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
                $manager->career_id = $request->career_id;
            }

            $user->save();
            $manager->save();

            return response()->json([
                'success' => true,
                'message' => 'Coordinador actualizado exitosamente',
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Coordinador no encontrado'
        ], 404);
    }

    /**
     * Elimina un coordinador específico.
     *
     * Este método verifica si el usuario autenticado tiene permiso para eliminar un coordinador específico.
     * Los permisos se limitan al administrador principal ('1') y al coordinador cuyo ID coincide con el ID de la solicitud.
     * Si el usuario no tiene permiso, devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     * Luego, elimina el coordinador de la base de datos. Si el coordinador no existe, devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     * Si el coordinador es eliminado exitosamente, devuelve una respuesta JSON con un mensaje de éxito y un código de estado 200.
     *
     * @param string $id El ID del coordinador.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function destroy(string $id): JsonResponse {
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
                    'message' => 'Coordinador eliminado'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Coordinador no encontrado'
        ], 404);
    }
}
