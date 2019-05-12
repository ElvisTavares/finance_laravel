@extends('layouts.app')

@section('title', __('invoices.confirm-destroy'))
@section('class', config('constants.classes.form'))
@section('content')
    {{ Form::open(['route' => ['transactions.destroy', $account->id, $transaction->id], 'method'=>'DELETE']) }}
        <div class="form-group">
            {{__('transactions.confirmation-text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}}
        </div>
        <div class="form-group">
            {!! Form::submit(__('common.remove'), ['class' => 'btn btn-danger']); !!}
        </div>
    {{ Form::close() }}
@endsection