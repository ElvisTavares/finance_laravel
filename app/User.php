<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use jeremykenedy\LaravelRoles\Models\Role;

class User extends ApplicationModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, HasRoleAndPermission;

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
     * @return HasMany
     */
    public function accounts() : HasMany
    {
        return $this->hasMany('App\Account');
    }

    /**
     * Get user's accounts
     *
     * @return HasMany
     */
    public function debitAccounts() : HasMany
    {
        return $this->accounts()->where('is_credit_card', false);
    }

    /**
     * Get user's accounts
     *
     * @return HasMany
     */
    public function creditAccounts() : HasMany
    {
        return $this->accounts()->where('is_credit_card', true);
    }

    /**
     * Get user's config
     *
     * @return HasMany
     */
    public function configs() : HasMany
    {
        return $this->hasMany('App\UserConfig');
    }

    /**
     * Get user's categories
     *
     * @return HasMany
     */
    public function categories() : HasMany
    {
        return $this->hasMany('App\Category');
    }

    public function avgTransactionsPositive(){
        return $this->avgTransactionsCalc(Transaction::ofUser($this)->positive());
    }

    public function avgTransactionsNegative(){
        return $this->avgTransactionsCalc(Transaction::ofUser($this)->negative());
    }

    private function avgTransactionsCalc($builder){
        $division = $builder->count();
        if ($division == 0) $division = 1;
        return $builder->sum('value') / $division;
    }

    public function avgTransactions()
    {
        return $this->avgTransactionsPositive() + $this->avgTransactionsNegative();
    }
}
