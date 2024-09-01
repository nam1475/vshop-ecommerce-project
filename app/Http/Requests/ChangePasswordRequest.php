<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'currentPassword' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth('customer')->user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword'
        ];
    }

    public function messages()
    {
        return [
            'currentPassword.required' => 'Please enter your current password',
            'newPassword.required' => 'Please enter your new password',
            'confirmPassword.required' => 'Please confirm your new password',
            'confirmPassword.same' => 'The new password and confirmation password do not match',
        ];
    }
}
