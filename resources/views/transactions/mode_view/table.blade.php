<table class="table" style="margin-top:10px;">
    <thead>
    <tr>
        <th>{{ __('common.id') }}</th>
        <th>{{ __('common.date') }}</th>
        <th>{{ __('transactions.invoice') }}</th>
        <th>{{ __('common.description') }}</th>
        <th>{{ __('common.categories') }}</th>
        <th class="text-center">{{ __('transactions.value') }}</th>
        <th class="text-center">{{ __('transactions.paid') }}</th>
        <th class="text-center" colspan="3">{{ __('common.actions') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transactions as $transaction)
        <tr>
            <td>
                {{ $transaction->id }}
            </td>
            <td>
                {{formatDate($transaction->date) }}
            </td>
            <td>
                @if ($transaction->account->is_credit_card && $transaction->invoice)
                    {{ $transaction->invoice->description }}
                @endif
            </td>
            <td>
                {{ $transaction->description }}
            </td>
            <td>
                @if (count($transaction->categories)>0)
                    <div class="bootstrap-tagsinput">
                        @foreach ($transaction->categories as $category)
                            <span class="badge badge-info">{{ $category->category->description }}</span>
                        @endforeach
                    </div>
                @endif
            </td>
            <td class="text-right">
                {!! formatMoney($transaction->value) !!}
            </td>
            <td class="text-center">
                @if (!$transaction->account->is_credit_card)
                    <div class="checkbox">
                        <label style="margin-bottom: 0px;">
                            <input style="vertical-align: middle;" disabled="true"
                                   type="checkbox" {!! $transaction->paid?"checked='true'":"" !!}/>
                        </label>
                    </div>
                @endif
            </td>
            <td class="text-center">
                <a class="btn btn-list" title="{{ __('common.repeat') }} {{ __('transactions.transaction') }}"
                   href="{{ url("/account/".$transaction->account_id."/transaction/".$transaction->id."/repeat".$query) }}">
                    <i class="fas fa-redo-alt"></i>
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-edit" title="{{ __('common.edit') }} {{ __('transactions.transaction') }}"
                   href="{{ url("/account/".$transaction->account_id."/transaction/".$transaction->id."/edit".$query) }}">
                    <i class="fa fa-edit"></i>
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-remove" title="{{ __('common.remove') }} {{ __('transactions.transaction') }}"
                   href="{{ url("/account/".$transaction->account_id."/transaction/".$transaction->id."/confirm".$query) }}">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
    @if ($transactions->links()!='')
        <tfoot>
        <tr>
            <td colspan="11">
                {{ $transactions->links('vendor.pagination.bootstrap-4') }}
            </td>
        </tr>
        </tfoot>
    @endif
</table>