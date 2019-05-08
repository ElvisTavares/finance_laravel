@foreach($invoices as $invoice)
  <div class="{{ config('constants.classes.card') }}">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{$invoice->description}}</h5>
        <div class="container">
          <div class="row">
            <div class="col-md-6"><b>{{__('invoices.date-init')}}:</b></div>
            <div class="col-md-6">{{formatDate($invoice->date_init)}}</div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6"><b>{{__('invoices.date-end')}}:</b></div>
            <div class="col-md-6">{{formatDate($invoice->date_end)}}</div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-6"><b>{{__('invoices.debit-date')}}:</b></div>
            <div class="col-md-6">{{formatDate($invoice->debit_date)}}</div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <a class="btn btn-import" title="{{__('common.import')}} {{__('accounts.account')}}" href="#" data-toggle="modal" data-target="#model_account_{{$invoice->id}}">
          <i class="fa fa-upload"></i> {{__('common.import')}}
        </a>
         <a class="btn btn-edit" title="{{__('common.edit')}} {{__('invoices.invoice')}}" href="{{url('/account/'.$account->id.'/invoice/'.$invoice->id.'/edit')}}">
          <i class="fa fa-edit"></i> {{__('common.edit')}}
        </a>
        <a class="btn btn-remove" title="{{__('common.remove')}} {{__('invoices.invoice')}}" href="{{url('/account/'.$account->id.'/invoice/'.$invoice->id.'/confirm')}}">
          <i class="fa fa-trash"></i> {{__('common.remove')}}
        </a>
        <a class="btn btn-list" title="{{__('transactions.transaction')}}" href="{{url('/account/'.$account->id.'/transactions?invoice_id='.$invoice->encryptedId())}}">
          <i class="fa fa-list"></i> {{__('transactions.title')}}
        </a>
      </div>
    </div>
  </div>
@endforeach