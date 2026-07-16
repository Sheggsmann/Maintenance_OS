<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $property = $this->route('property');
        return $this->user()?->can('update', $property) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string'],
            'state' => ['sometimes', 'string'],
            'manager_id' => ['sometimes', 'exists:users,id'],
        ];
    }
}
