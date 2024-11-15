<?php

namespace App\Http\Controllers;

use App\Models\Managers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $managers = Managers::all();

        if ($managers->isEmpty()) {
            $data = [
                'success' => false,
                'message' => 'No hay coordinadores registrados'
            ];
            return response()->json($data, 404);
        }

        return response()->json($managers, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:managers,email',
            'phone_number' => 'required|string|unique:managers,phone_number',
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
            $manager = Managers::create([
                "name" => $request->name,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "password" => Hash::make($request->password),
                "career_id" => $request->career_id,
                "rol_id" => 1,
                "enabled" => true,
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
        $manager = Managers::find($id);

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
        $manager = Managers::find($id);

        if (!$manager) {
            $data = [
                'success' => false,
                'message' => 'Coordinador no encontrado'
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:managers,email,' . $id,
            'phone_number' => 'required|string|unique:managers,phone_number,' . $id,
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
            $manager->update([
                "name" => $request->name,
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "password" => Hash::make($request->password),
                "career_id" => $request->career_id,
            ]);

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
        $manager = Managers::find($id);

        if ($manager) {
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'email' => 'email|unique:managers,email,' . $id,
                'phone_number' => 'string|unique:managers,phone_number,' . $id,
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

            if ($request->has('name')){
                $manager->name = $request->name;
            }

            if ($request->has('email')){
                $manager->email = $request->email;
            }

            if ($request->has('phone_number')){
                $manager->phone_number = $request->phone_number;
            }

            if ($request->has('password')){
                $manager->password = Hash::make($request->password);
            }

            if ($request->has('career_id')){
                $manager->career_id = $request->career_id;
            }

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
        $manager = Managers::find($id);

        if (!$manager) {
            $data = [
                'success' => false,
                'message' => 'Coordinador no encontrado'
            ];
            return response()->json($data, 404);
        }

        try {
            $manager->delete();
            return response()->json([
                'success' => true,
                'message' => 'Coordinador eliminado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el coordinador',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
