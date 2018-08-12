<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'config_id', 'value'
    ];

    /**
     * Get user from user config
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get config from user config
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function config()
    {
        return $this->belongsTo('App\SysConfig');
    }

    /**
     * Function to create or get first UserConfig of accounts mode view
     *
     * @param integer $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function accountsModeView($userId)
    {
        $constant = config('constants.user_configs.mode_account_view');
        return UserConfig::modeView($userId, $constant);
    }

    /**
     * Function to create or get first UserConfig of invoices mode view
     *
     * @param integer $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function invoicesModeView($userId)
    {
        $constant = config('constants.user_configs.mode_invoice_view');
        return UserConfig::modeView($userId, $constant);
    }

    /**
     * Function to create or get first UserConfig of transactions mode view
     *
     * @param integer $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function transactionsModeView($userId)
    {
        $constant = config('constants.user_configs.mode_transaction_view');
        return UserConfig::modeView($userId, $constant);
    }

    /**
     * Function to create or get first UserConfig of users mode view
     *
     * @param integer $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function usersModeView($userId)
    {
        $constant = config('constants.user_configs.mode_user_view');
        return UserConfig::modeView($userId, $constant);
    }
    /**
     * Function to create or get first UserConfig of any mode view
     *
     * @param integer $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    private static function modeView($userId, $constant){
        $attributes = [
            'config_id' => $constant,
            'user_id' => $userId
        ];
        return UserConfig::firstOrCreate($attributes);
    }
}