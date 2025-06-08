<?php

namespace App\Modules\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commentable_type' => 'required|string|in:ticket', // we will map this manually
            'commentable_id'   => 'required|integer|exists:tickets,id',
            'comment'          => 'required|string|min:1',
        ];
    }
}
