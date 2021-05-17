<?php

namespace App\Http\Middleware;

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
            } catch (TokenExpiredException $e) {
                abort(412,'token_expired');
            } catch (JWTException $e) {
                abort(412,'token_expired');
            }
    
            if (! $user) {
                abort(412,'user_not_found');
            }
    
            $this->events->fire('tymon.jwt.valid', $user);
    
            return $next($request);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}