<table class="table table-bordered">
  <thead>
    <tr class="active">
      <th>{{__('common.id')}}</th>
      <th>{{__('common.description')}}</th>
      <th>{{__('invoices.date_init')}}</th>
      <th>{{__('invoices.date_end')}}</th>
      <th>{{__('invoices.debit_date')}}</th>
      <th colspan="3">{{__('common.actions')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($invoices as $invoice)
      <tr>
        <td>{{$invoice->id}}</td>
        <td>{{$invoice->description}}</td>
        <td>{{formatDate($invoice->date_init)}}</td>
        <td>{{formatDate($invoice->date_end)}}</td>
        <td>{{formatDate($invoice->debit_date)}}</td>
        <td>
          <a class="btn btn-info" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$invoice->id}}">
            <i class="fa fa-upload"/></i> {{__('common.import')}}
          </a>
        </td>
        <td>
           <a class="btn btn-warning" title="{{__('common.edit')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/edit">
            <i class="fa fa-edit"/></i> {{__('common.edit')}}
          </a>
        </td>
        <td>
          <a class="btn btn-danger" title="{{__('common.remove')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/confirm">
            <i class="fa fa-trash"/></i> {{__('common.remove')}}
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>