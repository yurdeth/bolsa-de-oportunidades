<?php

namespace App\Http\Controllers;

use App\Models\Proyectos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProyectosController extends Controller {
    public function index() {
        $proyectos = ((new Proyectos())->getProyetos(null));

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    public function indexBelongs()
    {
        $proyectos = Proyectos::all()
            ->with('empresa_table')
            ->with('estado_oferta_table')
            ->with('modalidad_trabajo_table')
            ->with('tipo_proyecto_table')
            ->with('carrera_table');

        if ($proyectos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron proyectos'
            ], 404);
        }

        $proyectos = $proyectos->map(function ($proyecto) {
            $proyecto->requisitos = explode(',', $proyecto->requisitos);
            return $proyecto;
        });
    }

    public function findByEmpresa($id)
    {
        $proyectos = Proyectos::where('id_empresa', $id)
            ->with('empresa_table')
            ->with('estado_oferta_table')
            ->with('modalidad_trabajo_table')
            ->with('tipo_proyecto_table')
            ->with('carrera_table')
            ->get();

        if ($proyectos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron proyectos'
            ], 404);
        }

        $proyectos = $proyectos->map(function ($proyecto) {
            $proyecto->requisitos = explode(',', $proyecto->requisitos);
            return $proyecto;
        });

        return response()->json([
            'success' => true,
            'data' => $proyectos
        ]);
    }

    public function store(Request $request) {
        if (Auth::user()->id_tipo_usuario != 4) { // <- Solamente las empresas pueden crear proyectos
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $rules = [
            'id_empresa' => 'integer|exists:empresas,id',
            'id_estado_oferta' => 'integer|exists:estados_oferta,id',
            'id_modalidad' => 'integer|exists:modalidades_trabajo,id',
            'id_tipo_proyecto' => 'integer|exists:tipos_proyecto,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'requisitos' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'fecha_limite_aplicacion' => 'date',
            'estado_proyecto' => 'boolean',
            'cupos_disponibles' => 'integer',
            'ubicacion' => 'required|string',
        ];

        $messages = [
            'id_empresa.integer' => 'El campo id_empresa debe ser un número entero',
            'id_empresa.exists' => 'La empresa seleccionada no existe',
            'id_estado_oferta.integer' => 'El campo id_estado_oferta debe ser un número entero',
            'id_estado_oferta.exists' => 'El estado de oferta seleccionado no existe',
            'id_modalidad.integer' => 'El campo id_modalidad debe ser un número entero',
            'id_modalidad.exists' => 'La modalidad seleccionada no existe',
            'id_tipo_proyecto.integer' => 'El campo id_tipo_proyecto debe ser un número entero',
            'id_tipo_proyecto.exists' => 'El tipo de proyecto seleccionado no existe',
            'id_carrera.integer' => 'El campo id_carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'titulo.required' => 'El campo título es obligatorio',
            'titulo.string' => 'El campo título debe ser una cadena de texto',
            'titulo.max' => 'El campo título debe tener un máximo de 200 caracteres',
            'descripcion.required' => 'El campo descripción es obligatorio',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto',
            'requisitos.required' => 'El campo requisitos es obligatorio',
            'requisitos.string' => 'El campo requisitos debe ser una cadena de texto',
            'fecha_inicio.required' => 'El campo fecha_inicio es obligatorio',
            'fecha_inicio.date' => 'El campo fecha_inicio debe ser una fecha',
            'fecha_fin.required' => 'El campo fecha_fin es obligatorio',
            'fecha_fin.date' => 'El campo fecha_fin debe ser una fecha',
            'fecha_limite_aplicacion.date' => 'El campo fecha_limite_aplicacion debe ser una fecha',
            'estado_proyecto.boolean' => 'El campo estado_proyecto debe ser un booleano',
            'cupos_disponibles.integer' => 'El campo cupos_disponibles debe ser un número entero',
            'ubicacion.required' => 'El campo ubicacion es obligatorio',
            'ubicacion.string' => 'El campo ubicacion debe ser una cadena de texto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $proyecto = Proyectos::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ], 201);
    }

    public function show($id): JsonResponse {
        $proyecto = ((new Proyectos())->getProyetos($id));

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    public function update(Request $request, $id) {
        if (Auth::user()->id_tipo_usuario != 4) { // <- Solamente la empresa dueña del proyecto puede modificarlo
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }
        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $validations = [];
        $rules = [
            'id_empresa' => 'integer|exists:empresas,id',
            'id_estado_oferta' => 'integer|exists:estados_oferta,id',
            'id_modalidad' => 'integer|exists:modalidades_trabajo,id',
            'id_tipo_proyecto' => 'integer|exists:tipos_proyecto,id',
            'id_carrera' => 'integer|exists:carreras,id',
            'titulo' => 'string|max:200',
            'descripcion' => 'string',
            'requisitos' => 'string',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'fecha_limite_aplicacion' => 'date',
            'estado_proyecto' => 'boolean',
            'cupos_disponibles' => 'integer',
            'ubicacion' => 'string',
        ];

        $messages = [
            'id_empresa.integer' => 'El campo id_empresa debe ser un número entero',
            'id_empresa.exists' => 'La empresa seleccionada no existe',
            'id_estado_oferta.integer' => 'El campo id_estado_oferta debe ser un número entero',
            'id_estado_oferta.exists' => 'El estado de oferta seleccionado no existe',
            'id_modalidad.integer' => 'El campo id_modalidad debe ser un número entero',
            'id_modalidad.exists' => 'La modalidad seleccionada no existe',
            'id_tipo_proyecto.integer' => 'El campo id_tipo_proyecto debe ser un número entero',
            'id_tipo_proyecto.exists' => 'El tipo de proyecto seleccionado no existe',
            'id_carrera.integer' => 'El campo id_carrera debe ser un número entero',
            'id_carrera.exists' => 'La carrera seleccionada no existe',
            'titulo.string' => 'El campo título debe ser una cadena de texto',
            'titulo.max' => 'El campo título debe tener un máximo de 200 caracteres',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto',
            'requisitos.string' => 'El campo requisitos debe ser una cadena de texto',
            'fecha_inicio.date' => 'El campo fecha_inicio debe ser una fecha',
            'fecha_fin.date' => 'El campo fecha_fin debe ser una fecha',
            'fecha_limite_aplicacion.date' => 'El campo fecha_limite_aplicacion debe ser una fecha',
            'estado_proyecto.boolean' => 'El campo estado_proyecto debe ser un booleano',
            'cupos_disponibles.integer' => 'El campo cupos_disponibles debe ser un número entero',
            'ubicacion.string' => 'El campo ubicacion debe ser una cadena de texto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $proyecto->save();

        return response()->json([
            'success' => true,
            'data' => $proyecto
        ]);
    }

    public function destroy($id) {
        // Solamente los estudiantes no podrán eliminar proyectos
        if (Auth::user()->id_tipo_usuario == 3) {
            return response()->json([
                'success' => false,
                'message' => 'Ruta no encontrada en este servidor'
            ]);
        }

        $proyecto = Proyectos::find($id);

        if (is_null($proyecto)) {
            return response()->json([
                'success' => false,
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $proyecto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proyecto eliminado'
        ]);
    }
}
