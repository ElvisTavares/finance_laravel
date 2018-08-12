<tr class="title">
    <th colspan="5">
        {{ __('common.description') }}
    </th>
    @for ($month=0; $month<12; $month++)
        <th colspan="2" class="{{ $isNowYear && $month==$period->actual->month ? 'actual' : '' }}">
            {{ __('common.months.'.$month) }} ({{ __('common.money-type') }})
        </th>
    @endfor
</tr>