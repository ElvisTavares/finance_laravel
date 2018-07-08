@extends('layouts.app')

@section('title')
    {{__('login.reset_password')}}
@endsection

@section('content')
<div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
  <form role="form" method="POST" action="{{ url('/password/email') }}">
    {!! csrf_field() !!}

    <div class="form-group">
      <label>{{__('login.email')}}</label>
      <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

      @if ($errors->has('email'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('email') }}</strong>
        </div>
      @endif
    </div>
    
    @include('shared/submit', ['text'=>__('login.send_email_reset'), 'iconClass'=>'fas fa-sign-in-alt'])
  </form>
</div>
@endsection
