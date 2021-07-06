<?php

namespace App\Http\Middleware;

use App\Http\JWT\MyJWT;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Lindelius\JWT\Exception\ExpiredJwtException;
class authApi
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
        $user = null;
        $jwt = MyJWT::create('HS256');
        $header = $request->header('Authorization', '');

        if (Str::startsWith($header, "Bearer ")) {
            $bearer = Str::substr($header, 7);
            try {
                $decodedJwt = MyJWT::decode($bearer);
                $result = $decodedJwt->verify(Config::get('remote.hash_key'));
                $user_id = $decodedJwt->user_id;
                $exp = $decodedJwt->exp;
                $carbonDate = Carbon::now();
                $loggedUser = User::where('id', $user_id)->first();
                if ($loggedUser && $carbonDate->lt( Carbon::parse($loggedUser->updated_at)->addHours(2) )) {
                    $user = $loggedUser;
                }
                $request->request->add([
                    'loggedUser' => $user,
                    'token' => $bearer
                ]);
            } catch(ExpiredJwtException $e) {
                $request->request->add([
                    'error' => $e->getMessage()
                ]);
            }
        }
        return $next($request);
    }
}
