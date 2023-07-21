<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckStatusTransition implements ValidationRule
{
    private $transitions;

    private $currentStatus;

    public function __construct($transitions, $currentStatus)
    {
        $this->transitions = $transitions;
        $this->currentStatus = $currentStatus;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === $this->currentStatus) {
            return;
        }

        if ($this->currentStatus === null) {
            return;
        }

        if (! in_array($value, $this->transitions[$this->currentStatus])) {
            $fail("Invalid {$attribute} transition, you can only transition from {$this->currentStatus} to " . implode(', ', $this->transitions[$this->currentStatus]) . '.');
        }
    }
}
