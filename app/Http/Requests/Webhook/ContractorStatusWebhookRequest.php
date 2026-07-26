<?php

namespace App\Http\Requests\Webhook;

use App\Enums\WorkOrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractorStatusWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'maintenance_work_order_id' => [
                'required',
                'integer',
                'exists:work_orders,id',
            ],

            'status' => [
                'required',
                Rule::enum(WorkOrderStatusEnum::class),
            ],

        ];
    }
}
