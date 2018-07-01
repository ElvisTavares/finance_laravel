<div class="form-group">
	<?php
		if (!isset($text)){
			$text = __('common.submit');
		}
		if (!isset($iconClass)){
			$iconClass = "fa fa-save";
		}
	?>
  {{ Form::button('<i class="'.$iconClass.'"></i> '.$text,['type'=>'submit', 'class'=>'btn btn-primary', 'style'=>'float:right;']) }}
</div>