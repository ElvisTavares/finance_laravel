@extends('layouts.app')

@section('title')
  {{$action}} {{__('invoices.title')}}
@endsection

@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => "/account/".$account->id."/invoices",
      "colMd" => 4,
      "colMdOffset" => 8,
      "colSm" => 4,
      "colSmOffset" => 8,
      "btnClass" => backButton()->btnClass,
      "iconClass" => backButton()->iconClass
    ]
  ];
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
<div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
  {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.( isset($invoice) ? $invoice->id : '' ), 'method' => isset($invoice) ? 'PUT' : 'POST' ]) }}
    <div class="form-group">
      {{ Form::label('description', __('common.description')) }}
      {{ Form::text('description', old('description', ( isset($invoice) ? $invoice->description : null )), ['class'=>'form-control']) }}
    </div>
    <div class="form-group">
      {{ Form::label('date_init', __('common.date_init')) }}
      {{ Form::input('date', 'date_init', old('date_init', ( isset($invoice) ? $invoice->date_init : null )), ['class'=>'form-control']) }}
    </div>
    <div class="form-group">
      {{ Form::label('date_end', __('common.date_end')) }}
      {{ Form::input('date', 'date_end', old('date_end', ( isset($invoice) ? $invoice->date_end : null )), ['class'=>'form-control']) }}
    </div>
    <div class="form-group">
      {{ Form::label('debit_date', __('common.debit_date')) }}
      {{ Form::input('date', 'debit_date', old('debit_date', ( isset($invoice) ? $invoice->debit_date : null )), ['class'=>'form-control']) }}
    </div>
    @include('shared.submit')
  {{ Form::close() }}
</div>
@endsection