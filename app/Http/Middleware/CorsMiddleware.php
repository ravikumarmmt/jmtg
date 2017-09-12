<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware 
{
   // ALLOW OPTIONS METHOD
    protected $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept, X-Requested-With, Origin',
        'Access-Control-Allow-Credentials' => 'true'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $this->setCorsHeaders($response);
    }

    /**
     * @param $response
     * @return mixed
     */
    public function setCorsHeaders($response)
{
    foreach ($this->headers as $key => $value) {
        $response->header($key, $value);
    }

    return $response;
}
}
