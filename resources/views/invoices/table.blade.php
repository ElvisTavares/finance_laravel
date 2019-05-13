<table class="{{config('constants.classes.table')}}">
  <thead>
    <tr class="active">
      <th>{{__('common.id')}}</th>
      <th>{{__('common.description')}}</th>
      <th>{{__('invoices.date-init')}}</th>
      <th>{{__('invoices.date-end')}}</th>
      <th>{{__('invoices.debit-date')}}</th>
      <th>{{__('invoices.total')}}</th>
      <th colspan="4">{{__('common.actions')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($invoices as $invoice)
      <tr>
        <td>{{$invoice->id}}</td>
        <td>{{$invoice->description}}</td>
        <td>{{_date($invoice->date_init)}}</td>
        <td>{{_date($invoice->date_end)}}</td>
        <td>{{_date($invoice->debit_date)}}</td>
        <td>{{_money($invoice->total())}}</td>
        <td>
          <a class="btn btn-import" title="{{__('common.import')}} {{__('accounts.account')}}"
            href="#" data-toggle="modal" data-target="#model_account_{{$invoice->id}}">
            <i class="fa fa-upload"></i>
          </a>
        </td>
        <td>
           <a class="btn btn-edit" title="{{__('common.edit')}} {{__('invoices.invoice')}}"
            href="{{route('invoices.edit',[$account, $invoice])}}">
            <i class="fa fa-edit"></i>
          </a>
        </td>
        <td>
          <a class="btn btn-remove" title="{{__('common.remove')}} {{__('invoices.invoice')}}"
            href="{{route('invoices.confirm',[$account, $invoice])}}">
            <i class="fa fa-trash"></i>
          </a>
        </td>
        <td>
          <a class="btn btn-list" title="{{__('transactions.transaction')}}"
            href="{{route('invoices.transactions',[$account, $invoice->getId()])}}">
            <i class="fa fa-list"></i>
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>