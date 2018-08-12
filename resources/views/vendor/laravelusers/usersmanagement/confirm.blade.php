@extends('layouts.app')

@section('title')
    {{ __('users.title') }}
@endsection

@section('title-buttons')
    @php
    $links = [
        new LinkResponsive(url("/users"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
    ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <div class="{{ config('constants.classes.confirm') }}">
    {{ Form::open(['url' => '/users/'.$user->id, 'method'=>'DELETE']) }}
    {{__('users.confirmation-text', ['id'=>$user->id, 'description'=>$user->description])}}
    @include('shared.submit')
    {{ Form::close() }}
    </div>
@endsection
