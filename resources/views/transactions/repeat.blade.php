@extends('layouts.app')

@section('title')
    {{__('common.repeat')}} {{__('transactions.title')}} {{__('common.in')}} {{$account->id}}/{{$account->description}}
@endsection

@section('class', config('constants.classes.form'))

@section('content')
    {!! Form::open(['route' => ['transactions.repeat', $account, $transaction]]) !!}
        <div class="form-group">
        <div class="form-group">
            {!!
                Form::label('id', __('transactions.repeat-text', [
                    'id' => $transaction->id,
                    'description' => $transaction->description,
                    'accountId' => $account->id,
                    'accountDescription' => $account->description
                ])
            ) !!}
            {!! Form::number('times', old('times'), ['class'=>'form-control', 'min'=> 1, 'value'=>1]) !!}
        </div>
        <div class="form-group">
            {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection