@extends('layouts.app')

@section('title')
  {{__('invoices.title')}}
@endsection

@section('title-buttons')
    @php
        $nextViewMode = ($viewMode == "table" ? "card" : "table");
        $urlViewMode = url("/account/".$account->id."/invoices?view_mode=".$nextViewMode);
        $urlAdd = url("/account/".$account->id."/invoice/create");
        $links = [
            new LinkResponsive(url("/accounts"), 'btn btn-back', 'fa fa-arrow-left', __('common.back')),
            new LinkResponsive($urlViewMode, 'btn btn-change', 'fas fa-exchange-alt', __('common.'.$nextViewMode)),
            new LinkResponsive($urlAdd, 'btn btn-add', 'fa fa-plus', __('common.add'))
        ];
    @endphp
@include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
@include('invoices/mode_view/'.$viewMode)
@foreach($invoices as $invoice)
  @include('invoices/import', ['account'=>$account->id, 'id'=>$invoice->id])
@endforeach
@endsection