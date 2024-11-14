<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller {
    public function index(): JsonResponse {
        $students = Students::all();
        if ($students->isEmpty()) {
            $data = [
                'success' => false,
                'message' => 'No students found'
            ];
            return response()->json($data, 404);
        }
        return response()->json($students);
    }

    public function store(Request $request) {
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

        $student = Students::create($request->all());

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not created'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ], 201);
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
            ]);
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
