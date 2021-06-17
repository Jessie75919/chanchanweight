<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmountStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'weight' => 'numeric',
            'fat' => 'numeric',
            'date' => 'required|date',
            'temperature' => 'numeric'
        ];
    }
}
