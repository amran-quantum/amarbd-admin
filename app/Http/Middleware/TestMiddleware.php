<?php

namespace App\Http\Middleware;

use Closure;

class TestMiddleware
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
        $s = microtime(true);
        $s2 = microtime(true);
        $s3 = $s2-$s;
    
        return response([$s,$s2,$s3]);
        //return $next($request);
    }
    
}
        

    
