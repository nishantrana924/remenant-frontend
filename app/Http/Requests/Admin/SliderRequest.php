<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_desktop' => $this->isMethod('POST') ? 'required|image' : 'nullable|image',
            'image_mobile' => $this->isMethod('POST') ? 'required|image' : 'nullable|image',
            'alt_text' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ];
    }
}
