@extends('layouts.app')

@section('title', ($transaction->id ? __('common.edit') : __('common.add')) ." ". __('transactions.title'))
@section('class', config('constants.classes.form'))

@section('content')
    {!! Form::open(['route' => $transaction->id ? ['transactions.update', $account, $transaction] : ['transactions.store', $account], 'method' => $transaction->id ? 'put' : 'post']) !!}
        <div class="form-group">
            {!! Form::label('id', __('common.id')) !!}
            {!! Form::text('id', old('id', $transaction->id), ['class'=>'form-control', 'readonly' => true]) !!}
        </div>
        @if ($account->is_credit_card)
            <div class="form-group">
                {!! Form::label('invoice_id', __('common.invoice')) !!}
                {!! Form::select('invoice_id', $invoices, old('invoice_id', $transaction->invoice ? $transaction->invoice->getId() : null), ['class'=>'form-control']) !!}
            </div>
        @endif
        <div class="form-group">
            {!! Form::label('description', __('common.description')) !!}
            {!! Form::text('description', old('description', $transaction->description), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date', __('common.date')) !!}
            {!! Form::date('date', old('date', explode('T', $transaction->date)[0]), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date', __('common.is-transfer')) !!}
            {!! Form::checkbox('is_transfer', true, old('is_transfer', $transaction->isTransfer()), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('account_id_transfer', __('common.transfer-account')) !!}
            {!! Form::select('account_id_transfer', $accounts, old('account_id_transfer', $transaction->account_id_transfer), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date', __('common.is-credit')) !!}
            {!! Form::checkbox('is_credit', true, old('is_credit', $transaction->isCredit()), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date', __('common.is-paid')) !!}
            {!! Form::checkbox('is_paid', true, old('is_paid', $transaction->isPaid()), ['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('date', __('common.value')) !!}
            {!! Form::number('value', abs(old('value',  $transaction->value)), ['class'=>'form-control', 'step' => '0.01']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection