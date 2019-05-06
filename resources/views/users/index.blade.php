@extends('layouts.app')
@section('title', __('users.users'))
@section('title-buttons')
    @include('shared/titleButtons', [
        'links'=>[
            new LinkResponsive(route('home'), 'btn btn-back', 'fa fa-arrow-left'),
            new LinkResponsive(route('users.create'),'btn btn-add', 'fa fa-plus', __('common.add'))
        ]
    ])
@stop
@section('content')
<div class="table-responsive users-table">
    <table class="table table-striped table-sm data-table">
        <thead class="thead">
            <tr>
                <th>
                    {{ __('common.id') }}
                </th>
                <th>
                    {{ __('common.name') }}
                </th>
                <th class="no-search no-sort" colspan="{{Auth::user()->isAdmin() ? 5 : 4}}">
                    {{ __('common.actions') }}
                </th>
            </tr>
        </thead>
        <tbody id="users_table">
            @foreach($users as $user)
                <tr>
                    <td>
                        {{$user->id}}
                    </td>
                    <td>
                        {{$user->name}}<br>
                        @foreach ($user->roles as $user_role)
                        <span class="badge bg-primary">
                            {{ $user_role->name }}
                        </span>
                        @endforeach
                    </td>
                    <td>
                        <a class="btn btn-edit" title="{{__('common.edit')}} {{__('users.user')}}" href="{{route('users.edit', $user)}}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-remove" title="{{__('common.remove')}} {{__('users.user')}}" href="{{route('user.destroy', $user)}}">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if($pagintaionEnabled)
        {{ $users->links() }}
    @endif
</div>
@endsection