<?php

namespace App\Rules;

use App\Models\Equipment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StockCheckRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1];

        $equipment = Equipment::find($value);
        if ($equipment === null) {
            $fail('Equipment does not exist');
        } else {
            $quantityRequired = request()->input('items.' . $index . '.quantity');
            $currentStock = $equipment->availableStocks()->count();

            if ($currentStock < $quantityRequired) {
                $fail("Not enough stock for {$equipment->name}. Available stock: {$currentStock}");
            }
        }
    }
}
