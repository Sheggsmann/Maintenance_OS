<?php

namespace App\Http\Requests\Unit;

use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $property = Property::findOrFail($this->input('property_id'));
        return $this->user()->can('create', [
            Unit::class,
            $property
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_id' => [
                'required',
                'exists:properties,id',
            ],

            'tenant_id' => [
                'nullable',
                'exists:users,id',
            ],

            'unit_number' => [
                'required',
                'string',
                Rule::unique('units')
                    ->where(
                        fn($query) =>

                        $query->where(
                            'property_id',
                            $this->property_id
                        )
                    ),
            ],

            'floor' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'type' => [
                'required',
                Rule::enum(UnitTypeEnum::class),
            ],

            'status' => [
                'required',
                Rule::enum(UnitStatusEnum::class),
            ],

            'bedrooms' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'bathrooms' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'monthly_rent' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
