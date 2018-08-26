@extends('layouts.app')

@section('title')
    {{__('issues.title')}}
@endsection

@section('title-buttons')
    @php
        $links = [
            new LinkResponsive(url("/"), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
        ];
    @endphp
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <table class="table table-sm">
        <thead>
            <tr>
                <td>
                    {{__('issues.title')}}
                </td>
                <td>
                    {{__('common.actions')}}
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach($issues as $issue)
                <tr>
                    <td>
                        {{$issue['title']}}
                    </td>
                    <td>
                        <a class="btn btn-list" title="{{ __('issues.show') }}"
                           href="{{ url('issues/'.$issue['number']) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection