<?php

namespace App\Http\Requests\Property;

use App\Models\Property;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ListPropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Property::class);
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],

            'city' => ['nullable', 'string'],

            'state' => ['nullable', 'string'],

            'manager_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'sort_by' => [
                'nullable',
                'in:name,city,state,created_at',
            ],

            'sort_direction' => [
                'nullable',
                'in:asc,desc',
            ],
        ];
    }
}
