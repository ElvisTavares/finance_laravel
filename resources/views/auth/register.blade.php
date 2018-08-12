@extends('layouts.app')

@section('title')
    {{__('login.register')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        <form role="form" method="POST" action="{{ url('/register') }}">
            {!! csrf_field() !!}

            {!! (new FormGroup(__('login.name'), new Field('text', 'name'), $errors))->html() !!}
            {!! (new FormGroup(__('login.email'), new Field('email', 'email'), $errors))->html() !!}
            {!! (new FormGroup(__('login.password'), new Field('password', 'password'), $errors))->html() !!}
            {!! (new FormGroup(__('login.password-confirmation'), new Field('password', 'password_confirmation'), $errors))->html() !!}

            @include('shared/submit', ['text'=>__('login.register'), 'iconClass'=>'fas fa-sign-in-alt'])
        </form>
        @include('auth.social')
    </div>
@endsection
