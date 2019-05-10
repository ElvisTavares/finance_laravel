<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountDestroyRequest extends FormRequest
{
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
            if ($this->account_id && !Auth::user()->accounts()->find($this->account_id))
                $validator->errors()->add('account_id', __('accounts.not-your-account'));
        });
    }
}
