@extends('layouts.app')

@section('title')
    {{__('transactions.title')}}
@endsection

@section('title-buttons')
    @php
    $nextViewMode = ($viewMode == "table" ? "card" : "table");
    $links = [
        new LinkResponsive(url("/accounts"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
    ];
    if (isset($account)) {
        $baseUrl = '/account/'. $account->id  . '/transactions/';
        $urlViewMode = url("account/" . $account->id . "/transactions?view_mode=" . $nextViewMode);
        $urlAdd = url("/account/" . $account->id . "/transaction/create");
    } else {
        $baseUrl = '/transactions/';
        $urlViewMode = url("/transactions?view_mode=" . ($viewMode == "table" ? "card" : "table"));
        $urlAdd = url("/transactions/create");
    }

    $links[] = new LinkResponsive($urlViewMode, 'btn btn-change', 'fas fa-exchange-alt', __('common.'.$viewMode));
    $links[] = new LinkResponsive($urlAdd, 'btn btn-add', 'fas fa-plus', __('common.add'));
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    @include('transactions.filters')
    @if (count($transactions)>0)
        @include('transactions.categories')
        @include('transactions.mode_view.'.$viewMode)
    @endif
@endsection