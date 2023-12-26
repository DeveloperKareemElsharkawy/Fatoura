<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|exists:users,' . $this->username(),
            'password' => 'required|min:6',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return string
     */
    public function username(): string
    {
        return (filter_var(request()['username'], FILTER_VALIDATE_EMAIL)) ? 'email' : 'phone';
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->username(),
        ]);
    }
}
