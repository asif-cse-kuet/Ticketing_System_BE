<?php

namespace App\Modules\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|in:technical,billing,general',
            'priority'    => 'required|in:low,medium,high',
            'attachment'  => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        ];
    }
}
