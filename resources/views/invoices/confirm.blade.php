@extends('layouts.app')

@section('title')
  {{__('invoices.confirm_destroy')}}
@endsection

@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => "/account/".$account->id."/invoices",
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
 {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.$invoice->id, 'method'=>'DELETE']) }}
    {{__('invoices.confirmation_text', ['id'=>$invoice->id, 'description'=>$invoice->description])}}
    @include('shared.submit')
  {{ Form::close() }} 
@endsection