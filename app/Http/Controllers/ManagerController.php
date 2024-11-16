<?php

namespace App\Http\Controllers;

use App\Models\Managers;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller {
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
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
     * Display the specified resource.
     */
    public function show(string $id) {
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
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

    public function partial(Request $request, $id) {
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
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
