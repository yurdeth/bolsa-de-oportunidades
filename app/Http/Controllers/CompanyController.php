<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller {
    /**
     * Muestra una lista de todas las empresas registradas.
     *
     * Este método verifica si el usuario autenticado tiene permiso para ver la lista de empresas.
     * Los permisos se limitan al administrador principal ('1') y al coordinador ('2'). Si el usuario
     * no tiene permiso, se redirige a la página de inicio. Luego, obtiene la información de las empresas
     * utilizando el método `getCompaniesInfo` del modelo `User`. Si no hay empresas registradas, devuelve
     * una respuesta JSON con un mensaje de error y un código de estado 404. Si hay empresas registradas,
     * devuelve una respuesta JSON con la información de las empresas y un código de estado 201.
     *
     * @return JsonResponse Respuesta JSON con la lista de empresas o un mensaje de error.
     */
    public function index(): JsonResponse {
        if (Auth::user()->rol_id != '1' && Auth::user()->rol_id != '2') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $companies = (new User())->getCompaniesInfo();

        if ($companies->isEmpty()) {
            $data = [
                'success' => false,
                'message' => 'No hay empresas registradas'
            ];
            return response()->json($data, 404);
        }
        return response()->json([
            'success' => true,
            'companies' => $companies
        ], 201);
    }

    /**
     * Registra una nueva empresa en el sistema.
     *
     * Este método valida los datos de la solicitud para registrar una nueva empresa. Si la validación falla,
     * devuelve una respuesta JSON con los errores de validación y un código de estado 422. Si la validación es exitosa,
     * crea un nuevo usuario y una nueva empresa en la base de datos. Luego, autentica al usuario y genera un token de acceso.
     * Si ocurre un error durante el proceso, devuelve una respuesta JSON con un mensaje de error y un código de estado 500.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la empresa.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function store(Request $request): JsonResponse {
        /*Nombre comercial
        Nit
        Tipo de personalidad (natural o jurídica)
        Dirección
        Departamento
        Municipio
        Distrito
        Clasificación
        Rama
        Sector*/

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'nit' => 'required|string|unique:companies,nit',
            'password' => 'required|string',
            'phone_number' => 'required|string|unique:users,phone_number',
            'entity_name_id' => 'required|string',
            'address' => 'required|string',
            'department_id' => 'required|integer|exists:sv_departments,id',
            'municipality_id' => 'required|integer|exists:sv_municipalities,id',
            'district_id' => 'required|integer|exists:sv_districts,id',
            'clasification_id' => 'required|integer|exists:clasifications,id',
            'sector_id' => 'required|integer|exists:sectors,id',
            'brand_id' => 'required|integer|exists:brands,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la empresa',
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $user = (new UsersController)->store($request, 3);

            $company = Companies::create([
                'name' => $request->name,
                'nit' => $request->nit,
                'entity_name_id' => $request->entity_name_id,
                'address' => $request->address,
                'department_id' => $request->department_id,
                'municipality_id' => $request->municipality_id,
                'district_id' => $request->district_id,
                'clasification_id' => $request->clasification_id,
                'sector_id' => $request->sector_id,
                'brand_id' => $request->brand_id,
                'user_id' => $user->id
            ]);

            Auth::login($user);
            $token = $user->createToken("token")->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'token_type' => 'Bearer',
                'redirect_to' => url('/'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra la información de una empresa específica.
     *
     * Este método verifica si el usuario autenticado tiene permiso para ver la información de la empresa.
     * Los permisos se limitan al propio usuario, al administrador principal ('1') y al coordinador ('2').
     * Si el usuario no tiene permiso, se redirige a la página de inicio. Luego, obtiene la información de la empresa
     * utilizando el método `getCompaniesInfoById` del modelo `User`. Si la empresa no se encuentra, devuelve una respuesta
     * JSON con un mensaje de error y un código de estado 404. Si la empresa se encuentra, devuelve una respuesta JSON
     * con la información de la empresa y un código de estado 201.
     *
     * @param string $id El ID de la empresa.
     * @return JsonResponse Respuesta JSON con la información de la empresa o un mensaje de error.
     */
    public function show(string $id): JsonResponse {
        if (Auth::user()->rol_id != $id &&
            Auth::user()->rol_id != '1' &&
            Auth::user()->rol_id != '2') {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $company = (new User())->getCompaniesInfoById($id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'company' => $company
        ], 201);
    }

    /**
     * Actualiza la información de una empresa específica.
     *
     * Este método verifica si el usuario autenticado tiene permiso para actualizar la información de la empresa.
     * Los permisos se limitan al propio usuario y al administrador principal ('1'). Si el usuario no tiene permiso,
     * se devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, busca al usuario y
     * a la empresa en la base de datos. Si la empresa existe, valida los datos de la solicitud. Si la validación
     * falla, devuelve una respuesta JSON con los errores de validación y un código de estado 422. Si la validación
     * es exitosa, actualiza los campos proporcionados del usuario y de la empresa en la base de datos y devuelve
     * una respuesta JSON con un mensaje de éxito y un código de estado 201. Si la empresa no se encuentra, devuelve
     * una respuesta JSON con un mensaje de error y un código de estado 404.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la empresa.
     * @param string $id El ID de la empresa.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function update(Request $request, string $id): JsonResponse {
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
        $company = Companies::where('user_id', '=', $id)->first();

        if ($company) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'nit' => 'required|string|unique:companies,nit',
                'password' => 'required|string',
                'phone_number' => 'required|string|unique:users,phone_number',
                'entity_name_id' => 'required|string',
                'address' => 'required|string',
                'department_id' => 'required|integer|exists:sv_departments,id',
                'municipality_id' => 'required|integer|exists:sv_municipalities,id',
                'district_id' => 'required|integer|exists:sv_districts,id',
                'clasification_id' => 'required|integer|exists:clasifications,id',
                'sector_id' => 'required|integer|exists:sectors,id',
                'brand_id' => 'required|integer|exists:brands,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la empresa',
                    'error' => $validator->errors()
                ], 422);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password = $request->password;
            $user->save();

            $company->nit = $request->nit;
            $company->entity_name_id = $request->entity_name_id;
            $company->address = $request->address;
            $company->district_id = $request->district_id;
            $company->clasification_id = $request->clasification_id;
            $company->sector_id = $request->sector_id;
            $company->brand_id = $request->brand_id;
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada exitosamente',
            ], 201);

        }

        return response()->json([
            'success' => false,
            'message' => 'Empresa no encontrada'
        ], 404);
    }

    /**
     * Actualiza parcialmente la información de una empresa específica.
     *
     * Este método verifica si el usuario autenticado tiene permiso para actualizar parcialmente la información de la empresa.
     * Los permisos se limitan al propio usuario y al administrador principal ('1'). Si el usuario no tiene permiso,
     * se devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, busca al usuario y
     * a la empresa en la base de datos. Si la empresa existe, valida los datos de la solicitud. Si la validación
     * falla, devuelve una respuesta JSON con los errores de validación y un código de estado 422. Si la validación
     * es exitosa, actualiza los campos proporcionados del usuario y de la empresa en la base de datos y devuelve
     * una respuesta JSON con un mensaje de éxito y un código de estado 201. Si la empresa no se encuentra, devuelve
     * una respuesta JSON con un mensaje de error y un código de estado 404.
     *
     * @param Request $request La solicitud HTTP que contiene los datos de la empresa.
     * @param string $id El ID de la empresa.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function partial(Request $request, string $id): JsonResponse {
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
        $company = Companies::where('user_id', '=', $id)->first();

        if ($company) {
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'email' => 'string|unique:companies,email,' . $company->id,
                'nit' => 'string|unique:companies,nit,' . $company->id,
                'password' => 'string',
                'phone_number' => 'string|unique:users,phone_number',
                'entity_name_id' => 'string',
                'address' => 'string',
                'department_id' => 'integer|exists:sv_departments,id',
                'municipality_id' => 'integer|exists:sv_municipality,id',
                'district_id' => 'integer|exists:sv_districts,id',
                'clasification_id' => 'integer|exists:clasifications,id',
                'sector_id' => 'integer|exists:sectors,id',
                'brand_id' => 'integer|exists:brands,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la empresa',
                    'error' => $validator->errors()
                ], 422);
            }

            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('nit')) {
                $company->nit = $request->nit;
            }

            if ($request->has('password')) {
                $user->password = $request->password;
            }

            if ($request->has('phone_number')) {
                $user->phone_number = $request->phone_number;
            }

            if ($request->has('entity_name_id')) {
                $company->entity_name_id = $request->entity_name_id;
            }

            if ($request->has('address')) {
                $company->address = $request->address;
            }

            if ($request->has('department_id')) {
                $company->department_id = $request->department_id;
            }

            if ($request->has('municipality_id')) {
                $company->municipality_id = $request->municipality_id;
            }

            if ($request->has('district_id')) {
                $company->district_id = $request->district_id;
            }

            if ($request->has('clasification_id')) {
                $company->clasification_id = $request->clasification_id;
            }

            if ($request->has('sector_id')) {
                $company->sector_id = $request->sector_id;
            }

            if ($request->has('brand_id')) {
                $company->brand_id = $request->brand_id;
            }

            $user->save();
            $company->save();

            return response()->json([
                'success' => true,
            ], 201);

        }

        return response()->json([
            'success' => false,
            'message' => 'Empresa no encontrada'
        ], 404);
    }

    /**
     * Elimina una empresa específica.
     *
     * Este método verifica si el usuario autenticado tiene permiso para eliminar la empresa.
     * Los permisos se limitan al propio usuario y al administrador principal ('1'). Si el usuario no tiene permiso,
     * se devuelve una respuesta JSON con un mensaje de error y un código de estado 404. Luego, busca la empresa en la
     * base de datos. Si la empresa existe, la elimina de la base de datos y devuelve una respuesta JSON con un mensaje
     * de éxito y un código de estado 201. Si la empresa no se encuentra, devuelve una respuesta JSON con un mensaje de
     * error y un código de estado 404.
     *
     * @param string $id El ID de la empresa.
     * @return JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function destroy(string $id): JsonResponse {
        /* Limitar visualizacion:
            1. El usuario mismo
            2. El admin principal ('1')
        */

        if (Auth::user()->id != $id && Auth::user()->rol_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ruta no existe' // <- Para no dar la pista de que la ruta es verdadera y evitar fallas de seguridad
            ], 404);
        }

        $company = Companies::where('user_id', '=', $id)->first();

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        }

        try {
            $company->delete();
            return response()->json([
                'success' => true,
                'message' => 'Empresa eliminada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
