<?php

namespace App\Http\Middleware;

use App\Models\UserLoginToken;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTTokenValidate extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try
        {
            if (! $token = $this->auth->setRequest($request)->getToken()) {
                abort(412,'token not provided');
            }
    
            try {
                $user = $this->auth->authenticate($token);
                $token_exist = app(UserLoginToken::class)->where('users_id',$user['users_id'])->latest()->first();
                if(isset($token_exist))
                {
                    if($token_exist->token != $token->get())
                    {
                        abort(412,'token is already expired..Please login again..');
                    }
                }
                else
                {
                    abort(412,'Please generate new token');
                }
            } catch (TokenExpiredException $e) {
                abort(412,'token_expired');
            } catch (JWTException $e) {
                abort(412,'token_expired');
            }
    
            if (! $user) {
                abort(412,'user_not_found');
            }
        
            return $next($request);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}