<?php

namespace Towoju5\SmartMetaManager;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
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
        if (!Auth::check()) {
            $class = new SmartMetaManager();
            return $class->api_error_response("Unauthenticated", ["error" => "Please login to continue"], 401);
        }

        return $next($request);
    }
}
