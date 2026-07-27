<?php

namespace App\Http\Requests\WorkOrder;

use App\Enums\WorkOrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListWorkOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],

            'status' => [
                'nullable',
                Rule::enum(
                    WorkOrderStatusEnum::class
                ),
            ],

            'property_id' => [
                'nullable',
                'integer',
                'exists:properties,id',
            ],

            'manager_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'between:1,100',
            ],
        ];
    }
}
