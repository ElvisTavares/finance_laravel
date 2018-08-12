<tbody id="users_table">
@foreach($users as $user)
    <tr>
        <td>
            {{ $user->name }}
        </td>
        <td>
            {{ Html::mailto($user->email, $user->email) }}
        </td>
        @if(config('laravelusers.rolesEnabled'))
            <td class="hidden-sm hidden-xs">
                <div class="bootstrap-tagsinput">
                    @foreach ($user->roles as $userRole)
                        <span class="badge badge-info">{{ strtoupper($userRole->name) }}</span>
                    @endforeach
                </div>
            </td>
        @endif
        <td>
            {{ formatDate($user->created_at) }}
        </td>
        <td>
            {{ formatDate($user->updated_at) }}
        </td>
        <td>
            <a class="btn btn-remove" title="{{ __('common.remove') }} {{ __('transactions.transaction') }}"
               href="{{ url("/users/".$user->id."/confirm") }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
        <td>
            <a class="btn btn-sm btn-edit btn-block" href="{{ url('users/' . $user->id."/edit") }}"
               data-toggle="tooltip" title="{{ __('users.show') }}">
                <i class="fa fa-edit"></i>
            </a>
        </td>
    </tr>
@endforeach
</tbody>