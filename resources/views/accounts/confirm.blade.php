@extends('layouts.app')

@section('title')
  {{__('accounts.confirm_destroy')}}
@endsection

@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => url("/accounts"),
      "colMd" => 4,
      "colMdOffset" => 8,
      "btnClass" => $backButton->btnClass,
      "iconClass" => $backButton->iconClass
    ]
  ];
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
 {{ Form::open(['url' => '/accounts/'.$account->id.'', 'method'=>'DELETE']) }}
    {{__('accounts.confirmation_text', ['id'=>$account->id, 'description'=>$account->description])}}
  @include('shared.submit')
  {{ Form::close() }}
@endsection