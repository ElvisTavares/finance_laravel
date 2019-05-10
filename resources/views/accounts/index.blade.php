@extends('layouts.app')
@section('title')
    <i class="fas fa-piggy-bank"></i> {{__('accounts.title')}}
@endsection
@section('content')
    <div class="row line-bottom default-padding">
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.avg-max')}}:</b>
            {{__('common.money-type')}} {!!_e_money($avg->positive)!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.avg-min')}}:</b>
            {{__('common.money-type')}} {!!_e_money($avg->negative)!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.avg-avg')}}:</b>
            {{__('common.money-type')}} {!!_e_money($avg->all)!!}
        </div>
    </div>
    <div class="row line-bottom default-padding default-margin-bottom">
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.totals-paid')}}:</b>
            {{__('common.money-type')}} {!!_e_money($values->totalPaidActualMonth())!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.totals-not-paid')}}:</b>
            {{__('common.money-type')}} {!!_e_money($values->totalNonPaidActualMonth())!!}
        </div>
        <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
            <b>{{__('accounts.totals')}}:</b>
            {{__('common.money-type')}} {!!_e_money($values->totalActualMonth())!!}
        </div>
    </div>

    @include('accounts/table')

    @foreach($accounts as $account)
        @include('accounts/import', ['id'=>$account->id])
    @endforeach
@endsection

@section('script')
    <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection