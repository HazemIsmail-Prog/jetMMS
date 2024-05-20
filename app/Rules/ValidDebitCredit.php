<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDebitCredit implements Rule
{
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $debitValue = $this->details[$index]['debit'] ?? 0;
        $creditValue = $this->details[$index]['credit'] ?? 0;

        return $debitValue > 0 || $creditValue > 0;
    }

    public function message()
    {
        return 'Either debit or credit must be greater than zero.';
    }
}
