<tfoot>
<tr>
    <td id="user_count" colspan="{{ config('laravelusers.rolesEnabled') ? 7 : 6 }}">
        {{ trans_choice('users.search-caption', 1, ['userscount' => $users->count()]) }}
    </td>
</tr>
</tfoot>