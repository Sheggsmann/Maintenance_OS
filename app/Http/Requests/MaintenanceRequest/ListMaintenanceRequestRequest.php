<?php

namespace App\Http\Requests\MaintenanceRequest;

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Models\MaintenanceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListMaintenanceRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', MaintenanceRequest::class);
    }

    public function rules(): array
    {
        return [

            'search' => ['nullable', 'string'],

            'property_id' => ['nullable', 'exists:properties,id'],

            'unit_id' => ['nullable', 'exists:units,id'],

            'tenant_id' => ['nullable', 'exists:users,id'],

            'contractor_id' => ['nullable', 'exists:users,id'],

            'status' => [
                'nullable',
                Rule::enum(MaintenanceStatusEnum::class),
            ],

            'priority' => [
                'nullable',
                Rule::enum(MaintenancePriorityEnum::class),
            ],

            'category' => [
                'nullable',
                Rule::enum(MaintenanceCategoryEnum::class),
            ],

            'reported_from' => [
                'nullable',
                'date',
            ],

            'reported_to' => [
                'nullable',
                'date',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'between:1,100',
            ],

            'sort_by' => [
                'nullable',
                Rule::in([
                    'reported_at',
                    'priority',
                    'status',
                    'created_at',
                ]),
            ],

            'sort_direction' => [
                'nullable',
                Rule::in(['asc', 'desc']),
            ],

        ];
    }
}
