<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminPermissionRequest extends FormRequest
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
            'permission' => ['required', 'string'],
            'actions' => ['required', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'permission.required' => 'Permission is required',
            'actions.required' => 'Action is required',
        ];
    }
}
