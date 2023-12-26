<?php

namespace App\Http\Requests\API\Comment;

use App\Models\Post;
use App\Models\Share;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCommentsRequest extends FormRequest
{
    protected $commentableClass;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commentable_type' => [
                'required',
                Rule::in([Post::class, Share::class]),
            ],
            'commentable_id' => [
                'required'
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        $commentableType = $this->route('commentableType');

        if ($commentableType === 'post') {
            $this->commentableClass = Post::class;
        } elseif ($commentableType === 'share') {
            $this->commentableClass = Share::class;
        }

        $this->merge([
            'commentable_type' => $this->commentableClass,
            'commentable_id' => $this->route('commentableId'),
        ]);
    }
}
