<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $companies = Companies::all();

        if ($companies->isEmpty()){
            $data = [
                'success' => false,
                'message' => 'No hay empresas registradas'
            ];
            return response()->json($data, 404);
        }
        return response()->json($companies, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
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
            'commercial_name' => 'required|string',
            'email' => 'required|string|unique:companies,email',
            'nit' => 'required|string|unique:companies,nit',
            'password' => 'required|string',
            'phone_number' => 'required|string|unique:companies,phone_number',
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
            $company = Companies::create([
                'commercial_name' => $request->commercial_name,
                'email' => $request->email,
                'nit' => $request->nit,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'entity_name_id' => $request->entity_name_id,
                'address' => $request->address,
                'department_id' => $request->department_id,
                'municipality_id' => $request->municipality_id,
                'district_id' => $request->district_id,
                'clasification_id' => $request->clasification_id,
                'sector_id' => $request->sector_id,
                'brand_id' => $request->brand_id,
                'rol_id' => 2,
                'enabled' => true,
            ]);

            $token = $company->createToken("AuthToken")->accessToken;
            Auth::guard('web')->login($company);

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
     * Display the specified resource.
     */
    public function show(string $id) {
        $company = Companies::find($id);

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        }

        return response()->json($company, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse {
        $company = Companies::find($id);

        if ($company) {
            $validator = Validator::make($request->all(), [
                'commercial_name' => 'required|string',
                'email' => 'required|string|unique:companies,email,'.$company->id,
                'nit' => 'required|string|unique:companies,nit,'.$company->id,
                'password' => 'required|string',
                'personality_type' => 'required|string',
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

            $company->update($request->all());

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

    public function partial(Request $request, $id): JsonResponse {
        Log::info('info', (array)$request);

        $company = Companies::find($id);

        if ($company) {
            $validator = Validator::make($request->all(), [
                'commercial_name' => 'string',
                'email' => 'string|unique:companies,email,'.$company->id,
                'nit' => 'string|unique:companies,nit,'.$company->id,
                'password' => 'required|string',
                'personality_type' => 'string',
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

            if($request->has('commercial_name')) {
                $company->commercial_name = $request->commercial_name;
            }

            if($request->has('email')) {
                $company->email = $request->email;
            }

            if($request->has('nit')) {
                $company->nit = $request->nit;
            }

            if($request->has('personality_type')) {
                $company->personality_type = $request->personality_type;
            }

            if($request->has('address')) {
                $company->address = $request->address;
            }

            if($request->has('department_id')) {
                $company->department_id = $request->department_id;
            }

            if($request->has('municipality_id')) {
                $company->municipality_id = $request->municipality_id;
            }

            if($request->has('district_id')) {
                $company->district_id = $request->district_id;
            }

            if($request->has('clasification_id')) {
                $company->clasification_id = $request->clasification_id;
            }

            if($request->has('sector_id')) {
                $company->sector_id = $request->sector_id;
            }

            if($request->has('brand_id')) {
                $company->brand_id = $request->brand_id;
            }

            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada exitosamente',
                'data' => $company
            ], 201);

        }

        return response()->json([
            'success' => false,
            'message' => 'Empresa no encontrada'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $company = Companies::find($id);

        if(!$company){
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
