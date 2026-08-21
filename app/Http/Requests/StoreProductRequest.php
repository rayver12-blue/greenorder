<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:100'],
            'category'         => ['nullable', 'string', 'max:50'],
            'description'      => ['nullable', 'string', 'max:500'],
            'price'            => ['required', 'numeric', 'min:0'],
            'stock'            => ['required', 'integer', 'min:0'],
            'day_availability' => ['required', 'in:common,monday,tuesday,wednesday,thursday,friday,saturday'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery'          => ['nullable', 'array'],
            'gallery.*'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
