<?php

namespace App\Http\Requests\API\Comment;

use App\Models\Post;
use App\Models\Share;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCommentRequest extends FormRequest
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
            'content' => [
                'required',
                'string',
                'max:255',
            ],
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'commentable_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$this->commentableClass || !class_exists($this->commentableClass)) {
                        $fail("Invalid commentableType");
                        return;
                    }

                    $exists = $this->commentableClass::find($value);

                    if (!$exists) {
                        $fail("The " . optional($this->commentableClass)->getMorphClass() . " with ID $value does not exist.");
                    }
                },
            ],
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
            'user_id' => auth()->user()->id,
        ]);
    }
}
