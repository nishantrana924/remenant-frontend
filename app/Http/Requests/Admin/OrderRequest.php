<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'nullable|string|in:pending,paid,failed',
            'delivery_status' => 'nullable|string|in:pending,packed,shipped,delivered,returned',
            'tracking_id' => 'nullable|string',
            'courier_name' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'phone' => 'nullable|string',
        ];
    }
}
