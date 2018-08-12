@extends('layouts.app')

@section('title')
    {{ __('users.title') }}
@endsection

@section('title-buttons')
    <?php
    $nextViewMode = ($viewMode == "table" ? "card" : "table");
    $urlViewMode = url("/users?view_mode=" . $nextViewMode);
    $links = [
        new LinkResponsive(url("/"), 'btn btn-back', 'fa fa-arrow-left', __('common.back')),
        new LinkResponsive($urlViewMode, 'btn btn-change', 'fas fa-exchange-alt', __('common.' . $nextViewMode)),
        new LinkResponsive(url("/users/create"), 'btn btn-add', 'fa fa-plus', __('common.add'))
    ];
    ?>
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('laravelusers::partials.search')
            @include('laravelusers::partials.'. $viewMode )
        </div>
    </div>
    {{ $users->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
@endsection
