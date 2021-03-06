<?php

namespace App\Models;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends \Cartalyst\Sentinel\Users\EloquentUser implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract,JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $table = 'users';
    protected $primaryKey = 'users_id';
    protected $persistableKey = 'users_id';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'permissions'
    ];
    protected $hidden = [
        'password',
    ];
    public  function  getJWTIdentifier() {
        return  $this->getKey();
    }
    public function getJWTCustomClaims()    
    {
        return [
        ];
    }
}
