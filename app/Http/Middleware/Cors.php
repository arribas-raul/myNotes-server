<?php

namespace App\Http\Middleware;

use Closure;
class Cors
{
  public function handle($request, Closure $next)
  {
    return $next($request)
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Allow-Origin', 'http://localhost:4200')
        ->header('Access-Control-Allow-Methods', '*')
        ->header('Access-Control-Max-Age', '3600')
        ->header('Access-Control-Allow-Headers', 'X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept, Authorization'); 
  }
}
