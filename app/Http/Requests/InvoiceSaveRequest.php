<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceSaveRequest extends FormRequest
{

    public function authorize() {
      return Auth::check();
    }

    public function rules()
    {
        return [
            'description' => 'required|min:5|max:100',
            'date_init' => 'required',
            'date_end' => 'required',
            'debit_date' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->invoice_id && !Auth::user()->invoices()->find($this->invoice_id))
                $validator->errors()->add('invoice_id', __('invoices.not-your-invoice'));
        });
    }

    public function messages()
    {
        return [
            'description.required' => __('common.description-required'),
            'description.min' => __('common.description-min-5'),
            'description.max' => __('common.description-max-100'),
            'date_init.required' => __('common.date-required'),
            'date_end.required' => __('common.date-required'),
            'debit_date.required' => __('common.date-required'),
        ];
    }

    public function isCreditCard(){
        return $this->is_credit_card == null ? false : $this->is_credit_card;
    }
}
