<?php

class Swicth
{
    private $fieldName;
    private $model;
    private $value;

    public function __construct($fieldName, $model, $value)
    {
        $this->fieldName = $fieldName;
        $this->model = $model;
        $this->value = $value;
    }

    public function html(){
        $checkbox = Form::checkbox($this->fieldName, 1, $this->value ? 'checked' : '', ['id'=>$this->fieldName]);
        $span = (new TagTemplate('span', ['class'=>'slider round']))->html();
        return (new TagTemplate('label', ['class'=>'switch'], $checkbox.$span))->html();
    }
}
