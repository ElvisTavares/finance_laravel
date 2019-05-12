@extends('layouts.app')

@section('title', ($invoice->id ? __('common.edit') : __('common.add')) ." ". __('invoices.title'))
@section('class', config('constants.classes.form'))

@section('content')
    {!! Form::open(['route' => $invoice->id ? ['invoices.update', $account, $invoice] : ['invoices.store', $account], 'method' => $invoice->id ? 'put' : 'post']) !!}
        <div class="form-group">
            {!! Form::label('id', __('common.id')) !!}
            {!! Form::text('id', old('id', $invoice->id), ['class'=>'form-control', 'readonly' => true]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', __('common.description')) !!}
            {!! Form::text('description', old('description', $invoice->description), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date_init', __('common.date-init')) !!}
            {!! Form::date('date_init', old('date_init', explode('T', $invoice->date_init)[0]), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date_end', __('common.date-end')) !!}
            {!! Form::date('date_end', old('date_end', explode('T', $invoice->date_end)[0]), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('debit_date', __('common.debit-date')) !!}
            {!! Form::date('debit_date', old('debit_date', $invoice->debit_date), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
        </div>
    {!! Form::close() !!}
@endsection