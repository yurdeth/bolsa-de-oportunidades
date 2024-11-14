<?php

namespace App\Http\Controllers;

//use App\Mail\ContactFormMail;
use App\Models\Companies;
use App\Models\Students;
use App\Models\User;
use App\Rules\EmailUniqueIgnoreCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Rules\OnlyUesMail;

class AuthController extends Controller {

    public function register(Request $request) {
        /*$validationService = new ValidationService($request, ValidationTypeEnum::REGISTER_USER);
        $response = $validationService->ValidateRequest();
        if ($response) {
            return $response;
        }*/

        $userType = $request->user_type;

        if (!$userType) {
            return response()->json([
                "message" => "Error al crear el registro",
                "success" => false,
            ], 500);
        }

        if ($userType == 1){
            $this->registerNewStudent($request);
        } else{
            $this->registerNewCompany($request);
        }
    }

    private function registerNewStudent(Request $request): JsonResponse {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => [
                "required",
                "string",
                "unique:users,email",
                new OnlyUesMail(),
                new EmailUniqueIgnoreCase("users", "email"),
            ],
            "phone_number" => "required|string|unique:students,phone_number",
            "password" => "required|string|confirmed|min:8",
            "password_confirmation" => "required|string|confirmed|min:8",
            "career" => "required|exists:careers,id",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => "Error en los datos",
                "error" => $validate->errors(),
                "success" => false,
            ]);
        }

        $emailParts = explode('@', $request->email);
        $email = strtoupper($emailParts[0]) . '@' . $emailParts[1];
        $carnet = strtoupper($emailParts[0]);

        $user = Students::create([
            "name" => $request->name,
            "carnet" => $carnet,
            "email" => $email,
            "password" => Hash::make($request->password),
            "career_id" => $request->career,
        ]);

        if (!$user) {
            return response()->json([
                "message" => "Error al crear el registro",
                "success" => false,
            ], 500);
        }

        Auth::login($user);
        $token = $user->createToken("token")->accessToken;
//        $this->sendWelcomeMail($request, $user);

        return response()->json([
            "token" => $token,
            "token_type" => "Bearer",
            "redirect_to" => route("/"),
            "success" => true,
        ], 201);
    }

    private function registerNewCompany(Request $request): JsonResponse {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => [
                "required",
                "string",
                "unique:companies,email",
                new OnlyUesMail(),
                new EmailUniqueIgnoreCase("companies", "email"),
            ],
            "phone_number" => "required|string|unique:companies:phone_number",
            "nit" => "required|string|unique:companies:nit",
            "entity" => "required|string",
            "address" => "required|string",
            "district" => "required|string",
            "clasification" => "required|string",
            "brand" => "required|string",
            "sector" => "required|string",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "message" => "Error en los datos",
                "error" => $validate->errors(),
                "success" => false,
            ]);
        }

        $emailParts = explode('@', $request->email);
        $email = strtoupper($emailParts[0]) . '@' . $emailParts[1];
        $carnet = strtoupper($emailParts[0]);

        $user = Companies::create([
            "name" => $request->name,
            "email" => $email,
            "phone_number" => $request->phone_number,
            "nit" => $request->nit,
            "entity_id" => $request->entity,
            "address" => $request->address,
            "district_id" => $request->district,
            "clasification_id" => $request->clasification,
            "brand" => $request->brand,
            "sector" => $request->sector,
        ]);

        if (!$user) {
            return response()->json([
                "message" => "Error al crear el registro",
                "success" => false,
            ], 500);
        }

        Auth::login($user);
        $token = $user->createToken("token")->accessToken;
//        $this->sendWelcomeMail($request, $user);

        return response()->json([
            "token" => $token,
            "token_type" => "Bearer",
            "redirect_to" => route("/"),
            "success" => true,
        ], 201);
    }

    public function login(Request $request): JsonResponse|RedirectResponse {
        // Campos esperados en la petición
        $credentials = $request->only('email', 'password');

        if (!$request->email && !$request->password) {
            return redirect()->route("iniciarSesion");
        }

        if (!$request->email) {
            return response()->json([
                "message" => "Por favor, ingrese su correo",
                "success" => false,
            ]);
        }

        if (!$request->password) {
            return response()->json([
                "message" => "Por favor, ingrese su contraseña",
                "success" => false,
            ]);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Auth::login($user);

            // Crear un nuevo token
            $token = Auth::user()->createToken("token")->accessToken;

            // Redireccionamiento basado en el rol del usuario
            if (Auth::user()->roles_id == 1) {
                $route = "dashboard";
            } else {
                $route = "HomeVR";
            }

            return response()->json([
                "token" => $token,
                "token_type" => "Bearer",
                "redirect_to" => route($route),
                "success" => true,
            ], 201);

        }

        if (!$request->email || !$request->password) {
            return response()->json([
                "message" => "Faltan credenciales",
                "success" => false,
                "redirect_to" => route("login"),
            ], 401);
        }

        return response()->json([
            "message" => "Credenciales erróneas",
            "success" => false,
            "redirect_to" => route("login"),
        ], 401);
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("inicio");
    }

    /*private function sendWelcomeMail(Request $request, User $user) {
        $details = [
            'subject' => 'Bienvenido a Minerva RV Lab, ' . $user->name . '.',
            'name' => 'Minerva RV Lab',
            'email' => $user->email,
            'message' => 'Es un gusto tenerte con nosotros'
        ];

        Mail::to($user->email)->send(new ContactFormMail($details));
    }*/
}
