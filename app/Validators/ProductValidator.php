<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

use Facades\App\Services\UserAgentUtilityService;

class ProductValidator extends LaravelValidator
{

    protected $rules = [

        'create-rule' => [
	        'product_name'      => 'required|regex:/^[a-zA-Z0-9 @#$\'&.]+$/u',
	        'price'             => 'required|regex:/^\d+(\.\d{1,2})?$/',
	        'sku_number'        => 'required|alpha_num',
	        'description'       => 'required|regex:/^[a-zA-Z0-9 @#$\'&.]+$/u'
        ]        
    ];
}
