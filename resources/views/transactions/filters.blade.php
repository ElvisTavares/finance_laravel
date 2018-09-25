<div class="row line-bottom default-margin-bottom">
    <div class="{{ config('constants.classes.filter') }}">
        <h4>{{__('common.filter-by-date')}}</h4>
        {{ Form::open(['url' => $baseUrl, 'method'=>'GET']) }}
        <div class="container-fluid">
            <div class="row">
                {!! (new FormGroup(__('common.date-init'), new Field('date', 'date_init'), $errors, null, null, ['class'=>'form-group col-6']))->html() !!}
                {!! (new FormGroup(__('common.date-end'), new Field('date', 'date_end'), $errors, null, null, ['class'=>'form-group col-6']))->html() !!}
            </div>
            <div class="row">
                {!! (new FormGroup(__('common.description'), new Field('text', 'description', ['required' => false]), $errors, null, null, ['class'=>'form-group col-8']))->html() !!}
                @include('shared/submit', ['text' => __('common.search'), 'class'=>'col-4 fixed-bottom-inside', 'iconClass' => 'fa fa-search'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
    @if (isset($account) && $account->is_credit_card)
        <div class="{{ config('constants.classes.filter') }}">
            <h4>{{__('common.filter-by-invoice')}}</h4>
            {{ Form::open(['url' => $baseUrl, 'method'=>'GET']) }}
            <div class="container-fluid">
                <div class="row">
                    {!! (new FormGroup(__('transactions.invoice'), new Field('select', 'invoice_id', ['value' => isset($request->invoice_id) ? $request->invoice_id : null]), $errors, null, $account->filterListInvoices(), ['class'=>'col-12']))->html() !!}
                </div>
                <div class="row">
                    {!! (new FormGroup(__('common.description'), new Field('text', 'description', ['required' => false]), $errors, null, null, ['class'=>'form-group col-8']))->html() !!}
                    @include('shared/submit', ['text' => __('common.search'), 'class'=>'col-4 fixed-bottom-inside', 'iconClass' => 'fa fa-search'])
                </div>
            </div>
            {{ Form::close() }}
        </div>
    @endif
</div>