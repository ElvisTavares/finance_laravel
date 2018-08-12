<div class="row">
    @foreach($accounts as $account)
        @php
            $paid = $values->paid[$account->id][$period->actual->month];
            $nonPaid = $values->nonPaid[$account->id][$period->actual->month];
        @endphp
        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $account->description }}</h5>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                {{ __('accounts.totals-paid') }}:
                            </div>
                            <div class="col-md-6 text-right">
                                {{ __('common.money-type') }} {!! formatMoney($paid) !!}
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                {{ __('accounts.totals-not-paid') }}:
                            </div>
                            <div class="col-md-6 text-right">
                                {{ __('common.money-type') }} {!! formatMoney($nonPaid) !!}
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                {{ __('accounts.totals') }}:
                            </div>
                            <div class="col-md-6 text-right">
                                {{ __('common.money-type') }} {!! formatMoney($paid+$nonPaid) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-edit" title="{{ __('common.edit') }} {{ __('accounts.account') }}"
                       href="{{ url("/accounts/".$account->id."/edit") }}">
                        <i class="fa fa-edit"></i> {{ __('common.edit') }}
                    </a>
                    <a class="btn btn-remove" title="{{ __('common.remove') }} {{ __('accounts.account') }}"
                       href="{{ url("/accounts/".$account->id."/confirm") }}">
                        <i class="fa fa-trash"></i> {{ __('common.remove') }}
                    </a>
                    <a class="btn btn-import" title="{{ __('common.import') }} {{ __('accounts.account') }}" href="#"
                       data-toggle="modal" data-target="#model_account_{{$account->id}}">
                        <i class="fa fa-upload"></i> {{ __('common.import') }}
                    </a>
                    @php($periodMonth = $period->months[$period->actual->month])
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-{{ $account->is_credit_card ? 6 : 12 }}">
                                <a class="btn btn-list" style="margin-right: 5px;" title="{{ __('transactions.title') }}"
                                   href="{{ url("/account/".$account->id."/transactions?date_init=". $periodMonth->init."&date_end=". $periodMonth->end)}}">
                                    <i class="fa fa-list"></i> {{ __('transactions.title') }}
                                </a>
                            </div>
                            @if ($account->is_credit_card)
                                <div class="col-6">
                                    <a class="btn btn-list" title="{{ __('invoices.title') }} {{ __('accounts.account') }}"
                                       href="{{ url("/account/".$account->id."/invoices") }}">
                                        <i class="fas fa-receipt"></i> {{ __('invoices.title') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>