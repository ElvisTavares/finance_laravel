<?php

class Field {
    public $name;
    public $type;
    public $attributes;

    public function __construct($type, $name, $attributes = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->attributes = $attributes;
        if (!isset($this->attributes['required'])){
            $this->attributes['required'] = true;
        } elseif (!$this->attributes['required']) {
            unset($this->attributes['required']);
        }
    }
}