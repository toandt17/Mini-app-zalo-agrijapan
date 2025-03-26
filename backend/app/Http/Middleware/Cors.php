<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle OPTIONS request specially
        if ($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            $response = $next($request);
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Metho ds', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Co ntrol-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
         // $response->headers->set('Access-Control-Al        low-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
        $response->headers->set('Access-Control-Max-Age', '86400'); // 24 hours

        return $response;
    }
}
