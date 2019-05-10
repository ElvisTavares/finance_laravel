@extends('layouts.app')

@section('title')
    {{__('login.reset-password')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        {!! Form::open(['route' => 'password.request']) !!}
            <div class="form-group">
                {!! Form::label('email', __('login.email')) !!}
                {!! Form::email('email', null, ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::submit(__('login.send-email-reset'), ['class' => 'btn btn-success']); !!}
            </div>
        {!! Form::close() !!}
    </div>
@endsection