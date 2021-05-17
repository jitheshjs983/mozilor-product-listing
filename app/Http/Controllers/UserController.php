<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Dingo;
use App\Interfaces\JwtInterface;
use App\Validators\UserRegisterValidator;
use Prettus\Validator\Exceptions\ValidatorException;

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
        dd($request->all());
    }
    public function register(Request $request)
    {
        try
        {
            $data = $request->all();
            $validation_rule = 'register-rule';
            app(UserRegisterValidator::class)->with( $data )->passesOrFail($validation_rule);  // Pass validation rule depending up on the API

            $user = Sentinel::findByCredentials($data);
            if(!$user)
            {
                $user = Sentinel::register($data);
                $token = app(JwtInterface::class)->generate_token($user);
                if(isset($token))
                {
                    dd($token);
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

}
