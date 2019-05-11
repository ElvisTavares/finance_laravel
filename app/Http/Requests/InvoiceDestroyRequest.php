<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceDestroyRequest extends FormRequest
{

    public function authorize() {
      return Auth::check();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->invoice && !Auth::user()->invoices($this->account)->find($this->invoice))
                $validator->errors()->add('invoice', __('accounts.not-your-account'));
        });
    }
}