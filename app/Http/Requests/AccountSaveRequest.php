<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class AccountSaveRequest extends FormRequest
{

    public function authorize() {
      return Auth::check();
    }

    public function rules()
    {
        return [
            'description' => 'required|min:4|max:50'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->is_credit_card || $this->prefer_debit_account_id == null) return;
            if ($this->account_id && !Auth::user()->accounts()->find($this->account_id))
                $validator->errors()->add('account_id', __('accounts.not-your-account'));
            if (Auth::user()->accounts()->find($this->prefer_debit_account_id)) return;
            $validator->errors()->add('prefer_debit_account', __('accounts.not-your-account'));
        });
    }

    public function messages()
    {
        return [
            'description.required' => __('common.description-required'),
            'description.min' => __('common.description-min-5'),
            'description.max' => __('common.description-max-50')
        ];
    }

    public function isCreditCard(){
        return $this->is_credit_card == null ? false : $this->is_credit_card;
    }
}
