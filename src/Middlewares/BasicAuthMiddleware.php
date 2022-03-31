<?php

namespace Megaads\Apify\Middlewares;

use Closure;
use Megaads\Apify\Controllers\BaseController;
use Illuminate\Contracts\Auth\Factory as Auth;

class BasicAuthMiddleware extends BaseController
{

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        $config = config('apify');
        if (isset($config['auth']) && $config['auth'] == 'basic') {
            // Status flag:
            $loginSuccessful = false;
            // Check username and password:
            if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    
                $username = $_SERVER['PHP_AUTH_USER'];
                $password = $_SERVER['PHP_AUTH_PW'];
                if ($username == $config['basicAuthentication']['username'] && $password == $config['basicAuthentication']['password']){
                    $loginSuccessful = true;
                }
            }
            if ($loginSuccessful){
                return $next($request);
            }else{
                return response('Unauthorized.', 401,["WWW-Authenticate"=>"Basic realm='Coupon.io'"]);
            }
        } else {
            return $next($request);
        }
    }

}
