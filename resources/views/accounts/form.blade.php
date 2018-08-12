@extends('layouts.app')

@section('title')
    {{$action}} {{__('accounts.title')}}
@endsection

@section('title-buttons')
    @php
        $links = [
            new LinkResponsive(url("/accounts"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
        ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    @php
        $issetAccounts = isset($account);
        $account = $issetAccounts ? $account : null
    @endphp
    <div class="{{ config('constants.classes.form') }}">
        {{ Form::open(['url' => '/accounts'.($issetAccounts?'/'.$account->id:''), 'method'=>($issetAccounts?'PUT':'POST')]) }}
        {!! (new FormGroup(__('common.description'), new Field('text', 'description'), $errors, $account))->html() !!}
        @if (!$issetAccounts)
            {!! (new FormGroup(__('accounts.is-credit-card'), new Field('checkbox', 'is_credit_card'), $errors, $account))->html() !!}
        @endif
        @if (!$issetAccounts || ($issetAccounts && $account->is_credit_card))
            {!! (new FormGroup(__('accounts.prefer-debit-account'), new Field('select', 'prefer_debit_account_id'), $errors, $account, $selectAccounts))->html() !!}
        @endif
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/accounts/form.js') }}"></script>
@endsection