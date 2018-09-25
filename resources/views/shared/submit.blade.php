<div class="form-group {{isset($class)?$class:''}}">
	<?php
		if (!isset($text)){
			$text = __('common.submit');
		}
		if (!isset($iconClass)){
			$iconClass = "fa fa-save";
		}
	?>
  {{ Form::button('<i class="'.$iconClass.'"></i> '.$text,[
  'type'=>'button',
  'class'=>'btn btn-submit',
  'style'=>'float:right;',
  'onclick'=>"$(this).closest('form').submit()"
  ]) }}
</div>