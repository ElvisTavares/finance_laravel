<tr class="title">
    <th colspan="5">
        {{ __('common.description') }}
    </th>
    @for ($month=0; $month<12; $month++)
        <th colspan="2" style="border-left: 2px solid;">
            {{ __('common.months.'.$month) }} ({{ __('common.money-type') }})
        </th>
    @endfor
</tr>