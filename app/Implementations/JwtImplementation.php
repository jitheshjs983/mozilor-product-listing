<?php

namespace App\Implementations;
use App\Interfaces\JwtInterface;
use JWTAuth;

class JwtImplementation implements JwtInterface
{

    public function generate_token($auth)
    {
        try {

            $customClaims = [
                'first_name' => $auth->first_name,
                'last_name' => $auth->last_name,
                'users_id' => $auth->users_id,
                'email' => $auth->email
            ];

           
            // Create a token for the user
            $token = JWTAuth::fromUser($auth, $customClaims);

            $claims = JWTAuth::getJWTProvider()->decode($token);


        } catch (\Exception $e) {
            // something went wrong whilst attempting to encode the token
           dd($e);
        }

        return [
                'token'=>$token,
                'claims'=>$claims
        ];
        
    }
}