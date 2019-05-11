<ul class="nav nav-tabs">
    @foreach ($values->avaliableYears() as $year)
        <li class="nav-item primary-color">
            <a class="nav-link {{ $values->isThisYear($year) ? 'active' : ''}}"
               href="{{ route('accounts.index', ['year'=> $year]) }}">
               {{ $year }}
            </a>
        </li>
    @endforeach
</ul>
<div class="table-responsive">
    <table class="{{config('constants.classes.table')}}" id="accounts">
        <thead>
            <tr>
                <th colspan="4">
                    {{ __('common.description') }}
                </th>
                @for ($month=0; $month<12; $month++)
                    <th colspan="2" data-month="m{{$month}}">
                        {{ __('common.months.'.$month) }} ({{ __('common.money-type') }})
                    </th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
                <tr>
                    <th class="title" colspan="4" data-account="a{{$account->id}}">
                        {{ $account->description }}
                    </th>
                    @for($month=0; $month<12; $month++)
                        <td class="{{ $values->isThisMonth($month) ? 'actual' : '' }}" rowspan="3" data-account="a{{$account->id}}" data-month="m{{$month}}">
                            @if (isset($account->invoices[$month]))
                                <a class="btn btn-list" title="{{ __('transactions.title') }}"
                                    href="{{ route('accounts.transactions', ['account' => $account->id, 'invoice_id' => $account->invoices[$month]->id()]) }}">
                                    <i class="fa fa-list"></i>
                                </a>
                            @elseif (!$account->is_credit_card)
                                <a class="btn btn-list" title="{{ __('transactions.title') }}"
                                    href="{{ route('accounts.transactions', [ 'account' => $account->id, 'date_init' => $values->getInit($month), 'date_end' => $values->getEnd($month)]) }}">
                                    <i class="fa fa-list"></i>
                                </a>
                            @endif
                        </td>
                        <td class="{{ $values->isThisMonth($month) ? 'actual' : '' }} text-right"
                             data-account="a{{$account->id}}" data-month="m{{$month}}" title="{{ __('accounts.totals-paid') }}">
                            {!! _e_money($values->getPaid($account, $month)) !!}
                        </td>
                    @endfor
                </tr>
                <tr>
                    <td rowspan="2" data-account="a{{$account->id}}">
                        <a class="btn btn-edit"
                            title="{{ __('common.edit') }} {{ __('accounts.account') }}"
                            href="{{ route('accounts.edit', ['account' => $account->id]) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td rowspan="2" data-account="a{{$account->id}}">
                        <a class="btn btn-remove"
                            title="{{ __('common.remove') }} {{ __('accounts.account') }}"
                            href="{{ route('accounts.confirm', ['account' => $account->id]) }}">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                    <td rowspan="2" colspan="{{ $account->is_credit_card ? 1 : 2 }}" data-account="a{{$account->id}}">
                        <a class="btn btn-import"
                            title="{{ __('common.import') }} {{ __('accounts.account') }}" href="#"
                            data-toggle="modal" data-target="#model_account_{{$account->id}}">
                            <i class="fa fa-upload"></i>
                        </a>
                    </td>
                    @if ($account->is_credit_card)
                        <td rowspan="2" data-account="a{{$account->id}}">
                            <a class="btn btn-list"
                                title="{{ __('invoices.title') }} {{ __('accounts.account') }}"
                                href="{{ route('accounts.invoices', ['account' => $account->id]) }}">
                                <i class="fas fa-receipt"></i>
                            </a>
                        </td>
                    @endif
                    @for($month=0; $month<12; $month++)
                        <td class="{{ $values->isThisMonth($month) ? 'actual' : '' }} text-right"
                            title="{{ __('accounts.totals-not-paid') }}" data-account="a{{$account->id}}" data-month="m{{$month}}">
                            {!! _e_money($values->getNonPaid($account, $month)) !!}
                        </td>
                    @endfor
                </tr>
                <tr>
                    @for($month=0; $month<12; $month++)
                        <td class="{{ $values->isThisMonth($month) ? 'actual' : '' }} text-right"
                            title="{{ __('accounts.totals') }}" data-account="a{{$account->id}}" data-month="m{{$month}}">
                            {!! _e_money($values->getTotal($account, $month)) !!}
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">
                    {{__('accounts.totals-paid')}}:
                </th>
                @for($month=0; $month<12; $month++)
                    <th class="text-right" colspan="2" data-month="m{{$month}}">
                        {!! _e_money($values->totalPaid($month)) !!}
                    </th>
                @endfor
            </tr>
            <tr>
                <th colspan="4">
                    {{ __('accounts.totals-not-paid') }}:
                </th>
                @for($month=0; $month<12; $month++)
                    <th class="text-right" colspan="2" data-month="m{{$month}}">
                        {!!_e_money($values->totalNonPaid($month))!!}
                    </th>
                @endfor
            </tr>
            <tr>
                <th colspan="4">
                    {{__('accounts.totals')}}:
                </th>
                @for($month=0; $month<12; $month++)
                    <th class="text-right" colspan="2" data-month="m{{$month}}">
                        {!!_e_money($values->total($month))!!}
                    </th>
                @endfor
            </tr>
        </tfoot>
    </table>
</div>