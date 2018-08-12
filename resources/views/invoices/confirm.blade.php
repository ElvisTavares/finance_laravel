@extends('layouts.app')

@section('title')
  {{__('invoices.confirm-destroy')}}
@endsection

@section('title-buttons')
    @php
    $links = [
        new LinkResponsive("/account/".$account->id."/invoices", 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
    ];
    @endphp
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <div class="{{ config('constants.classes.confirm') }}">
        {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.$invoice->id, 'method'=>'DELETE']) }}
        {{__('invoices.confirmation-text', ['id'=>$invoice->id, 'description'=>$invoice->description])}}
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection