<tr class="title">
    <th colspan="5">
        {{__('accounts.totals-paid')}}:
    </th>
    @for($month=0; $month<12; $month++)
        <th class="{{$isNowYear && $month==$period->actual->month?'actual':''}} text-right" colspan="2">
            {!! formatMoney($totals->paid[$month]) !!}
        </th>
    @endfor
</tr>
<tr class="title">
    <th colspan="5">
        {{ __('accounts.totals-not-paid') }}:
    </th>
    @for($month=0; $month<12; $month++)
        <th class="{{ $isNowYear && $month==$period->actual->month ? 'actual' : '' }} text-right" colspan="2">
            {!!formatMoney($totals->nonPaid[$month])!!}
        </th>
    @endfor
</tr>
<tr class="title">
    <th colspan="5">
        {{__('accounts.totals')}}:
    </th>
    @for($month=0; $month<12; $month++)
        <th class="{{ $isNowYear && $month == $period->actual->month ? 'actual' : '' }} text-right" colspan="2">
            {!! formatMoney($totals->nonPaid[$month] + $totals->paid[$month]) !!}
        </th>
    @endfor
</tr>