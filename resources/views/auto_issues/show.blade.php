@extends('layouts.app')

@section('title')
    {{$issue['title']}}
@endsection

@section('title-buttons')
    @php
        $links = [
            new LinkResponsive(url("/issues"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
        ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <pre>
        {{$issue['body']}}
    </pre>
@endsection