@extends('layouts.app')

@section('title')
    {{__('common.repeat')}} {{__('transactions.title')}} {{__('common.in')}} {{$account->id}}/{{$account->description}}
@endsection

@section('title-buttons')
    @php
        $links = [
            new LinkResponsive(url("/account/".$account->id."/transactions"), 'btn btn-back', 'fa fa-arrow-left')
        ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <div class="{{ config('constants.classes.form') }}">
        @php($query = ( isset($_GET['date_init']) && isset($_GET['date_end']) ? '?date_init='.$_GET['date_init'].'&date_end='.$_GET['date_end']   : ''))
        {{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.$transaction->id.'/confirmRepeat'.$query, 'method'=>'POST']) }}
        {{__('transactions.repeat-text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}}
        <input type="number" name="times" min="1" value="1" style="text-align: right;"> {{__('transactions.times')}}
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection