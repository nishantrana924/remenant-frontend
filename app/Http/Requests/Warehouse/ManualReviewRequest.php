<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class ManualReviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'shipping_address_id' => ['nullable', 'integer', 'min:1'],
            'product_weight' => ['nullable', 'integer', 'min:1'],
            'product_length' => ['nullable', 'integer', 'min:1'],
            'product_width' => ['nullable', 'integer', 'min:1'],
            'product_height' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
