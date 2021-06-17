<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BodyStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'body' => 'array',
            'workout' => 'array',
            'medicine' => 'array',
        ];
    }
}
