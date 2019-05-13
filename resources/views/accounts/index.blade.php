@extends('layouts.app')
@section('title')
    <i class="fas fa-piggy-bank"></i> {{__('accounts.title')}}
@endsection
@section('class', config('constants.classes.full'))
@section('title-buttons')
    <a href="{{route('accounts.create')}}" class="btn btn-primary">
        <i class="fa fa-plus"></i> {{__('common.add')}}
    </a>
@endsection
@section('content')

    @include('accounts/table')

    <table class="table table-sm">
        <tr>
            <th>{{__('accounts.avg-max')}}:</th>
            <td>{{__('common.money-type')}} {!!_money($avg->positive)!!}</td>
            <th>{{__('accounts.avg-min')}}:</th>
            <td>{{__('common.money-type')}} {!!_money($avg->negative)!!}</td>
            <th>{{__('accounts.avg-avg')}}:</th>
            <td>{{__('common.money-type')}} {!!_money($avg->all)!!}</td>
        </tr>
        <tr>
            <th>{{__('accounts.totals-paid')}}:</th>
            <td>{{__('common.money-type')}} {!!_money($values->totalPaidActualMonth())!!}</td>
            <th>{{__('accounts.totals-not-paid')}}:</th>
            <td>{{__('common.money-type')}} {!!_money($values->totalNonPaidActualMonth())!!}</td>
            <th>{{__('accounts.totals')}}:</th>
            <td>{{__('common.money-type')}} {!!_money($values->totalActualMonth())!!}</td>
        </tr>
    </table>

    @foreach($accounts as $account)
        @include('accounts/import', ['id' => $account->id])
    @endforeach
@endsection

@section('script')
    <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection