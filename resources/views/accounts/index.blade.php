@extends('layouts.app')
@section('title')
    <i class="fas fa-piggy-bank"></i> {{__('accounts.title')}}
@endsection
@section('title-buttons')
    @php
    $nextViewMode = ($viewMode == "table" ? "card" : "table");
    $urlViewMode = url("/accounts?view_mode=" . $nextViewMode);
    $urlAdd = url("/accounts/create");
    $links = [
        new LinkResponsive(url("/"), 'btn btn-back', 'fa fa-arrow-left', __('common.back')),
        new LinkResponsive($urlViewMode, 'btn btn-change', 'fas fa-exchange-alt', __('common.' . $nextViewMode)),
        new LinkResponsive($urlAdd, 'btn btn-add', 'fa fa-plus', __('common.add'))
    ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection
@section('content')
    <div class="row line-bottom default-padding">
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.avg-max')}}:</b>
            {{__('common.money-type')}} {!!formatMoney($avg->max)!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.avg-min')}}:</b>
            {{__('common.money-type')}} {!!formatMoney($avg->min)!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.avg-avg')}}:</b>
            {{__('common.money-type')}} {!!formatMoney($avg->avg)!!}
        </div>
    </div>
    <div class="row line-bottom default-padding default-margin-bottom">
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.totals-paid')}}:</b>
            {{__('common.money-type')}} {!!formatMoney($totals->paid[$period->actual->month])!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.totals-not-paid')}}:</b>
            {{__('common.money-type')}} {!!formatMoney($totals->nonPaid[$period->actual->month])!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.totals')}}:</b>
            {{__('common.money-type')}} {!!formatMoney($totals->nonPaid[$period->actual->month]+$totals->paid[$period->actual->month])!!}
        </div>
    </div>

    @include('accounts/mode_view/'.$viewMode)

    @foreach($accounts as $account)
        @include('accounts/import', ['id'=>$account->id])
    @endforeach
@endsection

@section('script')
    <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection