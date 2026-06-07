<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class AssignWeightRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'weight' => ['required', 'integer', 'min:1', 'max:500000'], // max 500kg
        ];
    }
}
