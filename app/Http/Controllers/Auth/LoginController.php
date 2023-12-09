<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\ErrorCode;
use Google\Protobuf\GPBEmpty;
use Grpc\ChannelCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Presales\PresalesLoginRequest;
use Presales\PresalesServiceClient;
use WebEDC\EDCServiceClient;
use WebEDC\LoginRequest as WebEDCLoginRequest;

class LoginController extends Controller
{
    function showSignInPage(Request $request)
    {
        // Reset all existing session

//        $request->session()->put('sessionEmail', '');
//        $request->session()->put('sessionId', '');
//        $request->session()->put('sessionReferralId', '');
//        $request->session()->put('sessionFullname', '');
//        $request->session()->put('sessionPhoneNumber', '');

        //$request->session()->flush();
        if (request()->session()->get('token') !== null and request()->session()->get('token') !== '') {
            return redirect('/dashboard');
        }

        return view('pages.auth.signin');
    }

    function doLogin(Request $request) {
        Log::debug('doLogin is called.');

        $grpcClient = new PresalesServiceClient(webClientGRPCAddress, ['credentials' => ChannelCredentials::createInsecure(),]);

        // Prepare the request

        $signinRequest = new PresalesLoginRequest();
        $signinRequest->setRemoteip($request->ip());
        $signinRequest->setWeburl($request->fullUrl());
        $signinRequest->setLangid('ID');
        $signinRequest->setEmail($request->input('emailx'));
        $signinRequest->setPassword($request->input('passwordx'));

        list($result, $status) = $grpcClient->DoPresalesLogin($signinRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Login grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Login respStatus: '.$respStatus);

            if ($respStatus == ErrorCode::ErrorSuccess) {
                // Call grpc success - set session data
                // dd($result);
                $request->session()->put('langid', $result->getResults()[0]->getLangid());
                $request->session()->put('userid', $result->getResults()[0]->getUserid());
                $request->session()->put('username', $result->getResults()[0]->getUsername());
                $request->session()->put('email', $request->input('emailx'));
                $request->session()->put('token', $result->getResults()[0]->getSignature());
                $request->session()->put('roleid', $result->getResults()[0]->getRoleid());
                $request->session()->put('role', $result->getResults()[0]->getRole());
                $request->session()->put('firstname', $result->getResults()[0]->getFirstname());
                $request->session()->put('lastname', $result->getResults()[0]->getLastname());
                return redirect('/dashboard');
            } else if ($respStatus == ErrorCode::ErrorBadAccount){
                // Remove sessionId from the session
                $request->session()->remove('token');

                return redirect()->back()->with('message', 'Failed to login. Invalid username.');
            } else if ($respStatus == ErrorCode::ErrorBadPassword){
                // Remove sessionId from the session
                $request->session()->remove('token');

                return redirect()->back()->with('message', 'Failed to login. Invalid password.');
            } else {
                $request->session()->remove('token');

                return redirect()->back()->with('message', $result->getDescription());
            }
        } else {
            // Failed to call grpc
            // Remove sessionId from the session
            $request->session()->remove('token');

            return redirect()->back()->with('message', 'Failed to login. Server not available. Please try again later');
        }
    }
}
