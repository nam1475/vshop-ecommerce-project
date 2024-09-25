<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        // $userId = $this->route('admin.user.edit') ? $this->route('admin.user.edit')->id : null;
        return [
            'name' => ['required', 'string'],
            // 'email' => ['required', 'string', 'email',  Rule::unique('users', 'email')->ignore($userId)],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
            // 'password' => $userId ? ['sometimes', 'confirmed'] : ['required', 'confirmed'],
            // 'password_confirmation' => $userId ? ['sometimes'] : ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation is not match',
            'password_confirmation.required' => 'Password confirmation is required',
        ];
    }
}
