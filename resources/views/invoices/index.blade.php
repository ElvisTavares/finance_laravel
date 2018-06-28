@extends('layouts.app')

@section('title')
  {{__('invoices.title')}}
@endsection

@section('title-buttons')
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <a class="btn btn-secondary" href="/accounts">
        <i class="fa fa-arrow-left"></i>
      </a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-secondary" title="{{__('common.view_mode')}}" href="/account/{{$account->id}}/invoices?view_mode={{$modeView=='table'?'card':'table'}}">
        <i class="fas fa-exchange-alt"></i>
      </a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-primary" title="{{__('common.add')}}" href="/account/{{$account->id}}/invoice/create">
        <i class="fa fa-plus"></i>
      </a>
    </div>
  </div>
</div>
@endsection

@section('content')
@include('invoices/mode_view/'.$modeView)
@foreach($invoices as $invoice)
  @include('accounts/import', ['isAccount'=>false, 'accountId'=>$account->id,'id'=>$invoice->id])
@endforeach
@endsection