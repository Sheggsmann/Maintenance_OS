<?php

namespace App\Http\Requests\Unit;

use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListUnitsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Unit::class);
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],

            'property_id' => [
                'nullable',
                'exists:properties,id',
            ],

            'tenant_id' => [
                'nullable',
                'exists:users,id',
            ],

            'status' => [
                'nullable',
                Rule::enum(UnitStatusEnum::class),
            ],

            'type' => [
                'nullable',
                Rule::enum(UnitTypeEnum::class),
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'sort_by' => [
                'nullable',
                Rule::in([
                    'unit_number',
                    'monthly_rent',
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
