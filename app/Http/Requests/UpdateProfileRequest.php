<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'profile_pic' => 'nullable|image|max:2048',
            'old_password' => 'required_with:new_password|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required_with' => 'Please enter your old password to change it.',
            'old_password.min' => 'Old password must be at least 6 characters.',
            'new_password.confirmed' => 'New password and confirm password do not match.',
            'new_password.min' => 'New password must be at least 6 characters.',
        ];
    }
}