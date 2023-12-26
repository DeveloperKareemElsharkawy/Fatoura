<?php

namespace App\Http\Requests\API\Friendship;

use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendFriendRequest extends FormRequest
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
            'to_user_id' => ['required', 'exists:users,id', Rule::notIn([auth()->id()])], // Ensure 'to_user_id' is not the same as the authenticated user's ID
        ];
    }
}
