<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule {
    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        // Formato: 2xxx-xxxx, 2xxxxxxx, 7xxxx-xxxx, 7xxxxxxx, 6xxxx-xxxx, 6xxxxxxx, +503 2xxx-xxxx, +503 2xxxxxxx, +503 7xxxx-xxxx, +503 7xxxxxxx, +503 6xxxx-xxxx, +503 6xxxxxxx
        if (!preg_match('/^(\+503\s)?(2|6|7)\d{3}(-)?\d{4}$/', $value)) {
            $fail("El campo $attribute no tiene un formato de número de teléfono válido");
        }
    }
}
