<?php

namespace App\Http\Requests\API\Friendship;

use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcceptFriendRequest extends FormRequest
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
            'friend_request_id' => [
                'required',
                Rule::exists('friend_requests', 'id')->where(function ($query) {
                    $query->where('status', 'pending');
                }),
            ],
        ];
    }
}
