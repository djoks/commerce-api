<?php

namespace App\Rules;

use App\Models\Product;
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

        $product = Product::find($value);
        if ($product === null) {
            $fail('Product does not exist');
        } else {
            $quantityRequired = request()->input('items.' . $index . '.quantity');
            $currentStock = $product->withAvailableStock()->count();

            if ($currentStock < $quantityRequired) {
                $fail("Not enough stock for {$product->name}. Available stock: {$currentStock}");
            }
        }
    }
}
