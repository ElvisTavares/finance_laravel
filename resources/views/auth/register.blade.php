@extends('layouts.app')

@section('title')
  {{__('login.register')}}
@endsection

@section('content')
<div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
  <form role="form" method="POST" action="{{ url('/register') }}">
    {!! csrf_field() !!}

    <div class="form-group">
      <label>{{__('login.name')}}</label>
      <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >
      @if ($errors->has('name'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('name') }}</strong>
        </div>
      @endif
    </div>

    <div class="form-group">
      <label>{{__('login.email')}}</label>
      <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

      @if ($errors->has('email'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('email') }}</strong>
        </div>
      @endif
    </div>

    <div class="form-group">
      <label>{{__('login.password')}}</label>
      <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
      @if ($errors->has('password'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('password') }}</strong>
        </div>
      @endif
    </div>

    <div class="form-group">
      <label>{{__('login.password_confirmation')}}</label>
      <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>
      @if ($errors->has('password_confirmation'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('password_confirmation') }}</strong>
        </div>
      @endif
    </div>
    @include('shared/submit', ['text'=>__('login.register'), 'iconClass'=>'fas fa-sign-in-alt'])
  </form>
  @include('auth.social')
</div>
@endsection
