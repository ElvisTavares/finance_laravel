<?php

namespace App;

class SysConfig extends ApplicationModel
{
    protected $table = 'configs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];
}