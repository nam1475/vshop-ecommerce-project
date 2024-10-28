<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'address' => ['required', 'string'],
            'province' => ['required'],
            'district' => ['required'],
            'ward' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Phone number is not valid',
            'address.required' => 'Address is required',
            'province.required' => 'Province is required',
            'district.required' => 'District is required',
            'ward.required' => 'Ward is required',
        ];
    }
}
