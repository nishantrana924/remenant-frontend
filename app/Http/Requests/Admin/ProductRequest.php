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
            'badge' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'trust_signals' => 'nullable|array',
            'brand_info' => 'nullable|string',
            'extra_content' => 'nullable|string',
            'image' => $this->isMethod('POST') ? 'required|image' : 'nullable|image',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image',
            'benefits' => 'nullable|array',
            'ritual' => 'nullable|array',
            'specs' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'variants' => 'nullable|array',
            'variants.*.variant_name' => 'required|string',
            'variants.*.price' => 'required|numeric',
            'variants.*.stock' => 'required|integer',
            'discount_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'faqs' => 'nullable|array',
            'sku' => 'nullable|string|max:255',
            'hsn_code' => 'nullable|string|max:255',
            'low_stock_threshold' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|string',
            'theme_color' => 'nullable|string',
        ];
    }
}
