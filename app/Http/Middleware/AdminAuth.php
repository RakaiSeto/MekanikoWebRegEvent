<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug('MW WebClientAuthADMIN.');

        // Get encrypted sessionId
        $theEncSessionId = convertNilToEmptyString($request->session()->get('K_ID'));
        Log::debug('MW WebClientAuthADMIN - enc sessionId: '.$theEncSessionId);

        if ($theEncSessionId != '') {
            // Decrypt the sessionId
            $theSessionId = decryptKrapoex($theEncSessionId);
            Log::debug('MW WebClientAuthADMIN - sessionId: '.$theSessionId);
    
            // Get the session data
            $sessionData = getSession($request);
            $isadmin = getSessionIsAdmin($request);

            Log::debug('MW WebClientAuthADMIN: '.$sessionData);
    
            if ($sessionData && strlen($sessionData) > 0 && $isadmin == 1) {
                // Session contain data
                // - Check detail session data
    
                return $next($request);
            } else {
                // Session not contain data
                Log::debug('MW WebClientAuthADMIN - Invalid session.');
                return redirect()->back();
            }
        } else {
            $request->session()->flush();
            Auth::logout();
            return redirect('/signout');
        }

    }
}
