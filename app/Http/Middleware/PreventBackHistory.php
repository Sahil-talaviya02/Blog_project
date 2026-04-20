<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reponse  = $next($request);
        $reponse->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $reponse->headers->set('Pragma', 'no-cache');
        $reponse->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        return $reponse;
    }
}
