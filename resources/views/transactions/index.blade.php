@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{__('transactions.title')}}
          <a href="/account/{{$account->id}}/transaction/create">{{__('common.add')}}</a>
          {{$account->amount}}
        </div>

        <div class="panel-body">
          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
          <table class="table">
            <thead>
              <tr>
                <th>{{__('common.id')}}</th>
                <th>{{__('common.date')}}</th>
                <th>{{__('common.description')}}</th>
                <th>{{__('transactions.value')}}</th>
                <th>{{__('transactions.paid')}}</th>
                <th>{{__('common.actions')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transactions as $transaction)
                <tr>
                  <td>
                    {{$transaction->id}}
                  </td>
                  <td>
                    {{$transaction->date}}
                  </td>
                  <td>
                    {{$transaction->description}}
                  </td>
                  <td>
                    {{$transaction->value}}
                  </td>
                  <td>
                    <div class="checkbox">
                      <label><input disabled="true" type="checkbox" {{$transaction->paid?"checked='true'":""}}></label>
                    </div>
                  </td>
                  <td>
                    <a href="/account/{{$account->id}}/transaction/{{$transaction->id}}/edit">{{__('common.edit')}}</a>
                    <a href="/account/{{$account->id}}/transaction/{{$transaction->id}}/confirm">{{__('common.remove')}}</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection