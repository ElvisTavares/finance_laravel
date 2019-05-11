<?php

namespace App;

use Auth;
use Illuminate\Http\Request;

class Transaction extends ApplicationModel
{

    protected $fillable = [
        'description', 'value', 'date', 'paid', 'account_id', 'invoice_id', 'account_id_transfer'
    ];

    /**
     * Get account of transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Account', 'account_id');
    }

    /**
     * Get account which was transferred account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountTransfer()
    {
        return $this->belongsTo('App\Account', 'account_id_transfer');
    }

    /**
     * Get invoice from transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id');
    }

    /**
     * Get categories transactions from transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function categories()
    {
        return $this->hasMany('App\CategoryTransaction', 'transaction_id');
    }

    /**
     * Method to update categories of transaction
     *
     * @param array $categories
     * @return void
     */
    public function updateCategories($categories = [])
    {
        foreach (array_map('strtoupper', $categories) as $description) {
            $category = Category::firstOrCreate([
                'user_id' => $this->account->user_id,
                'description' => $description
            ]);
            CategoryTransaction::firstOrCreate([
                'category_id' => $category->id,
                'transaction_id' => $this->id
            ]);
        }
    }

    /**
     * Method to update transaction by request
     *
     * @param Request $request
     * @return void
     */
    public function updateByRequest(Request $request)
    {
        $this->date = $request->date;
        $this->description = $request->description;
        $this->value = $request->value * (isset($request->is_credit) && $request->is_credit ? -1 : 1);
        $this->paid = isset($request->paid) ? $request->paid : false;
        if (isset($request->is_transfer) && $request->is_transfer)
            $this->accountTransfer()->associate($request->account_transfer);
        if (isset($request->invoice_id)) {
            $this->invoice_id = $request->invoice_id;
        }
        $this->save();
        $this->updateCategories(explode(',', $request->categories));
    }
    /**
     * Scope a query to only include transactions which positive values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositive($query)
    {
        return $query->where('value', '>', 0);
    }

    /**
     * Scope a query to only include transactions which negative values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNegative($query)
    {
        return $query->where('value', '<', 0);
    }

    /**
     * Scope a query to only include transactions which negative values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query, $paid)
    {
        return $query->where('paid', $paid);
    }

    /**
     * Scope a query to only include transactions which negative values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateMinus($query, $date)
    {
        return $query->where('date', '<=', $date);
    }

    /**
     * Scope a query to only include transactions of user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, User $user)
    {
        return $query->whereIn('account_id', $user->mapped('accounts', 'id'));
    }
}
