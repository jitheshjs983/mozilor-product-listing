<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

use Facades\App\Services\UserAgentUtilityService;

class UserRegisterValidator extends LaravelValidator
{

    protected $rules = [

        //Rule for web API
        'register-rule' => [
	        'first_name'    => 'required',
	        'last_name'     => 'required',
	        'email'         => 'required|email|unique:users,email',
	        'password'      => ['required', 'regex:((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,64})']
        ],
        'login-rule' => [
            'email'         => 'required|email',
	        'password'      => ['required', 'regex:((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,64})']
        ]
        
    ];
    
    protected $messages = [
        'email.unique'          => 'The email has already been taken'
    ];
}
