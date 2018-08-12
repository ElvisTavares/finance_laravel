<?php

class FormGroup
{
    private $labelText;
    private $field;
    private $errors;
    private $model;
    private $options;
    private $attributes;

    public function __construct($labelText, Field $field, $errors, $model = null, $options = null, $attributes = [])
    {
        $this->labelText = $labelText;
        $this->field = $field;
        $this->errors = $errors;
        $this->model = $model;
        $this->options = $options;
        $this->attributes = $attributes;
    }

    public function html()
    {
        $hasError = $this->errors->has($this->field->name);
        if ($hasError) {
            $errorsStrong = (new TagTemplate('strong', [], $this->errors->first($this->field->name)))->html();
            $errorsDiv = (new TagTemplate('div', ['class' => 'invalid-feedback'], $errorsStrong))->html();
        } else {
            $errorsDiv = '';
        }
        $label = (new TagTemplate('label', [], $this->labelText))->html();
        $value = old($this->field->name);
        if (!isset($value) && isset($this->model)) {
            if (isset($this->field->attributes['value'])) {
                $value = $this->field->attributes['value'];
            } else {
                $value = $this->model->{$this->field->name};
            }
        }
        if ($this->field->type == 'checkbox') {
            $field = (new Swicth($this->field->name, $this->model, $value ?: (isset($this->field->attributes['value']) ? $this->field->attributes['value']:false) ))->html();
        } elseif ($this->field->type == 'select') {
            $field = Form::select($this->field->name, $this->options, $value, ['id' => $this->field->name, 'class' => 'form-control']);
        } else {
            $attributes = [
                'id' => $this->field->name,
                'name' => $this->field->name,
                'type' => $this->field->type,
                'value' => $value,
                'class' => 'form-control' . ($hasError ? ' is-invalid' : ''),
            ];
            if (isset($this->field->attributes)) {
                foreach ($this->field->attributes as $key => $value) {
                    $attributes[$key] = $value;
                }
            }
            $field = (new TagTemplate('input', $attributes, $errorsDiv))->html();
        }
        $attributes = ['class' => 'form-group'];
        if (isset($this->attributes)) {
            foreach ($this->attributes as $key => $value) {
                $attributes[$key] = $value;
            }
        }
        return (new TagTemplate('div', $attributes, $label . $field))->html();
    }


}