<?php

namespace App\Http\Requests\API\User;

use App\Rules\StateBelongsToCountry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'phone' => ['required', 'regex:/^[0-9]+$/', 'min:9', Rule::unique('users')->ignore(auth()->id())],


            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id', new StateBelongsToCountry($this->input('country_id', 0))],

            'profile_picture' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(10)->format('Y-m-d')],
            'gender' => ['required', 'in:male,female'],
        ];
    }
}
