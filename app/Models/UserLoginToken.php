<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginToken extends Model
{
    use HasFactory;
    protected $table = 'user_login_tokens';
    protected $primaryKey = 'user_login_tokens_id';
	public $timestamps = true;

    protected $fillable = [
        'users_id',
        'token'
    ];
}
