<div class="modal fade" id="model_account_{{$id}}" tabindex="-1" role="dialog" aria-labelledby="{{$id}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{$id}}">{{__('common.import')}} {{$account->description}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{__('common.close')}}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url("/account/".$id."/upload/ofx") }}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <h5>{{__('common.import')}} {{__('common.ofx')}}</h5>
                    <input type="file" name="ofx-file[]" multiple accept=".ofx"/>
                    @include('shared.submit', ['text' => __('common.import'), 'iconClass'=>'fa fa-upload'])
                </form>
                <form action="{{ url("/account/".$id."/upload/csv") }}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <h5>{{__('common.import')}} {{__('common.csv')}}</h5>
                    <input type="file" name="csv-file[]" multiple accept=".csv"/>
                    @include('shared.submit', ['text' => __('common.import'), 'iconClass'=>'fa fa-upload'])
                </form>
            </div>
        </div>
    </div>
</div>