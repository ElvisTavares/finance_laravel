@extends('layouts.app')

@section('title', ($account->id ? __('common.edit') : __('common.add')) ." ". __('accounts.title'))
@section('class', config('constants.classes.form'))

@section('content')
    {!! Form::open(['route' => $account->id ? ['accounts.update', $account] : 'accounts.store', 'method' => $account->id ? 'put' : 'post']) !!}
        <div class="form-group">
            {!! Form::label('id', __('common.id')) !!}
            {!! Form::text('id', old('id', $account->id), ['class'=>'form-control', 'readonly' => true]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', __('common.description')) !!}
            {!! Form::text('description', old('description', $account->description), ['class'=>'form-control']) !!}
        </div>
        @if (!$account->id)
            <div class="form-group">
                {!! Form::label('is_credit_card', __('accounts.is-credit-card')) !!}
                {!! Form::checkbox('is_credit_card', true, old('is_credit_card', $account->is_credit_card)) !!}
            </div>
        @endif
        @if (!$account->id || ($account->id && $account->is_credit_card))
            <div class="form-group" {!!$account->is_credit_card ? '' : 'style="display: none;"'!!}>
                {!! Form::label('prefer_debit_account_id', __('accounts.prefer-debit-account')) !!}
                {!! Form::select('prefer_debit_account_id', $select, old('prefer_debit_account_id', $account->prefer_debit_account_id), ['class'=>'form-control']) !!}
            </div>
        @endif
        <div class="form-group">
            {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    <script src="{{ asset('js/accounts/form.js') }}"></script>
@endsection