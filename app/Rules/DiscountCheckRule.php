<?php

namespace App\Rules;

use App\Models\Discount;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DiscountCheckRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $discountIds = collect($value);
        $attribute = $attribute; // to avoid unused variable warning

        if ($discountIds->count() > 0) {
            $discounts = Discount::whereIn('id', $discountIds)->get();

            if ($discounts->count() !== $discountIds->count()) {
                $fail('One or more {$attribute} are invalid.');
            }
        }
    }
}
