@extends('layouts.app')

@section('title')
    {{__('transactions.confirm-destroy')}}
@endsection

@section('title-buttons')
    @php
        $links = [new LinkResponsive(url("/account/".$account->id."/transactions"), 'btn btn-back', 'fa fa-arrow-left')];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <div class="{{ config('constants.classes.confirm') }}">
        {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.$transaction->id, 'method'=>'DELETE']) }}
        {{__('transactions.confirmation-text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}}
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection