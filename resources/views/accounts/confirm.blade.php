@extends('layouts.app')

@section('title')
  {{__('accounts.confirm_destroy')}}
@endsection

@section('title-buttons')
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-8">
				<a class="btn btn-secondary" href="/accounts">
				  <i class="fa fa-arrow-left"></i>
				</a>
      </div>
    </div>
  </div>
@endsection

@section('content')
 {{ Form::open(['url' => '/accounts/'.$account->id.'', 'method'=>'DELETE']) }}
    {{__('accounts.confirmation_text', ['id'=>$account->id, 'description'=>$account->description])}}
  @include('shared.submit')
  {{ Form::close() }}
@endsection