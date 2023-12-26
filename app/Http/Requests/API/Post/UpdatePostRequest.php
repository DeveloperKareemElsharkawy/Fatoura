<?php

namespace App\Http\Requests\API\Post;

use App\Models\Post;
use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
            'post_id' => [
                'required',
                'exists:posts,id',
                function ($attribute, $value, $fail) {
                    $post = Post::find($value);

                    if (!$post || $post->user_id !== auth()->id()) {
                        $fail('Unauthorized access to this post.');
                    }
                },
            ],
            'user_id' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example image validation
            'video' => 'mimetypes:video/mp4|max:20480',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->user()->id,
            'post_id' => $this->route('postId'),
        ]);
    }
}
