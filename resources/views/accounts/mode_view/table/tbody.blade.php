@foreach($accounts as $account)
    <tr>
        <th class="title" rowspan="3" style="border-bottom: 2px solid;">
            {{ $account->description }}
        </th>
        <td class="title " rowspan="3" style="border-bottom: 2px solid;">
            <a class="btn btn-edit" title="{{ __('common.edit') }} {{ __('accounts.account') }}"
               href={{ url("/accounts/".$account->id."/edit") }}>
                <i class="fa fa-edit"></i>
            </a>
        </td>
        <td class="title " rowspan="3" style="border-bottom: 2px solid;">
            <a class="btn btn-remove" title="{{ __('common.remove') }} {{ __('accounts.account') }}"
               href="{{ url("/accounts/".$account->id."/confirm") }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
        <td class="title" rowspan="3" colspan="{{ $account->is_credit_card ? 1 : 2 }}" style="border-bottom: 2px solid;">
            <a class="btn btn-import" title="{{ __('common.import') }} {{ __('accounts.account') }}" href="#"
               data-toggle="modal" data-target="#model_account_{{$account->id}}">
                <i class="fa fa-upload"></i>
            </a>
        </td>
        @if ($account->is_credit_card)
            <td class="title" rowspan="3">
                <a class="btn btn-list" title="{{ __('invoices.title') }} {{ __('accounts.account') }}"
                   href="{{ url("/account/".$account->id."/invoices") }}">
                    <i class="fas fa-receipt"></i>
                </a>
            </td>
        @endif
        @for($month=0; $month<12; $month++)
            @php
                $isNowMonth = $isNowYear && $month == $period->actual->month;
                $baseUrl = "/account/" . $account->id . "/transactions";
            @endphp
            <td class="{{ $isNowMonth ? 'bg-dark' : '' }}" rowspan="3" style="border: 2px solid; border-right: none;">
                @if ($account->is_credit_card && isset($account->invoices) && isset($account->invoices[$month]))
                    <a class="btn btn-list" title="{{ __('transactions.title') }}"
                       href="{{ url($baseUrl."?invoice_id=".$account->invoices[$month]->encryptedId()) }}">
                        <i class="fa fa-list"></i>
                    </a>
                @elseif (!$account->is_credit_card)
                    @php($periodMonth = $period->months[$month])
                    <a class="btn btn-list" title="{{ __('transactions.title') }}"
                       href="{{ url($baseUrl."?date_init=".$periodMonth->init."&date_end=".$periodMonth->end) }}">
                        <i class="fa fa-list"></i>
                    </a>
                @endif
            </td>
            <td class="{{ $isNowMonth ? 'bg-dark' : '' }} text-right" title="{{ __('accounts.totals-paid') }}"
                style="border-right: 2px solid;">
                {!! formatMoney($values->paid[$account->id][$month]) !!}
            </td>
        @endfor
    </tr>
    <tr>
        @for($month=0; $month<12; $month++)
            <td class="{{ $isNowYear && $month==$period->actual->month ? 'bg-dark' : '' }} text-right"
                title="{{ __('accounts.totals-not-paid') }}" style="border-right: 2px solid;">
                {!! formatMoney($values->nonPaid[$account->id][$month]) !!}
            </td>
        @endfor
    </tr>
    <tr>
        @for($month=0; $month<12; $month++)
            <td class="{{ $isNowYear && $month==$period->actual->month ? 'bg-dark' : '' }} text-right"
                style="font-weight: bold; border-bottom: 2px solid; border-right: 2px solid;" title="{{ __('accounts.totals') }}">
                {!! formatMoney($values->paid[$account->id][$month] + $values->nonPaid[$account->id][$month]) !!}
            </td>
        @endfor
    </tr>
@endforeach