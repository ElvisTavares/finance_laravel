<div class="modal fade" id="model_account_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="{{$id}}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{$id}}">{{__('common.import')}} {{$account->description}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('common.close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>{{__('common.upload_max_filesize')}}: </strong>{{ini_get('upload_max_filesize')}}
                {!! Form::open(['route' => ['accounts.import.ofx', $account->id]]) !!}
                    <div class="form-group">
                        {!! Form::label('ofx-file[]', __('common.ofx_files')) !!}
                        <input type="file" name="csv-file[]" class="form-control" multiple accept=".ofx"/>
                    </div>
                    <div class="form-group">
                        {!! Form::submit(__('common.import'), ['class' => 'btn btn-success']); !!}
                    </div>
                {!! Form::close() !!}
                {!! Form::open(['route' => ['accounts.import.csv', $account->id]]) !!}
                    <div class="form-group">
                        {!! Form::label('csv-file[]', __('common.csv_files')) !!}
                        <input type="file" name="csv-file[]" class="form-control" multiple accept=".csv"/>
                    </div>
                    <div class="form-group">
                        {!! Form::submit(__('common.import'), ['class' => 'btn btn-success']); !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>