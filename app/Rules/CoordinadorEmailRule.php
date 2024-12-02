<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CoordinadorEmailRule implements ValidationRule {
    /**
     * Regla de validación para el correo de un coordinador.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        // Debe terminar en @ues.edu.sv; sin simbolos especiales, unicamene letras y 1 numero (opcional); formato: nombre.apellido(1 numero opcional)@ues.edu.sv
        if (!preg_match('/^[a-zA-Z]+\.[a-zA-Z]+[0-9]*@ues.edu.sv$/', $value)) {
            $fail('El campo :attribute no tiene el formato correcto.');
        }
    }
}
