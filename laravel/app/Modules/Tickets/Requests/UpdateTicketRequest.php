<?php

namespace App\Modules\Tickets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Already protected by controller
    }

    public function rules(): array
    {
        return [
            'subject'     => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category'    => 'sometimes|required|in:technical,billing,general',
            'priority'    => 'sometimes|required|in:low,medium,high',
            'status'      => 'sometimes|required|in:pending,open,in_progress,resolved,closed',
        ];
    }
}
