@extends('layouts.app')

@section('title')
  {{__('invoices.title')}}
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
    ],
    (object) [
      "url" => url("/account/".$account->id."/invoices?view_mode=".($modeView=="table"?"card":"table")),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => modeViewButton()->btnClass,
      "iconClass" => modeViewButton()->iconClass
    ],
    (object) [
      "url" => url("/account/".$account->id."/invoice/create"),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => addButton()->btnClass,
      "iconClass" => addButton()->iconClass
    ]
  ];
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
@include('invoices/mode_view/'.$modeView)
@foreach($invoices as $invoice)
  @include('accounts/import', ['isAccount'=>false, 'accountId'=>$account->id,'id'=>$invoice->id])
@endforeach
@endsection