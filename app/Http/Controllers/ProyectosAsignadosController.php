<?php

namespace App\Http\Controllers;

use App\Models\ProyectosAsignados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProyectosAsignadosController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        if (Auth::user()->id_tipo_usuario != 2) { // <- Solamente el coordinador puede asignar proyectos
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ], 403);
        }

        // Validar los datos
        $rules = [
            'id_proyecto' => 'required|integer|exists:proyectos,id',
            'id_estudiante' => 'required|integer|exists:estudiantes,id',
        ];

        $messages = [
            'id_proyecto.required' => 'El proyecto es requerido',
            'id_proyecto.integer' => 'El proyecto debe ser un número entero',
            'id_proyecto.exists' => 'El proyecto no existe',
            'id_estudiante.required' => 'El estudiante es requerido',
            'id_estudiante.integer' => 'El estudiante debe ser un número entero',
            'id_estudiante.exists' => 'El estudiante no existe',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        // Asignar el proyecto al estudiante
        $proyectoAsignado = ProyectosAsignados::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $proyectoAsignado
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
