@extends('layouts.app')

@section('title')
  {{$action}} {{__('accounts.title')}}
@endsection

@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => url("/accounts"),
      "colMd" => 4,
      "colSm" => 4,
      "colMdOffset" => 8,
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
  {{ Form::open(['url' => '/accounts'.(isset($account)?'/'.$account->id:''), 'method'=>(isset($account)?'PUT':'POST')]) }}
    <div class="form-group">
      {{ Form::label('description', __('common.description')) }}
      {{ Form::text('description', old('description', (isset($account)?$account->description:null)), ['class'=>'form-control']) }}
    </div>
    <?php if (!isset($account)) { ?>
      <div class="form-group">
        {{ Form::label('is_credit_card', __('accounts.is_credit_card')) }}
        <label class="switch">
          {{ Form::checkbox('is_credit_card', 1, old('is_credit_card', (isset($account)?$account->is_credit_card:false))) }}
          <span class="slider round"></span>
        </label>
      </div>
    <?php } ?>
    <?php if (!isset($account) || (isset($account) && $account->is_credit_card)) { ?>
      <div class="form-group" style="{{ !isset($account)? 'display: none;' : '' }}">
        {{ Form::label('prefer_debit_account_id', __('accounts.prefer_debit_account')) }}
        {{ Form::select('prefer_debit_account_id', $selectAccounts, old('prefer_debit_account_id', (isset($account)?$account->prefer_debit_account_id:null)), ['class'=>'form-control']) }}
      </div>
    <?php } ?>
    @include('shared.submit')
  {{ Form::close() }}
</div>
@endsection

@section('script')
  <script src="{{ asset('js/accounts/form.js') }}"></script>
@endsection