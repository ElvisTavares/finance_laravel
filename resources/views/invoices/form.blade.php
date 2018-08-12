@extends('layouts.app')

@section('title')
    {{$action}} {{__('invoices.title')}}
@endsection

@section('title-buttons')
    @php
    $links = [
        new LinkResponsive(url("/account/" . $account->id . "/invoices"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
    ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    @php
        $issetInvoice = isset($invoice);
        $invoice = $issetInvoice ? $invoice : null
    @endphp
    <div class="{{ config('constants.classes.form') }}">
        {{ Form::open(['url' => '/account/'.$account->id.'/invoice/'.($issetInvoice ? $invoice->id : '' ), 'method' => isset($invoice) ? 'PUT' : 'POST' ]) }}
        {!! (new FormGroup(__('common.description'), new Field('text', 'description'), $errors, $invoice))->html() !!}
        {!! (new FormGroup(__('common.date-init'), new Field('date', 'date_init'), $errors, $invoice))->html() !!}
        {!! (new FormGroup(__('common.date-end'), new Field('date', 'date_end'), $errors, $invoice))->html() !!}
        {!! (new FormGroup(__('common.debit-date'), new Field('date', 'debit_date'), $errors, $invoice))->html() !!}
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection