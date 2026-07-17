<?php

namespace App\Http\Requests\MaintenanceRequest;

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Models\MaintenanceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMaintenanceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', MaintenanceRequest::class);
    }

    public function rules(): array
    {
        return [
            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'priority' => [
                'nullable',
                Rule::enum(MaintenancePriorityEnum::class),
            ],

            'category' => [
                'required',
                Rule::enum(MaintenanceCategoryEnum::class),
            ],
        ];
    }
}
