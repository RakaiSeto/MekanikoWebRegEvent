<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebClientAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug('MW WebClientAuth.');

        // Get encrypted sessionId
        $theEncSessionId = convertNilToEmptyString($request->session()->get('token'));
        Log::debug('MW WebClientAuth - enc sessionId: '.$theEncSessionId);

        if ($theEncSessionId != '') {
                return $next($request);
        } else {
            // Session not contain data
            Log::debug('MW WebClientAuth - Invalid session.');
            $request->session()->flush();
            Auth::logout();
            return redirect('/signout');
        }
    }
}
