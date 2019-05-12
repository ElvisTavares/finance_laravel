@extends('layouts.app')

@section('title', __('accounts.confirm-destroy'))
@section('class', config('constants.classes.form'))
@section('content')
    {{ Form::open(['url' => '/accounts/'.$account->id.'', 'method'=>'DELETE']) }}
        <div class="form-group">
            {{__('accounts.confirmation-text', ['id' => $account->id, 'description' =>$account->description])}}
        </div>
        <div class="form-group">
            {!! Form::submit(__('common.remove'), ['class' => 'btn btn-danger']); !!}
        </div>
    {{ Form::close() }}
@endsection