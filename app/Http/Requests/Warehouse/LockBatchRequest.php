<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class LockBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth handled by Policy
    }

    public function rules(): array
    {
        return [];
    }
}
