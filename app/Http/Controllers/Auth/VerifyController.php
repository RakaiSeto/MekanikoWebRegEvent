<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Grpc\ChannelCredentials;
use Illuminate\Support\Facades\Log;
use WebClientAuth\ChangePhoneNumberRequest;
use WebClientAuth\SendActivationOTPPhoneRequest;
use WebClientAuth\SendActivationOTPRequest;
use WebClientAuth\VerifyOTPRequest;
use WebClientAuth\VerifyPhoneNumberRequest;
use WebClientAuth\WebClientAuthServiceClient;

class VerifyController extends Controller
{
    function SendVerifyCode(Request $request) {
        $theToken = $request->session()->get('jwt');

        $client = new WebClientAuthServiceClient(webClientGRPCAddress, [
            'credentials' => ChannelCredentials::createInsecure(), 
        ]);

        $grpcRequest = new SendActivationOTPRequest();
        $grpcRequest->setJwt($theToken);

        list($result, $status) = $client->DoSendActivationOTP($grpcRequest)->wait();
        $grpcHitStatus = $status->code;
        Log::debug("Login grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Login respStatus: '.$respStatus);

            if ($respStatus == ErrorCode::ErrorServer) {
                http_response_code(500);
            } else if ($respStatus == ErrorCode::ErrorSuccess) {
                $request->session()->put('jwt', $result->getJwt());
                return http_response_code(200);
            } else if ($respStatus == ErrorCode::ErrorWebInvalidSession){
                // Remove sessionId from the session
                return http_response_code(400);
            } else {
                return http_response_code(500);
            }
        } else {
            // Failed to call grpc
            // Remove sessionId from the session
            http_response_code(503);
        }
    }

    function SendVerifyCodePhone(Request $request) {
        $theToken = $request->session()->get('jwt');

        $client = new WebClientAuthServiceClient(webClientGRPCAddress, [
            'credentials' => ChannelCredentials::createInsecure(), 
        ]);

        $grpcRequest = new SendActivationOTPPhoneRequest();
        $grpcRequest->setJwt($theToken);

        list($result, $status) = $client->DoSendActivationOTPPhone($grpcRequest)->wait();
        $grpcHitStatus = $status->code;
        Log::debug("Send OTP Phone grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Send OTP Phone respStatus: '.$respStatus);

            if ($respStatus == ErrorCode::ErrorServer) {
                http_response_code(500);
            } else if ($respStatus == ErrorCode::ErrorSuccess) {
                $request->session()->put('jwt', $result->getJwt());
                return http_response_code(200);
            } else if ($respStatus == ErrorCode::ErrorWebInvalidSession){
                // Remove sessionId from the session
                return http_response_code(400);
            } else {
                return http_response_code(500);
            }
        } else {
            // Failed to call grpc
            // Remove sessionId from the session
            http_response_code(503);
        }
    }

    function DoVerify(Request $request) {
        $newArray = explode("=", $request->getContent());
        $theToken = $request->session()->get('jwt');

        $client = new WebClientAuthServiceClient(webClientGRPCAddress, [
            'credentials' => ChannelCredentials::createInsecure(), 
        ]);

        $grpcRequest = new VerifyOTPRequest();
        $grpcRequest->setJwt($theToken);
        $grpcRequest->setOtp($newArray[1]);

        list($result, $status) = $client->DoVerifyOTP($grpcRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Verify OTP grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Verify OTP respStatus: '.$respStatus);
            if ($respStatus == ErrorCode::ErrorSuccess) {
                $request->session()->put('jwt', $result->getJwt());
                $request->session()->put('verified', true);

                $sessionId = getSessionid($request);
                $sessionEmail = getSessionEmail($request);
                $sessionPhoneNumber = getSessionPhoneNumber($request);
                $sessionReferralId = getSessionReferralId($request);
                $sessionFulLName = getSessionFulLName($request);
                $isAdmin = getSessionIsAdmin($request);
                $verifiedphone = getSessionVerifiedPhone($request);

                $isSetOK = setSession($request, $sessionId, $sessionEmail, $sessionPhoneNumber, $sessionReferralId, $sessionFulLName, true, $verifiedphone, $isAdmin, $result->getToken());

                if ($isSetOK) {
                    http_response_code(200);
                } else {
                    http_response_code(500);
                }
            } else if ($respStatus == ErrorCode::ErrorWebInvalidSession){
                // Remove sessionId from the session
                return http_response_code(400);
            } else if ($respStatus == ErrorCode::ErrorBadOTP){
                return http_response_code(402);
            } else {
                return http_response_code(500);
            }
        } else {
            return http_response_code(503);
        }
    }

    function DoVerifyPhone(Request $request) {
        $newArray = explode("=", $request->getContent());
        $theToken = $request->session()->get('jwt');

        $client = new WebClientAuthServiceClient(webClientGRPCAddress, [
            'credentials' => ChannelCredentials::createInsecure(), 
        ]);

        $grpcRequest = new VerifyPhoneNumberRequest();
        $grpcRequest->setJwt($theToken);
        $grpcRequest->setOtp($newArray[1]);

        list($result, $status) = $client->DoVerifyPhoneNumber($grpcRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Verify OTP Phone grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Verify OTP Phone respStatus: '.$respStatus);
            if ($respStatus == ErrorCode::ErrorSuccess) {
                $request->session()->put('jwt', $result->getJwt());
                $request->session()->put('verified', true);

                $sessionId = getSessionid($request);
                $sessionEmail = getSessionEmail($request);
                $sessionPhoneNumber = getSessionPhoneNumber($request);
                $sessionReferralId = getSessionReferralId($request);
                $sessionFulLName = getSessionFulLName($request);
                $isAdmin = getSessionIsAdmin($request);
                $verifiedemail = getSessionVerifiedEmail($request);

                $isSetOK = setSession($request, $sessionId, $sessionEmail, $sessionPhoneNumber, $sessionReferralId, $sessionFulLName, $verifiedemail, true, $isAdmin, $result->getJwt());

                if ($isSetOK) {
                    http_response_code(200);
                } else {
                    http_response_code(500);
                }
            } else if ($respStatus == ErrorCode::ErrorWebInvalidSession){
                // Remove sessionId from the session
                return http_response_code(400);
            } else if ($respStatus == ErrorCode::ErrorBadOTP){
                return http_response_code(402);
            } else {
                return http_response_code(500);
            }
        } else {
            return http_response_code(503);
        }
    }

    function DoChangePhone(Request $request) {
        $theToken = $request->session()->get('jwt');

        $client = new WebClientAuthServiceClient(webClientGRPCAddress, [
            'credentials' => ChannelCredentials::createInsecure(), 
        ]);

        $grpcRequest = new ChangePhoneNumberRequest();
        $grpcRequest->setJwt($theToken);
        $grpcRequest->setPhonenumber(convertNilToEmptyString($request->input('phonex')));
        $arrPhone = explode('|', $request->input('phonex'));

        list($result, $status) = $client->DoChangePhoneNumber($grpcRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Change Phone Number grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Change Phone Number respStatus: '.$respStatus);
            if ($respStatus == ErrorCode::ErrorSuccess) {
                $request->session()->put('jwt', $result->getJwt());
                $request->session()->put('verified', true);

                $sessionId = getSessionid($request);
                $sessionEmail = getSessionEmail($request);
                $sessionReferralId = getSessionReferralId($request);
                $sessionFulLName = getSessionFulLName($request);
                $isAdmin = getSessionIsAdmin($request);
                $verifiedemail = getSessionVerifiedEmail($request);
                $verifiedphone = getSessionVerifiedPhone($request);

                $isSetOK = setSession($request, $sessionId, $sessionEmail, $arrPhone[1], $sessionReferralId, $sessionFulLName, $verifiedemail, false, $isAdmin, $result->getJwt());
                if ($isSetOK) {
                    return redirect('/dashboard');
                } else {
                    return redirect()->back()->with('message', 'failed input phone number, website error');
                }
            } else if ($respStatus == ErrorCode::ErrorWebInvalidSession){
                // Remove sessionId from the session
                return redirect('/signin')->with('message', 'Session invalid.');
            } else {
                return redirect()->back()->with('message', 'failed input phone number, server error');
            }
        } else {
            return redirect()->back()->with('message', 'failed input phone number, server not available');
        }
    }
}
