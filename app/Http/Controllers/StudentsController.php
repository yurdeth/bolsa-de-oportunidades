<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller {
    public function index() {
        if (Auth::user()->rol_id != '1' && Auth::user()->rol_id != '2') {
            return redirect()->route('inicio');
        }

        $students = (new User())->getStudentsInfo();

        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes registrados'
            ], 404);
        }

        return response()->json($students, 201);
    }

    public function store(Request $request) {
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

    public function show($id) {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
            3. El coordinador ('2')
        */
        if (Auth::user()->rol_id != $id &&
            Auth::user()->rol_id != '1' &&
            Auth::user()->rol_id != '2') {
            return redirect()->route('inicio');
        }

        $student = (new User())->getStudentsInfoById($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }
        return response()->json($student);
    }

    public function update(Request $request, $id) {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
        */
        if (Auth::user()->id != $id &&
            Auth::user()->roles_id != 1) {
            return redirect()->route('inicio');
        }

        $student = User::find($id);

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
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
        */
        if (Auth::user()->id != $id && Auth::user()->roles_id != 1) {
            return redirect()->route('inicio');
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
                $user->password = bcrypt($request->password);
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
            'message' => 'Student not found'
        ], 404);
    }

    public function destroy($id) {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
            3. El coordinador ('2')
        */
        if (Auth::user()->rol_id != $id &&
            Auth::user()->rol_id != '1' &&
            Auth::user()->rol_id != '2') {
            return redirect()->route('inicio');
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
            'message' => 'Student not found'
        ], 404);
    }
}
