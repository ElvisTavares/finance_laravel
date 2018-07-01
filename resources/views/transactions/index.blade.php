@extends('layouts.app')

@section('title')
  {{__('transactions.title')}}
@endsection

@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => url("/accounts"),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => backButton()->btnClass,
      "iconClass" => backButton()->iconClass
    ]
  ];

  if (isset($account)){
    array_push($links, (object) [
      "url" => url("account/".$account->id."/transaction?view_mode=".($modeView=="table"?"card":"table")),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => modeViewButton()->btnClass,
      "iconClass" => modeViewButton()->iconClass
    ]);
    array_push($links, (object) [
      "url" => url("/account/".$account->id."/transaction/create"),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => addButton()->btnClass,
      "iconClass" => addButton()->iconClass
    ]);
  } else {
    array_push($links, (object) [
      "url" => url("/transaction?view_mode=".($modeView=="table"?"card":"table")),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => modeViewButton()->btnClass,
      "iconClass" => modeViewButton()->iconClass
    ]);
    array_push($links, (object) [
      "url" => url("/transactions/create"),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => addButton()->btnClass,
      "iconClass" => addButton()->iconClass
    ]);
  }
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
  <h4>{{__('common.filter_by_date')}}</h4>
  {{ Form::open(['url' => (isset($account) ? '/account/'. $account->id : '' ) . '/transactions/', 'method'=>'GET', 'class'=>'form']) }}
    <div class="form-group">
      {{ Form::label('description', __('common.description')) }}
      {{ Form::text('description', old('description'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
    </div>
    <div class="container-fluid" style="padding: 0px;">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            {{ Form::label('date_init', __('common.date_init')) }}
            {{ Form::date('date_init', old('date_init'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
          </div>
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="form-group">
            {{ Form::label('date_end', __('common.date_end')) }}
            {{ Form::date('date_end', old('date_end'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
          </div>
        </div>
      </div>
    </div>
    @include('shared/submit', ['text' => __('common.search'), 'iconClass' => 'fa fa-search'])
  {{ Form::close() }}
</div>
@if (isset($account) && $account->is_credit_card)
  <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
    {{ Form::open(['url' => '/account/'.$account->id.'/transactions/', 'method'=>'GET', 'class'=>'form']) }}
      <h4>{{__('common.filter_by_invoice')}}</h4>
      <div class="form-group">
        {{ Form::label('description', __('common.description')) }}
        {{ Form::text('description', old('description'), ['class'=>'form-control', 'style'=>'width:100%;']) }}
      </div>
      <div class="form-group">
        {{ Form::label('date_init', __('transactions.invoice')) }}
        {{ Form::select('invoice_id', $account->getOptionsInvoices(false), old('invoice_id', isset($request->invoice_id) ? $request->invoice_id : null), ['class'=>'form-control', 'style'=>'width:100%;']) }}
      </div>
      @include('shared/submit', ['text' => __('common.search'), 'iconClass' => 'fa fa-search'])
    {{ Form::close() }}
  </div>
@endif
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
  {{ Form::open(['url' => (isset($account) ? '/account/'. $account->id : '' ) . '/transactions/addCategories?'.$query, 'method'=>'PUT', 'class'=>'form', 'style'=>'width:100%;']) }}
    <div class="form-group">
      <h4> {{ __('common.add_category') }} </h4>
      {{ Form::text('categories', old('categories', ''), ['class'=>'form-control', 'data-role'=>'tagsinput']) }}
    </div>
    @include('shared.submit', ['text' => __('common.add'), 'iconClass' => ''])
  {{ Form::close() }}
</div>
@include('transactions/mode_view/'.$modeView)
@endsection