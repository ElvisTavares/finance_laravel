@extends('layouts.app')
@section('title')
    {{__('transactions.title')}}
@endsection
@section('class', config('constants.classes.full'))
@section('title-buttons')
    @if($invoice)
        <a href="{{route('invoices.transactions.create', [$account->id, $invoice->id()])}}" class="btn btn-primary">
            <i class="fa fa-plus"></i> {{__('common.add')}}
        </a>
    @else
        <a href="{{route('accounts.transactions.create', $account->id)}}" class="btn btn-primary">
            <i class="fa fa-plus"></i> {{__('common.add')}}
        </a>
    @endif
@endsection

@section('content')
    @include('transactions/header')
    @include('transactions/table')
@endsection