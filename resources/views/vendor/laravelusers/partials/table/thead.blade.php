<thead class="thead">
<tr>
    <th>
        {{ __('users.name') }}
    </th>
    <th>
        {{ __('users.email') }}
    </th>
    @if(config('laravelusers.rolesEnabled'))
        <th>
            {{ __('users.role') }}
        </th>
    @endif
    <th>
        {{ __('users.created') }}
    </th>
    <th>
        {{ __('users.updated') }}
    </th>
    <th colspan="2">
        {{ __('users.actions') }}
    </th>
</tr>
</thead>