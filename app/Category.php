<?php

namespace App;

class Category extends ApplicationModel
{
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'user_id'
    ];

    /**
     * Get category user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get category's transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
      return $this->hasMany('App\CategoryTransaction','transaction_id');
    }
}
