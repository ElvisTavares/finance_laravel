@php
    $request = http_build_query(Request::except('invoice_id'))
@endphp
<div class="row line-bottom default-margin-bottom">
    <div class="{{ config('constants.classes.filter') }}">
        <h4>{{__('common.filter-by-date')}}</h4>
        {!! Form::open(['route' => (
                $invoice ?
                ['invoices.transactions', $account->id, $invoice->getId(), $request] :
                ['accounts.transactions', $account->id, $request]
            ), 'method'=>'GET']) !!}
            <div class="form-group">
                {!! Form::label('description', __('common.description')) !!}
                {!! Form::text('description', old('description', Request::input('description')), ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('date_init', __('common.date-init')) !!}
                {!! Form::date('date_init', old('date_init', Request::input('date-init')), ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('date_end', __('common.date-end')) !!}
                {!! Form::date('date_end', old('date_end', Request::input('date-init')), ['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
            </div>
        {!! Form::close() !!}
    </div>
    @if (isset($account) && $account->is_credit_card)
        <div class="{{ config('constants.classes.filter') }}">
            <h4>{{__('common.filter-by-invoice')}}</h4>
            {!! Form::open(['route' => ['accounts.transactions', $account->id, $request], 'method'=>'GET']) !!}
                <div class="form-group">
                    {!! Form::label('description', __('common.description')) !!}
                    {!! Form::text('description', old('description', Request::input('description')), ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    @php
                        $select = [];
                        foreach(array_filter($account->invoices) as $invoice)
                            $select[$invoice->getId()] = $invoice->description();
                    @endphp
                    {!! Form::label('invoice_id', __('common.invoice')) !!}
                    {!! Form::select('invoice_id', $select, old('invoice_id', $invoice->getId()), ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
                </div>
            {!! Form::close() !!}
        </div>
    @endif
    <div class="{{ config('constants.classes.filter') }}">
        <h4>{{__('common.add-category')}}</h4>
        {{ Form::open(['route' => (
            $invoice ?
            ['invoices.transactions.categories', $account->id, $invoice->getId(), $request]:
            ['accounts.transactions.categories', $account->id, $request]
        )]) }}
            <div class="form-group">
                {!! Form::label('categories', __('common.categories')) !!}
                {!! Form::text('categories', old('categories', Request::input('categories')), ['class'=>'form-control', 'data-role'=>'tagsinput']) !!}
            </div>
            <div class="form-group">
                {!! Form::submit(__('common.submit'), ['class' => 'btn btn-success']); !!}
            </div>
        {{ Form::close() }}
    </div>
</div>