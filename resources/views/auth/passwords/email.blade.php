@extends('layouts.app')

@section('title')
    {{__('login.reset-password')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        <form role="form" method="POST" action="{{ url('/password/email') }}">
            {!! csrf_field() !!}

            {!! (new FormGroup(__('login.email'), new Field('email', 'email'), $errors))->html() !!}

            @include('shared/submit', ['text'=>__('login.send-email-reset'), 'iconClass'=>'fas fa-sign-in-alt'])
        </form>
    </div>
@endsection
