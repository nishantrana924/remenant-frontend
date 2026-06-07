<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class BulkActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy in Controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Safety Limits Validation
        $maxAwb = config('warehouse.max_awb_generation_batch_size', 100);
        
        return [
            'batch_ids' => ['required', 'array', 'min:1', "max:{$maxAwb}"],
            'batch_ids.*' => ['integer', 'exists:warehouse_batches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'batch_ids.max' => 'You cannot process more than :max batches in a single bulk request. Please select fewer items to protect system performance.',
        ];
    }
}
