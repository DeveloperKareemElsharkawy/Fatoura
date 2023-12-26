<?php

namespace App\Http\Requests\API\Auth;

use App\Rules\StateBelongsToCountry;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'regex:/^[0-9]+$/', 'min:9','unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id', new StateBelongsToCountry($this->input('country_id', 0))],

            'profile_picture' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(10)->format('Y-m-d')],
            'gender' => ['required', 'in:male,female'],
        ];
    }


}
