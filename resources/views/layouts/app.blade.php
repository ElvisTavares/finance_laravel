<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('stylesheet')
</head>
<body>
<div id="app">
    @include('layouts.nav')
    <div class="container-fluid ">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="@yield('class')">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid card-header">
                                <div class="row">
                                    <div class="col-sm-6 col-md-8">
                                        <h2 class="card-title">@yield('title')</h2>
                                    </div>
                                    <div class="col-sm-6 col-md-4 text-right">
                                        <a href="javascript:window.history.back();" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> {{__('common.back')}}
                                        </a>
                                        @yield('title-buttons')
                                    </div>
                                </div>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>
