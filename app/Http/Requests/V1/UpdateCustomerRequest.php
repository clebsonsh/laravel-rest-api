<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() == 'PUT') {
            return [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
                'email' => ['required', 'email', 'unique:customers,email,' . $this->customer->id],
                'address' => ['required', 'string', 'min:3', 'max:255'],
                'city' => ['required', 'string', 'min:3', 'max:255'],
                'state' => ['required', 'string', 'min:2', 'max:2'],
                'postal_code' => ['required', 'numeric'],
            ];
        }

        return [
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'type' => ['sometimes', 'required', Rule::in(['I', 'B', 'i', 'b'])],
            'email' => ['sometimes', 'required', 'email', 'unique:customers,email,' . $this->customer->id],
            'address' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'city' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'state' => ['sometimes', 'required', 'string', 'min:2', 'max:2'],
            'postal_code' => ['sometimes', 'required', 'numeric'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->postalCode) {
            $this->merge([
                'postal_code' => $this->postalCode
            ]);
        }
    }
}
