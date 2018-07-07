@extends('layouts.app')
@section('title')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-9">
        {{__('accounts.title')}}      
      </div>
    </div>
  </div>
@endsection
@section('title-buttons')
<?php
  $links = [
    (object) [
      "url" => url("/"),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => backButton()->btnClass,
      "iconClass" => backButton()->iconClass
    ],
    (object) [
      "url" => url("/accounts?view_mode=".($modeView=="table"?"card":"table")),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => modeViewButton()->btnClass,
      "iconClass" => modeViewButton()->iconClass
    ],
    (object) [
      "url" => url("/accounts/create"),
      "colMd" => 4,
      "colSm" => 4,
      "btnClass" => addButton()->btnClass,
      "iconClass" => addButton()->iconClass
    ]
  ];
?>
@include('shared/titleButtons', ['links'=>$links])
@endsection
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
        <b>{{__('accounts.avg_max')}}</b>
        {{__('common.money_type')}} {!!format_money($avg->avgMax)!!}
      </div>
      <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
        <b>{{__('accounts.avg_min')}}</b>
        {{__('common.money_type')}} {!!format_money($avg->avgMin)!!}
      </div>
      <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
        <b>{{__('accounts.avg_avg')}}</b>
        {{__('common.money_type')}} {!!format_money($avg->avgAvg)!!}
      </div>
    </div>
    <hr>

    <div class="row">
      <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
        <b>{{__('accounts.totals_paid')}}</b>
        {{__('common.money_type')}} {!!format_money($calcResult->sumPaid[$period->actualMonth])!!}
      </div>
      <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
        <b>{{__('accounts.totals_not_paid')}}</b>
        {{__('common.money_type')}} {!!format_money($calcResult->sumNotPaid[$period->actualMonth])!!}
      </div>
      <div class="col-sm-12 col-md-4 col-xl-4 col-lg-4 text-center">
        <b>{{__('accounts.totals')}}</b>
        {{__('common.money_type')}} {!!format_money($calcResult->sumNotPaid[$period->actualMonth]+$calcResult->sumPaid[$period->actualMonth])!!}
      </div>
    </div>
    <hr>
  </div>

  @include('accounts/mode_view/'.$modeView)

  @foreach($accounts->result as $account)
    @include('accounts/import', ['isAccount'=>true, 'id'=>$account->id])
  @endforeach
@endsection

@section('script')
  <script src="{{ asset('js/accounts/index.js') }}"></script>
@endsection