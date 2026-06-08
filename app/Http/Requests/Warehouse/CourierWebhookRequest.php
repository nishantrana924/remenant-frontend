<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class CourierWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization is handled by the VerifyCourierWebhook middleware HMAC
    }

    public function rules(): array
    {
        return [
            'batch_id' => 'required|integer|exists:warehouse_batches,id',
            'status' => 'required|string|in:dispatched,completed,failed',
            'awb_number' => 'nullable|string',
            'details' => 'nullable|string',
        ];
    }
}
