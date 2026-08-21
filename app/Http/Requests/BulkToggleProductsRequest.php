<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkToggleProductsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer'],
            'action' => ['required', 'in:enable,disable'],
        ];
    }
}
