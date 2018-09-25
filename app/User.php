<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use jeremykenedy\LaravelRoles\Models\Role;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoleAndPermission;

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $users = User::get();
            if ($users->count() == 1) {
                $model->attachRole(Role::where('name', '=', 'admin')->first());
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'picture', 'is_root'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get user's accounts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('App\Account');
    }

    /**
     * get array of id accounts
     *
     * @return array
     */
    public function accoutsId()
    {
        return $this->accounts->map(function ($account) {
            return $account->id;
        });
    }

    /**
     * Get user's config
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function configs()
    {
        return $this->hasMany('App\UserConfig');
    }

    /**
     * Get user's categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    /**
     * Function to get list of user's accounts
     *
     * @return array
     */
    public function listAccounts()
    {
        $selectAccounts = [];
        $selectAccounts[-1] = __('common.select');
        foreach ($this->accounts()->get() as $account) {
            $selectAccounts[$account->id] = $account->id . "/" . $account->description;
        }
        return $selectAccounts;
    }

    /**
     * Function to get accounts aren't credit card
     *
     * @return array
     */
    public function listNonCreditCard()
    {
        $accounts = $this->accounts->where('is_credit_card', false);
        $selectAccounts = [null => __('common.none')];
        foreach ($accounts as $account) {
            $selectAccounts[$account->id] = $account->id . "/" . $account->description;
        }
        return $selectAccounts;
    }

    /**
     * @return \stdClass
     */
    public function avgTransactions()
    {
        $result = new \stdClass;
        $result->max = Transaction::ofUser($this)->positive();
        $result->maxDivision = count($result->max->get());
        if ($result->maxDivision == 0) {
            $result->maxDivision = 1;
        }
        $result->max = $result->max->sum('value') / $result->maxDivision;
        $result->min = Transaction::ofUser($this)->negative();
        $result->minDivision = count($result->min->get());
        if ($result->minDivision == 0) {
            $result->minDivision = 1;
        }
        $result->min = $result->min->sum('value') / $result->minDivision;
        $result->avg = $result->max + $result->min;
        return $result;
    }
}
