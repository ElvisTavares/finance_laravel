@extends('layouts.app')

@section('title')
    {{__('login.reset-password')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        <form role="form" method="POST" action="{{ url('/password/reset') }}">
            {!! csrf_field() !!}

            <input type="hidden" name="token" value="{{ $token }}">

            {!! (new FormGroup(__('login.email'), new Field('email', 'email'), $errors))->html() !!}
            {!! (new FormGroup(__('login.password'), new Field('password', 'password'), $errors))->html() !!}
            {!! (new FormGroup(__('login.password-confirmation'), new Field('password', 'password_confirmation'), $errors))->html() !!}

            @include('shared/submit', ['text'=>__('login.reset-password'), 'iconClass'=>'fas fa-sign-in-alt'])
        </form>
    </div>
@endsection
