<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePreferencesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'use_newsapi' => 'boolean',
            'use_gnews' => 'boolean',
            'use_mediastack' => 'boolean',
        ];
    }
}