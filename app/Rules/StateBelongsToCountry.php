<?php

namespace App\Rules;

use App\Models\State;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StateBelongsToCountry implements ValidationRule
{

    /**
     * @var int
     */
    protected int $countryId;

    /**
     * @param $countryId
     */
    public function __construct($countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = State::where('id', $value)
            ->where('country_id', $this->countryId)
            ->exists();

        if (!$exists) {
            $fail("The selected state does not belong to the specified country.");
        }
    }
}
