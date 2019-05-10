@extends('layouts.app')

@section('title')
    {{__('login.login')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        {!! Form::open(['route' => 'login']) !!}
            <div class="form-group">
                {!! Form::label('email', __('login.email')) !!}
                {!! Form::email('email', null, ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password', __('login.password')) !!}
                {!! Form::password('password', ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('remember', __('login.remember-me')) !!}
                {!! Form::checkbox('remember', true) !!}
            </div>
            <div class="form-group">
                {!! Form::submit(__('login.login'), ['class' => 'btn btn-success']); !!}
            </div>
        {!! Form::close() !!}
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{__('login.forgot')}}
        </a>
        @include('auth.social')
    </div>
@endsection
