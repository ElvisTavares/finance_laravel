@extends('layouts.app')

@section('title')
  {{__('transactions.confirm_destroy')}}
@endsection

@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => "/account/".$account->id."/transactions",
      "colMd" => 4,
      "colMdOffset" => 8,
      "btnClass" => backButton()->btnClass,
      "iconClass" => backButton()->iconClass
    ]
  ];
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
{{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.$transaction->id, 'method'=>'DELETE']) }}
  {{__('transactions.confirmation_text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}}
  @include('shared.submit')
{{ Form::close() }} 
@endsection