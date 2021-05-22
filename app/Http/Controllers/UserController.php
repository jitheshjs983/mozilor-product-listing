<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLoginToken;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Dingo;
use App\Interfaces\JwtInterface;
use App\Implementations\JwtImplementation;
use App\Validators\UserRegisterValidator;
use Prettus\Validator\Exceptions\ValidatorException;
use JWTAuth;
use JWTFactory;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function login(Request $request)
    {
        try
        {
            $data = $request->all();
            $validation_rule = 'login-rule';
            app(UserRegisterValidator::class)->with( $data )->passesOrFail($validation_rule);
            $user = Sentinel::stateless($data);
            if($user)
            {
                $token = $this->generate_token($user);
                if(isset($token))
                {
                    $this->token_exist_check($token,$user->users_id);
                    return response()->json(['user' => $user,'token' => $token['token']]);
                }
            }
            else
            {
                abort(412,'Invalid Credentials');
            }
        }
        catch (ValidatorException $e) {
            throw new Dingo\Api\Exception\StoreResourceFailedException('Unable to login user ', $e->getMessageBag());
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
    public function register(Request $request)
    {
        try
        {
            $data = $request->all();
            $validation_rule = 'register-rule';
            app(UserRegisterValidator::class)->with( $data )->passesOrFail($validation_rule);

            $user = Sentinel::findByCredentials($data);
            if(!$user)
            {
                $user = Sentinel::register($data);
                $token = $this->generate_token($user);
                if(isset($token))
                {
                    return response()->json(['user' => $user,'token' => $token['token']]);
                }
            }
            else
            {
                abort(412,'Account already exists with email '.$data['email']);
            }
        }
        catch (ValidatorException $e) {
            throw new Dingo\Api\Exception\StoreResourceFailedException('Unable to register user ', $e->getMessageBag());
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
    public function generate_token($auth)
    {
        try {
            // // Create a token for the user
            $token = JWTAuth::fromUser($auth);
            $claims = JWTAuth::getJWTProvider()->decode($token);
        } 
        catch (\Exception $e) {
            throw $e;
        }
        return  [
            'token' => $token
        ];
    }
    public function token_exist_check($token,$users_id)
    {
        try
        {
            $token_exist = app(UserLoginToken::class)->where('users_id',$users_id)->first();
            if(isset($token_exist))
            {
                try
                {
                    JWTAuth::invalidate(JWTAuth::setToken(($token_exist->token)));
                }
                catch(\Exception $e)
                {
                    throw $e;
                }
            }
            $data_for_create = [
                'users_id'   => $users_id,
                'token'      => $token['token']
            ];
            app(UserLoginToken::class)->UpdateorCreate(['users_id' => $users_id],$data_for_create);
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}
