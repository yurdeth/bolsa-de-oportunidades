<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FormatCarnetRule implements ValidationRule {
    /**
     * Regla para validar el formato de un carnet.
     *
     * @param \Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        // Formato de carnet: XY12345
        if (!preg_match('/^[A-Z]{2}\d{5}$/', $value)) {
            $fail("El campo :attribute no tiene el formato correcto: AB12345");
        }
    }
}
