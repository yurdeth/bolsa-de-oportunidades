<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentsController extends Controller {
    public function index(): JsonResponse {
        $students = User::all();
        if ($students->isEmpty()) {
            $data = [
                'success' => false,
                'message' => 'No hay estudiantes registrados'
            ];
            return response()->json($data, 404);
        }
        return response()->json($students, 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'carnet' => 'required|string|unique:students,carnet',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
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

            // Registrar el token generado en los logs
            Log::info('Token generado: ' . $token);

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

    public function show($id) {
        $student = Students::find($id);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }
        return response()->json($student);
    }

    public function update(Request $request, $id) {
        $student = Students::find($id);

        if ($student) {
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

            $student->update($request->all());

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

    public function partial(Request $request, $id) {
        $student = Students::find($id);

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
                $student->name = $request->name;
            }

            if ($request->has('carnet')) {
                $student->carnet = $request->carnet;
            }

            if ($request->has('email')) {
                $student->email = $request->email;
            }

            if ($request->has('phone_number')) {
                $student->phone_number = $request->phone_number;
            }

            if ($request->has('password')) {
                $student->password = $request->password;
            }

            if ($request->has('career_id')) {
                $student->career_id = $request->career_id;
            }

            $student->save();

            return response()->json([
                'success' => true,
                'data' => $student
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }

    public function destroy($id) {
        $student = Students::find($id);

        if ($student) {
            $student->delete();
            return response()->json([
                'success' => true,
                'message' => 'Student deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }
}

