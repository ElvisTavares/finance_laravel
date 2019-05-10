@extends('layouts.app')

@section('title')
    {{__('login.register')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        {!! Form::open(['route' => 'register']) !!}
            <div class="form-group">
                {!! Form::label('name', __('login.name')) !!}
                {!! Form::text('name', null, ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email', __('login.email')) !!}
                {!! Form::email('email', null, ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password', __('login.password')) !!}
                {!! Form::password('password', ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('password_confirmation', __('login.password-confirmation')) !!}
                {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::submit(__('login.register'), ['class' => 'btn btn-success']); !!}
            </div>
        {!! Form::close() !!}
        @include('auth.social')
    </div>
@endsection