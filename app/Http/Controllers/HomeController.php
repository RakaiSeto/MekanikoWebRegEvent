<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    function showPage(Request $request)
    {
        $theEncSessionId = convertNilToEmptyString($request->session()->get('K_ID'));

        if ($theEncSessionId != '') {
            $sessionFullName = getSessionFullName($request);
            $sessionEmail = getSessionEmail($request);
            $sessionPhoneNumber = getSessionPhoneNumber($request);
            $verifiedemail = getSessionVerifiedEmail($request);
            $verifiedphone = getSessionVerifiedPhone($request);
            if ($verifiedemail == false) {
                Log::debug("Verified email : " . $verifiedemail);
                return view('pages.auth.verifyemail')->with('sessionFullName', $sessionFullName)->with('sessionEmail', $sessionEmail)
                ->with('phoneNumber', $sessionPhoneNumber);
            } else if (strlen($sessionPhoneNumber) == 0) {
                Log::debug("Phone Number : null");
                return view('pages.auth.inputphone')->with('sessionFullName', $sessionFullName)->with('sessionEmail', $sessionEmail)
                ->with('phoneNumber', $sessionPhoneNumber);
            } else if ($verifiedphone == false) {
                Log::debug("Verified email : " . $verifiedemail);
                return view('pages.auth.verifyphone')->with('sessionFullName', $sessionFullName)->with('sessionEmail', $sessionEmail)
                    ->with('phoneNumber', $sessionPhoneNumber);
            } else {
                return view('pages.home');  
            }
        } else {
            return view('pages.home');  
        }
    }
}
