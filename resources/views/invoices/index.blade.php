@extends('layouts.app')
@section('title')
    <i class="fas fa-piggy-bank"></i> {{__('accounts.title')}}
@endsection
@section('class', config('constants.classes.full'))
@section('title-buttons')
    <a href="{{route('invoices.create', $account)}}" class="btn btn-primary">
        <i class="fa fa-plus"></i> {{__('common.add')}}
    </a>
@endsection

@section('content')
@include('invoices/table')
@foreach($invoices as $invoice)
  @include('invoices/import', [ 'account'=>$account->id, 'id'=>$invoice->id ])
@endforeach
@endsection