@foreach($invoices as $invoice)
  <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{$invoice->description}}</h5>
        <div class="container">
          <div class="row">
            <div class="col-md-6"><b>{{__('invoices.date_init')}}:</b></div>
            <div class="col-md-6">{{formatDate($invoice->date_init)}}</div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6"><b>{{__('invoices.date_end')}}:</b></div>
            <div class="col-md-6">{{formatDate($invoice->date_end)}}</div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6"><b>{{__('invoices.debit_date')}}:</b></div>
            <div class="col-md-6">{{formatDate($invoice->debit_date)}}</div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <a class="btn btn-info" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$invoice->id}}">
          <i class="fa fa-upload"/></i> {{__('common.import')}}
        </a>
         <a class="btn btn-warning" title="{{__('common.edit')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/edit">
          <i class="fa fa-edit"/></i> {{__('common.edit')}}
        </a>
        <a class="btn btn-danger" title="{{__('common.remove')}} {{__('invoices.invoice')}}" href="/account/{{$account->id}}/invoice/{{$invoice->id}}/confirm">
          <i class="fa fa-trash"/></i> {{__('common.remove')}}
        </a>
      </div>
    </div>
  </div>
@endforeach