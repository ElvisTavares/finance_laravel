@extends('layouts.app')

@section('title')
  {{__('transactions.confirm_destroy')}}
@endsection

@section('title-buttons')
  <div class="container">
    <div class="row">
      <div class=" col-md-4 offset-md-8">
			  <a class="btn btn-secondary" href="/account/{{$account->id}}/transactions">
			    <i class="fa fa-arrow-left"></i>
			  </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
{{ Form::open(['url' => '/account/'.$account->id.'/transaction/'.$transaction->id, 'method'=>'DELETE']) }}
  {{__('transactions.confirmation_text', ['id'=>$transaction->id, 'description'=>$transaction->description, 'accountId'=>$account->id, 'accountDescription'=>$account->description])}}
  @include('shared.submit')
{{ Form::close() }} 
@endsection