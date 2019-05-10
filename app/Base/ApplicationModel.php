<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationModel extends Model {

    public function mapped($method, $variable){
        return self::mappedArray($this->{$method}->toArray(), null, $variable);
    }

    public function mappedSubItem($method, $variable, $subitem){
        return self::mappedArray($this->{$method}->toArray(), $variable);
    }

    public static function mappedArray($array, $variable, $subitem = null){
        return array_map(function($item) use ($variable, $subitem){
            if ($subitem) return $item[$subitem][$variable];
            return $item[$variable];
        }, $array);
    }

    public function listed($method, $variable_value = 'id', $variable_description = 'description', $none = true){
        $items = [];
        if ($none) $items[-1] = __('common.select');
        foreach ($this->{$method} as $item) {
            $items[$item->{$variable_value}] = $item->{$variable_value} . " / " . $item->{$variable_description};
        }
        return $items;
    }
}