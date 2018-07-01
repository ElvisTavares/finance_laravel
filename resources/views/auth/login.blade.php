@extends('layouts.app')

@section('title')
  {{__('login.login')}}
@endsection

@section('content')
<div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
  <form class="form-horizontal" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="form-group">
      <label for="email">{{__('login.email')}}</label>
      <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus
      >
      @if ($errors->has('email'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('email') }}</strong>
        </div>
      @endif
    </div>

    <div class="form-group">
      <label for="password">{{__('login.password')}}</label>
      <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required
      >
      @if ($errors->has('password'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('password') }}</strong>
        </div>
      @endif
    </div>
    
    <div class="form-group">
      {{ Form::label('remember', __('login.remember_me')) }}
      <label class="switch">
        {{ Form::checkbox('remember', 1, old('remember',  old('remember') ? 'checked' : '')) }}
        <span class="slider round"></span>
      </label>
    </div>

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
