<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'image' => $this->isMethod('POST') ? 'required|image' : 'nullable|image',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image',
            'color_theme' => 'nullable|string',
            'color_gradient' => 'nullable|string',
            'benefits' => 'nullable|array',
            'specs' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];
    }
}
