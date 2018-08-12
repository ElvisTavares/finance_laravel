@extends('layouts.app')

@section('title')
    {{__('login.login')}}
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            {!! (new FormGroup(__('login.email'), new Field('email', 'email'), $errors))->html() !!}
            {!! (new FormGroup(__('login.password'), new Field('password', 'password'), $errors))->html() !!}
            {!! (new FormGroup(__('login.remember-me'), new Field('checkbox', 'remember'), $errors))->html() !!}

            <div class="form-group">
                @include('shared/submit', ['text'=>__('login.login'), 'iconClass'=>'fas fa-sign-in-alt'])

                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{__('login.forgot')}}
                </a>
            </div>
        </form>
        @include('auth.social')
    </div>
@endsection
