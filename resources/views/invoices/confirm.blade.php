@extends('layouts.app')

@section('title', __('invoices.confirm-destroy'))
@section('class', config('constants.classes.form'))
@section('content')
    {{ Form::open(['route' => ['invoices.destroy', $account->id, $invoice->id], 'method'=>'DELETE']) }}
        <div class="form-group">
            {{__('invoices.confirmation-text', ['id' => $invoice->id, 'description' =>$invoice->description])}}
        </div>
        <div class="form-group">
            {!! Form::submit(__('common.remove'), ['class' => 'btn btn-danger']); !!}
        </div>
    {{ Form::close() }}
@endsection