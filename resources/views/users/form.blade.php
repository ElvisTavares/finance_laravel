@extends('layouts.app')
@php($user = empty($user) ? new App\User() : $user)
@section('title', __('users.'.($user->id?'edit':'new').'_title'). ($user->id?' ['.$user->name.']':''))
@section('title-buttons')
    @include('shared/titleButtons', ['links'=>[new LinkResponsive(route('users'), 'btn btn-back', 'fa fa-arrow-left')]])
@endsection
@section('content')
<form action="{{ ($user->id?route('users.update', $user):route('users.store')) }}" method="POST">
    @if ($user->id) <input type="hidden" name="_method" value="PUT"> @endif
    {!! csrf_field() !!}
    <div class="row">
        <div class="col-md-6 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label">{{ __('app.email') }}</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required >

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="control-label">{{ __('app.name') }}</label>
            <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required >

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
        <label for="role" class="control-label">{{ __('users.role') }}</label>
        <select id="role" type="text" class="form-control" name="role" value="{{ old('role') }}" required>
            @foreach ($roles as $role)
                <option {!! $user->hasRole($role->slug) ? 'selected="true"':"" !!} value="{{$role->id}}">{{$role->name}}</option>
            @endforeach
        </select>

        @if ($errors->has('role'))
            <span class="help-block">
                <strong>{{ $errors->first('role') }}</strong>
            </span>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="control-label">{{ __('users.password') }}</label>
            <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" @if(!$user->id) required @endif autocomplete="none">

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-md-6 form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password_confirmation" class="control-label">{{ __('users.password_confirmation') }}</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" @if(!$user->id) required @endif >

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-right">
            @include('shared.submit')
        </div>
    </div>
</form>
@endsection