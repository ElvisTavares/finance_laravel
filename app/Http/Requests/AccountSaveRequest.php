<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountSaveRequest extends FormRequest
{
    public function rules()
    {
        return [
            'description' => 'required|min:4|max:50'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $prefer = $this->input('prefer_debit_account_id');
            if (!$this->input('is_credit_card') || $prefer == null) return;
            if ($this->input('account_id') && !$this->user->accounts->find($this->input('account_id')))
                $validator->errors()->add('account_id', __('accounts.not-your-account'));
            if ($this->user->accounts->find($prefer)) return;
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
}
