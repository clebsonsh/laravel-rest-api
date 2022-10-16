<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->method() == 'PUT') {
            return [
                'customer_id' => ['required', 'integer'],
                'amount' => ['required', 'numeric'],
                'status' => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
                'billed_date' => ['required', 'date_format:Y-m-d H:i:s'],
                'paid_date' => ['date_format:Y-m-d H:i:s', 'nullable'],
            ];
        }

        return [
            'customer_id' => ['sometimes', 'required', 'integer'],
            'amount' => ['sometimes', 'required', 'numeric'],
            'status' => ['sometimes', 'required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            'billed_date' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
            'paid_date' => ['date_format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];

        if ($this->customerId) {
            $data['customer_id'] = $this->customerId;
        }
        if ($this->customerId) {
            $data['billed_date'] = $this->billedDate;
        }
        if ($this->customerId) {
            $data['paid_date'] = $this->paidDate;
        }

        $this->merge($data);
    }
}
