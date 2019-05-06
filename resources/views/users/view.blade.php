@extends('layouts.app')
@section('title', __('users.show_title'). ' ['.$user->name.']')
@section('content_header')
    $links = new [LinkResponsive(route('users'), 'btn btn-back', 'fa fa-arrow-left')];
    @include('shared/titleButtons', ['links'=>$links])
@stop
@section('layout-content')
<ul class="list-group list-group-flush">
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-4 col-sm-3">
                <strong>
                    {{ __('app.id') }}
                </strong>
            </div>
            <div class="col-md-8 col-sm-9">
                {{ $user->id }}
            </div>
        </div>
    </li>
    @if ($user->name)
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-4 col-sm-3">
                    <strong>
                        {{ __('app.name') }}
                    </strong>
                </div>
                <div class="col-md-8 col-sm-9">
                    {{ $user->name }}
                </div>
            </div>
        </li>
    @endif
    @if ($user->email)
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-4 col-sm-3">
                    <strong>
                        {{ __('app.email') }}
                    </strong>
                </div>
                <div class="col-md-8 col-sm-9">
                    {{ Html::mailto($user->email, $user->email) }}
                </div>
            </div>
        </li>
    @endif
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-4 col-sm-3">
                <strong>
                    {{ __('users.roles') }}
                </strong>
            </div>
            <div class="col-md-8 col-sm-9">
                @foreach ($user->roles as $user_role)
                    <span class="badge badge-default">{{ $user_role->name }}</span>
                @endforeach
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-4 col-sm-3">
                <strong>
                    {{ __('users.level') }}
                </strong>
            </div>
            <div class="col-md-8 col-sm-9">
                @for($level = $user->level(); $level>0; $level--)
                <span class="badge badge-primary margin-half margin-left-0">
                    <?php echo $level ?>
                </span>
                @endfor
            </div>
        </div>
    </li>
</ul>
@endsection
