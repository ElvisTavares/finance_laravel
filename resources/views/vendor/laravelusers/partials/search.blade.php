<div class="row line-bottom default-margin-bottom">
    <div class="{{ config('constants.classes.filter') }}">
        <h4>{{__('common.filter')}}</h4>
        {{ Form::open(['url' => '/users', 'method'=>'GET']) }}
        <div class="container-fluid">
            <div class="row">
                <div class="form-group col-8">
                    {{ Form::label('name', __('users.name')) }}
                    {{ Form::text('name', old('name'), ['class'=>'form-control']) }}
                </div>
                @include('shared/submit', ['text' => __('common.search'), 'class'=>'col-4 fixed-bottom-inside', 'iconClass' => 'fa fa-search'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>