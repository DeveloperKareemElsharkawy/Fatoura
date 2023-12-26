<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;
use Illuminate\Translation\PotentiallyTranslatedString;

class ResetPasswordCode implements ValidationRule
{

    /**
     * @var string
     */
    protected string $email;
    protected string $code;

    /**
     * @param $email
     * @param $code
     */
    public function __construct($email, $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $user = User::query()->where('email', $this->email)->first();

        if (\DB::table('password_resets')->where('email', $user?->email)->value('code') != $value) {
            $fail('the Reset Code is Wrong.');
        }

    }
}
