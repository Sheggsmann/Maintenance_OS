<?php

namespace App\Http\Requests\Property;

use App\Models\Property;
use Illuminate\Foundation\Http\FormRequest;

class CreatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Property::class);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'address' => [
                'required',
                'string'
            ],
            'city' => [
                'required',
                'string',
                'max:100'
            ],
            'state' => [
                'required',
                'string',
                'max:100'
            ],
            'country' => [
                'nullable',
                'string',
                'max:100'
            ],
            'postal_code' => [
                'nullable',
                'string',
                'max:20'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'manager_id' => [
                'required',
                'exists:users,id'
            ]
        ];
    }
}
