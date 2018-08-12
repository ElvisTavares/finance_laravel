@extends('layouts.app')

@section('title')
  {{__('accounts.confirm-destroy')}}
@endsection

@section('title-buttons')
    <?php
    $links = [
        new LinkResponsive(url("/accounts"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
    ];
    ?>
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <div class="{{ config('constants.classes.confirm') }}">
        {{ Form::open(['url' => '/accounts/'.$account->id.'', 'method'=>'DELETE']) }}
        {{__('accounts.confirmation-text', ['id'=>$account->id, 'description'=>$account->description])}}
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection