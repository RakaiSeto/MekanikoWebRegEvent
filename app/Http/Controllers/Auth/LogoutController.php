<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    function doLogOut(Request $request) {
        // Reset all existing session
        Auth::logout();

        $request->session()->remove('token');

        // $request->session()->flush();
        session_unset();

        return redirect('/');
    }
}
