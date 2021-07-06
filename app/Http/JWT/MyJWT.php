<?php

namespace App\Http\JWT;

use Lindelius\JWT\Algorithm\HMAC\HS256;
use Lindelius\JWT\JWT;

class MyJWT extends JWT
{
    use HS256;
}