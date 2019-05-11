@extends('layouts.app')

@section('title', __('login.reset-password'))
@section('class', config('constants.classes.form'))

@section('content')
    {!! Form::open(['route' => 'password.email']) !!}
        <div class="form-group">
            {!! Form::label('email', __('login.email')) !!}
            {!! Form::email('email', null, ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit(__('login.send-email-reset'), ['class' => 'btn btn-success']); !!}
        </div>
    {!! Form::close() !!}
@endsection