<?php

namespace App\Http\Requests\MaintenanceRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApproveMaintenanceRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('approve', $this->route('maintenanceRequest'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'scope_of_work' => ['required', 'string'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'estimated_hours' => ['required', 'numeric', 'min:1'],
            'scheduled_for' => ['nullable', 'date'],
        ];
    }
}
