<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContractorWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['required', 'uuid'],
            'reference' => ['required', 'string'],
            'status' => ['required', 'string'],
            'occurred_at' => ['required', 'date'],
        ];
    }
}
