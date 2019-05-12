<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class TransactionSaveRequest extends FormRequest
{

    public function authorize() {
      return Auth::check();
    }

    public function rules()
    {
        return [
            'description' => 'required|min:5|max:100',
            'date' => 'required',
            'value' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'description.required' => __('common.description-required'),
            'description.min' => __('common.description-min-5'),
            'description.max' => __('common.description-max-100'),
            'date.required' => __('common.date-required'),
            'value.required' => __('common.value-required')
        ];
    }


    public function isPaid($account){
        return $account->is_credit_card || $this->is_paid == "1";
    }

    public function isTransfer(){
        return $this->is_transfer == "1";
    }

    public function isCredit(){
        return $this->is_credit == "1";
    }
}
