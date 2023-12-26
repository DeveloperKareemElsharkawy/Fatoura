<?php

namespace App\Http\Requests\API\Like;

use App\Models\Post;
use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleLikeRequest extends FormRequest
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
            'post_id' => ['required', 'exists:posts,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'post_id' => $this->route('postId'),
        ]);
    }
}
