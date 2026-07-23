<?php

namespace App\Http\Requests\WorkOrder;

use Illuminate\Foundation\Http\FormRequest;

class GenerateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $maintenanceRequest = $this->route('maintenanceRequest');
        return $this->user()->can('generate', $maintenanceRequest);
    }

    public function rules(): array
    {
        return [
            'scope_of_work' => [
                'required',
                'string',
                'max:5000',
            ],

            'estimated_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'estimated_hours' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'scheduled_for' => [
                'nullable',
                'date',
                'after:now',
            ],
        ];
    }
}
