<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SocialAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|email|unique:users,email,NULL,id,provider,auth', // This will validate whether the email is already taken by a registered user
            'phone' => ['nullable', 'regex:/^[0-9]+$/', 'min:9'],

            'profile_picture_url' => ['nullable', 'url'],
            'bio' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(10)->format('Y-m-d')],
            'gender' => ['nullable', 'in:male,female'],

            'provider' => ['required', 'string', 'in:auth,google,facebook,twitter,linkedin,github,other'],
            'provider_id' => ['required', 'string'],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'provider' => $this->input('provider', 'other'),
        ]);
    }

}
