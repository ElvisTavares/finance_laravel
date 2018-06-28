@extends('layouts.app')

@section('title')
 {{$action}} {{__('transactions.title')}} {{__('common.in')}} {{$account->id}}/{{$account->description}}
@endsection

@section('title-buttons')
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-8">
        <a class="btn btn-secondary" href="/account/{{$account->id}}/transactions">
          <i class="fa fa-arrow-left"></i>
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
  {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.(isset($transaction)?$transaction->id:'').( isset($_GET['date_init']) && isset($_GET['date_end']) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end']   : ''), 'method'=>(isset($transaction)?'PUT':'POST')]) }}
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-{{$account->is_credit_card?'6':'12'}}">
          <div class="form-group">
            {{ Form::label('date', __('common.date')) }}
            {{ Form::input('dateTime-local', 'date', old('date', (isset($transaction)?$transaction->date:null)), ['class'=>'form-control']) }}
          </div>
        </div>
    @if (!$account->is_credit_card)
      </div>
    @endif

    @if ($account->is_credit_card)
        <div class="col-md-6">
          <div class="form-group">
            {{ Form::label('invoice_id', __('transactions.invoice')) }}
            {{ Form::select('invoice_id', $account->getOptionsInvoices(), old('invoice_id', isset($transaction) ? $transaction->invoice_id : null), ['class'=>'form-control']) }}
          </div>
        </div>
      </div>
      <div id="new_invoice" class="form-group" style="{{ isset($transaction) && ($transaction->invoice_id==-1 || $transaction->invoice_id==null) ? '' : 'display: none' }};">
        <div class="container-fluid" style="padding: 0px;">
          <div class="row" style="padding: 0px;">
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label('invoice_description', __('common.description')) }}
                {{ Form::text('invoice_description', old('invoice_description', null), ['class'=>'form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('invoice_date_init', __('common.date_init')) }}
                {{ Form::date('invoice_date_init', old('invoice_date_init', null), ['class'=>'form-control']) }}
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                {{ Form::label('invoice_date_end', __('common.date_end')) }}
                {{ Form::date('invoice_date_end', old('invoice_date_end', null), ['class'=>'form-control']) }}
              </div>
              <div class="form-group">
                {{ Form::label('invoice_debit_date', __('common.debit_date')) }}
                {{ Form::date('invoice_debit_date', old('invoice_debit_date', null), ['class'=>'form-control']) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    <div class="container-fluid" style="padding: 0px;">
      <div class="row" style="padding: 0px;">
        <div class="col-md-12">
          <div class="form-group">
            {{ Form::label('description', __('common.description')) }}
            {{ Form::text('description', old('description', (isset($transaction)?$transaction->description:null)), ['class'=>'form-control']) }}
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            {{ Form::label('is_credit', __('transactions.is_credit')) }}
            <label class="switch">
              {{ Form::checkbox('is_credit', 1, old('is_credit', (isset($transaction)?$transaction->value<0:false))) }}
              <span class="slider round"></span>
            </label>
          </div>
        </div>
        <div class="col-md-{{$account->is_credit_card?10:8}}">
          <div class="form-group">
            {{ Form::label('value', __('transactions.value')) }}
            {{ Form::number('value', old('value', (isset($transaction)?abs($transaction->value):null)), ['class'=>'form-control', 'step' => '0.01', 'style'=>'text-align:right;', 'min'=>'0.01']) }}
          </div>
        </div>
        @if (!$account->is_credit_card)
          <div class="col-md-2">
            <div class="form-group">
              {{ Form::label('paid', __('transactions.paid')) }}
              <label class="switch">
                {{ Form::checkbox('paid', 1, old('paid', (isset($transaction)?$transaction->paid:false))) }}
                <span class="slider round"></span>
              </label>
            </div>
          </div>
        @endif

        <div class="col-md-12">
          <div class="form-group">
            {{ Form::label('categories', __('common.categories')) }}
            {{ Form::text('categories', old('categories', (isset($transaction) ? $transaction->categories->map(function ($categoryTransaction) {
                return $categoryTransaction->category->description;
              })->implode(',') : null )), ['class'=>'form-control', 'data-role'=>'tagsinput']) }}
          </div>
        </div>

        <div class="col-md-12">
          <hr>
          @include('shared.submit')
        </div>
      </div>
    </div>
  {{ Form::close() }} 
@endsection

@section('script')
  <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection