<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormatEmailEstudianteRule implements ValidationRule {
    /**
     * Regla para validar el formato de un correo de estudiante.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        // Formato de email: XY12345@ues.edu.sv
        if (!preg_match('/^[A-Z]{2}\d{5}@ues.edu.sv$/', $value)) {
            $fail("El campo :attribute no tiene el formato correcto: AB12345@ues.edu.sv");
        }
    }
}
