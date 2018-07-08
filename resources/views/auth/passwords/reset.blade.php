@extends('layouts.app')

@section('title')
  {{__('login.reset_password')}}
@endsection

@section('content')
<div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
  <form role="form" method="POST" action="{{ url('/password/reset') }}">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
      <label>{{__('login.email')}}</label>
      <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}"
      >
      @if ($errors->has('email'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('email') }}</strong>
        </div>
      @endif
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
      >
      @if ($errors->has('password'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('password') }}</strong>
        </div>
      @endif
    </div>

    <div class="form-group">
      <label>{{__('login.password_confirmation')}}</label>
      <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation">
      @if ($errors->has('password_confirmation'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('password_confirmation') }}</strong>
        </div>
      @endif
    </div>
    @include('shared/submit', ['text'=>__('login.reset_password'), 'iconClass'=>'fas fa-sign-in-alt'])
  </form>
</div>
@endsection
