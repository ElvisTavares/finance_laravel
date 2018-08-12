{{ Form::open(['url' => url($baseUrl. 'addCategories'.$query), 'method'=>'PUT', 'class'=>'row']) }}
<div class="col-9">
    <div class="form-group">
        <h4> {{ __('common.add-category') }} </h4>
        {{ Form::text('categories', old('categories', ''), ['class'=>'form-control', 'data-role'=>'tagsinput']) }}
    </div>
</div>
@include('shared.submit', ['text' => __('common.add'), 'class' => 'col-3 fixed-bottom-inside'])
{{ Form::close() }}
