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

    public function login(Request $request): JsonResponse|RedirectResponse {
        // Campos esperados en la petición
        $credentials = $request->only('email', 'password');

        if (!$request->email && !$request->password) {
            return redirect()->route("iniciarSesion");
        }

        if (Auth::attempt($credentials)) {
            $student = Auth::user();
            Auth::login($student);

            // Crear un nuevo token
            $token = Auth::user()->createToken("token")->accessToken;

            // Redireccionamiento basado en el rol del usuario
            /*if (Auth::user()->roles_id == 1) {
                $route = "dashboard";
            } else {
                $route = "HomeVR";
            }*/

            return response()->json([
                "token" => $token,
                "token_type" => "Bearer",
                "redirect_to" => url("/"),
                "success" => true,
            ], 201);

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

        return response()->json([
            "message" => "Credenciales erróneas",
            "success" => false,
//            "redirect_to" => route("login"),
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
