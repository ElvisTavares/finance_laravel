<tr class="title">
    <th colspan="5" style="border-left: 2px solid;">
        {{__('accounts.totals-paid')}}:
    </th>
    @for($month=0; $month<12; $month++)
        <th class="{{$isNowYear && $month==$period->actual->month?'bg-dark':''}} text-right" colspan="2" style="border-left: 2px solid;">
            {!! formatMoney($totals->paid[$month]) !!}
        </th>
    @endfor
</tr>
<tr class="title">
    <th colspan="5" style="border-left: 2px solid;">
        {{ __('accounts.totals-not-paid') }}:
    </th>
    @for($month=0; $month<12; $month++)
        <th class="{{ $isNowYear && $month==$period->actual->month ? 'bg-dark' : '' }} text-right" colspan="2" style="border-left: 2px solid;">
            {!!formatMoney($totals->nonPaid[$month])!!}
        </th>
    @endfor
</tr>
<tr class="title">
    <th colspan="5" style="border-left: 2px solid;">
        {{__('accounts.totals')}}:
    </th>
    @for($month=0; $month<12; $month++)
        <th class="{{ $isNowYear && $month == $period->actual->month ? 'bg-dark' : '' }} text-right" colspan="2" style="border-left: 2px solid;">
            {!! formatMoney($totals->nonPaid[$month] + $totals->paid[$month]) !!}
        </th>
    @endfor
</tr>