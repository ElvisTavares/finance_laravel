@extends('layouts.app')

@section('title')
    {{$action}} {{__('users.title')}}
@endsection

@section('title-buttons')
    <?php
    $links = [
        new LinkResponsive(url('/users'), 'btn btn-back', 'fa fa-arrow-left', __('common.back'))
    ];
    ?>
    @include('shared/titleButtons', ['links'=>$links])
@endsection

@section('content')
    @php
        $issetUser = isset($user);
        $user =$issetUser ? $user : null;
        $currentRoleId = isset($currentRole) ? $currentRole->id : null;
    @endphp
    <div class="offset-md-7 col-md-5 offset-lg-8 col-lg-4 offset-xl-9 col-xl-3">
        {{ Form::open(['url' => url('/users/'.($issetUser ? $user->id : '')), 'method' => ($issetUser ? 'PUT' : 'POST')]) }}
        {!! (new FormGroup(__('login.name'), new Field('text', 'name'), $errors, $user))->html() !!}
        {!! (new FormGroup(__('login.email'), new Field('email', 'email'), $errors, $user))->html() !!}
        {!! (new FormGroup(__('login.role'), new Field('select', 'role', ['value' => $currentRoleId]), $errors, $user, $roles->pluck('id', 'description')))->html() !!}
        {!! (new FormGroup(__('login.password'), new Field('password', 'password'), $errors, $user))->html() !!}
        {!! (new FormGroup(__('login.password-confirmation'), new Field('password', 'password_confirmation'), $errors, $user))->html() !!}
        @include('shared.submit')
        {{ Form::close() }}
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/transactions/form.js') }}"></script>
@endsection