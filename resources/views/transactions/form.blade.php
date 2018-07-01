@extends('layouts.app')

@section('title')
 {{$action}} {{__('transactions.title')}}
 @if (isset($account))
  {{__('common.in')}} {{$account->id}}/{{$account->description}}
 @endif
@endsection

@section('title-buttons')
<?php
if (isset($account)){
  $links = [
    (object) [
      "url" => "/account/".$account->id."/transactions",
      "colMd" => 4,
      "colSm" => 4,
      "colMdOffset" => 8,
      "colSmOffset" => 8,
      "btnClass" => backButton()->btnClass,
      "iconClass" => backButton()->iconClass
    ]
  ];
} else {
  $links = [
    (object) [
      "url" => "/transactions",
      "colMd" => 4,
      "colSm" => 4,
      "colMdOffset" => 8,
      "colSmOffset" => 8,
      "btnClass" => backButton()->btnClass,
      "iconClass" => backButton()->iconClass
    ]
  ];
}
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
<div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
  @if (isset($account))
    {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.(isset($transaction)?$transaction->id:'').$query, 'method'=>(isset($transaction)?'PUT':'POST')]) }}
      <div class="form-group">
        {{ Form::label('date', __('common.date')) }}
        {{ Form::input('date', 'date', old('date', (isset($transaction)?$transaction->date:null)), ['class'=>'form-control']) }}
      </div>
      @if ($account->is_credit_card)
        <div class="form-group">
          {{ Form::label('invoice_id', __('transactions.invoice')) }}
          {{ Form::select('invoice_id', $account->getOptionsInvoices(), old('invoice_id', isset($transaction) ? $transaction->invoice_id : null), ['class'=>'form-control']) }}
        </div>
        <div id="new_invoice" class="form-group" style="{{ isset($transaction) && ($transaction->invoice_id==-1 || $transaction->invoice_id==null) ? '' : 'display: none' }};">
          <div class="form-group">
            {{ Form::label('invoice_description', __('common.description')) }}
            {{ Form::text('invoice_description', old('invoice_description', null), ['class'=>'form-control']) }}
          </div>
          <div class="form-group">
            {{ Form::label('invoice_date_init', __('common.date_init')) }}
            {{ Form::date('invoice_date_init', old('invoice_date_init', null), ['class'=>'form-control']) }}
          </div>
          <div class="form-group">
            {{ Form::label('invoice_date_end', __('common.date_end')) }}
            {{ Form::date('invoice_date_end', old('invoice_date_end', null), ['class'=>'form-control']) }}
          </div>
          <div class="form-group">
            {{ Form::label('invoice_debit_date', __('common.debit_date')) }}
            {{ Form::date('invoice_debit_date', old('invoice_debit_date', null), ['class'=>'form-control']) }}
          </div>
        </div>
      @endif
      <div class="form-group">
        {{ Form::label('description', __('common.description')) }}
        {{ Form::text('description', old('description', (isset($transaction)?$transaction->description:null)), ['class'=>'form-control']) }}
      </div>
      <div class="form-group">
        {{ Form::label('is_credit', __('transactions.is_credit')) }}
        <label class="switch">
          {{ Form::checkbox('is_credit', 1, old('is_credit', (isset($transaction)?$transaction->value<0:false))) }}
          <span class="slider round"></span>
        </label>
      </div>
      <div class="form-group">
        {{ Form::label('value', __('transactions.value')) }}
        {{ Form::number('value', old('value', (isset($transaction)?abs($transaction->value):null)), ['class'=>'form-control', 'step' => '0.01', 'style'=>'text-align:right;', 'min'=>'0.01']) }}
      </div>
      @if (!$account->is_credit_card)
        <div class="form-group">
          {{ Form::label('paid', __('transactions.paid')) }}
          <label class="switch">
            {{ Form::checkbox('paid', 1, old('paid', (isset($transaction)?$transaction->paid:false))) }}
            <span class="slider round"></span>
          </label>
        </div>
      @endif
      <div class="form-group">
        {{ Form::label('categories', __('common.categories')) }}
        {{ Form::text('categories', old('categories', (isset($transaction) ? $transaction->categories->map(function ($categoryTransaction) {
            return $categoryTransaction->category->description;
          })->implode(',') : null )), ['class'=>'form-control', 'data-role'=>'tagsinput']) }}
      </div>
      <hr>
      @include('shared.submit')
    {{ Form::close() }}
  @else
    {{ Form::open(['url' => URL::current(), 'method'=>'GET']) }}
      <div class="form-group">
        {{ Form::label('account_id', __('accounts.title')) }}
        {{ Form::select('account_id', \Auth::user()->getOptionsAccounts(), null, ['class'=>'form-control', 'style'=>'width:100%;']) }}
      </div>
      @include('shared.submit', ['text'=>__('common.select'), 'iconClass'=>'fas fa-check'])
    {{ Form::close() }}
  @endif
</div>
@endsection

@section('script')
  <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection