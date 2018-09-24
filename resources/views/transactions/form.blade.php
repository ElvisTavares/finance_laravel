@extends('layouts.app')

@section('title')
    {{$action}} {{__('transactions.title')}}
    @if (isset($account))
        {{__('common.in')}} {{$account->id}}/{{$account->description}}
    @endif
@endsection

@section('title-buttons')
    @php
        if (isset($account)) {
            $urlBack = url("/account/" . $account->id . "/transactions");
        } else {
            $urlBack = url("/transactions");
        }
        $links = [new LinkResponsive($urlBack, 'btn btn-back', 'fa fa-arrow-left')];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    @php
        $issetTransaction = isset($transaction);
        $transaction = $issetTransaction ? $transaction : null
    @endphp
    <div class="{{ config('constants.classes.form') }}">
        @if (isset($account))
            {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.($issetTransaction?$transaction->id:'').$query, 'method'=>($issetTransaction?'PUT':'POST')]) }}
            @if ($account->is_credit_card)
                {!! (new FormGroup(__('transactions.invoice'), new Field('select', 'invoice_id'), $errors, $transaction, $account->listInvoices()))->html() !!}
                <div id="new_invoice" class="form-group"
                     style="{{ $issetTransaction && ($transaction->invoice_id==-1 || $transaction->invoice_id==null) ? '' : 'display: none' }};">
                    {!! (new FormGroup(__('common.description'), new Field('text', 'invoice_description'), $errors, $transaction))->html() !!}
                    {!! (new FormGroup(__('common.date-init'), new Field('date', 'invoice_date_init'), $errors, $transaction))->html() !!}
                    {!! (new FormGroup(__('common.date-end'), new Field('date', 'invoice_date_end'), $errors, $transaction))->html() !!}
                    {!! (new FormGroup(__('common.debit-date'), new Field('date', 'invoice_debit_date'), $errors, $transaction))->html() !!}
                </div>
            @endif
            {!! (new FormGroup(__('common.date'), new Field('date', 'date'), $errors, $transaction))->html() !!}
            {!! (new FormGroup(__('common.description'), new Field('text', 'description'), $errors, $transaction))->html() !!}
            {!! (new FormGroup(__('transactions.is-transfer'), new Field('checkbox', 'is_transfer'), $errors, $transaction))->html() !!}
            {!! (new FormGroup(__('common.description'), new Field('select', 'account_id_transfer'), $errors, $transaction, $accounts->pluck('description', 'id')))->html() !!}
            {!! (new FormGroup(__('transactions.is-credit'), new Field('checkbox', 'is_credit', ['value' => $issetTransaction && $transaction->value < 0]), $errors, $transaction))->html() !!}
            {!! (new FormGroup(__('transactions.value'), new Field('number', 'value',['value' => $issetTransaction ? abs($transaction->value) : 0, 'step' => '0.01']), $errors, $transaction))->html() !!}
            @if (!$account->is_credit_card)
                {!! (new FormGroup(__('transactions.paid'), new Field('checkbox', 'paid'), $errors, $transaction))->html() !!}
            @endif
            {!! (new FormGroup(__('common.categories'), new Field('text', 'categories', ['class'=>'form-control', 'data-role'=>'tagsinput', 'value' => ($issetTransaction ? $transaction->categoriesString() : ''), 'required' => false]), $errors, $transaction))->html() !!}
            <hr>
            @include('shared.submit')
            {{ Form::close() }}
        @else
            {{ Form::open(['url' => URL::current(), 'method'=>'GET']) }}
            {!! (new FormGroup(__('accounts.title'), new Field('select', 'account'), $errors, $transaction, \Auth::user()->listAccounts()))->html() !!}
            @include('shared.submit', ['text'=>__('common.select'), 'iconClass'=>'fas fa-check'])
            {{ Form::close() }}
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection