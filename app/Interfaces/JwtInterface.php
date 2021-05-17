<?php
namespace App\Interfaces;


class JwtInterface
{
    public function generate_token($data);
}