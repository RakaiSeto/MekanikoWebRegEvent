<?php
use Grpc\ChannelCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use WebStockManagement\StockManagementServiceClient;


const webClientGRPCAddress = "172.31.5.139:36001";
const generalGRPCAddress = "172.31.5.139:40404";
const theKey = "_GodIsGoodYouAreAllBlessed123!@_";

function convertNilToEmptyString($data) {
    if (!isset($data) || $data == null) {
        return '';
    } else {
        return $data;
    }
}

function convertNilToZeroString($data) {
    if (!isset($data) || $data == null) {
        return '0';
    } else {
        return $data;
    }
}

function mime2ext($mime): bool|string
{
    $extensions = array(
        'image/jpeg' => 'jpeg',
        'text/xml' => 'xml'
    );

    return $extensions[$mime];
}

function formattednumberfloatvalue($val): float
{
    $val = str_replace(",",".",$val);
    $val = preg_replace('/\.(?=.*\.)/', '', $val);
    return floatval($val);
}

function replaceSpecialCharactersAndWhiteSpaces($theString): string
{
    return str_replace( array( '\'', '"', ',' , ';', '<', '>', '=', '*', '(', ')', '/', '?', '!'), '', $theString);
}

function encryptKrapoex($payload): string
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($payload, 'aes-256-cbc', theKey, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptKrapoex($garble): bool|string
{
    Log::debug('decrypt - garble: '.$garble);
    list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', theKey, 0, $iv);
}

function setSession(Request $request, string $sessionId, string $sessionEmail, string $sessionPhoneNumber, string $sessionReferralId, string $sessionFullName, bool $isVerifiedEmail, bool $isVerifiedPhone, bool $isAdmin, string $token): bool
{
    $sessionKey = 'SESS_'.$sessionId;
    $arrVal = array('email' => $sessionEmail, 'phoneNumber' => $sessionPhoneNumber, 'referralId' => $sessionReferralId, 'fullName' => $sessionFullName, 'isVerifiedEmail' => $isVerifiedEmail, 'isVerifiedPhone' => $isVerifiedPhone, 'isAdmin' => $isAdmin);
    $jsonVal = json_encode($arrVal);
    Log::debug('jsonVal: '.$jsonVal);

    // Encrypt it
    $sessionVal = encryptKrapoex($jsonVal);

    if (strlen($sessionVal) > 0) {
        $request->session()->put($sessionKey, $sessionVal);
        $request->session()->put('verifiedemail', $isVerifiedEmail);
        $request->session()->put('verifiedphone', $isVerifiedPhone);
        $request->session()->put('admin', $isAdmin);
        $request->session()->put('jwt', $token);
        Log::debug('Success set session - '.$sessionId);
        return true;
    } else {
        Log::debug('Failed to set session - '.$sessionId);
        return false;
    }
}

function getSession(Request $request): bool|string
{
    // Get encrypted sessionId
    $theEncSessionId = convertNilToEmptyString($request->session()->get('K_ID'));
    Log::debug('MW WebClientAuth - enc sessionId: '.$theEncSessionId);

    // Decrypt the sessionId
    $theSessionId = decryptKrapoex($theEncSessionId);
    Log::debug('MW WebClientAuth - sessionId: '.$theSessionId);

    $sessionKey = 'SESS_'.$theSessionId;
    $sessionVal = $request->session()->get($sessionKey);

    if (strlen($sessionVal) > 0) {
        // Decrypt sessionVal and return it
        return decryptKrapoex($sessionVal);
    } else {
        // Invalid session
        return false;
    }
}

function getSessionId(Request $request): string
{
    $theEncSessionId = convertNilToEmptyString($request->session()->get('K_ID'));
    Log::debug('MW WebClientAuth - enc sessionId: '.$theEncSessionId);

    // Decrypt the sessionId
    $theSessionId = decryptKrapoex($theEncSessionId);
    Log::debug('MW WebClientAuth - sessionId: '.$theSessionId);
    
    return $theSessionId;
}

function getSessionFullName(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $theFullName = $jsonSession->fullName;
        Log::debug('sessFullName: '.$theFullName);
        return $theFullName;
    } else {
        return false;
    }
}

function getSessionEmail(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $theEmail = $jsonSession->email;
        Log::debug('sessEmail: '.$theEmail);
        return $theEmail;
    } else {
        return false;
    }
}

function getSessionPhoneNumber(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $thePhoneNumber = $jsonSession->phoneNumber;
        Log::debug('sessPhoneNumber: '.$thePhoneNumber);
        return $thePhoneNumber;
    } else {
        return false;
    }
}

function getSessionVerifiedEmail(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $isVerified = $jsonSession->isVerifiedEmail;
        Log::debug('is verified email: '.$isVerified);
        return $isVerified;
    } else {
        return false;
    }
}

function getSessionVerifiedPhone(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $isVerified = $jsonSession->isVerifiedPhone;
        Log::debug('is verified phone: '.$isVerified);
        return $isVerified;
    } else {
        return false;
    }
}

function getSessionReferralId(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $referralId = $jsonSession->referralId;
        Log::debug('referral id: '.$referralId);
        return $referralId;
    } else {
        return false;
    }
}

function getSessionIsAdmin(Request $request) {
    $theSession = getSession($request);

    if ($theSession !== false && strlen($theSession) > 0) {
        Log::debug($theSession);

        $jsonSession = json_decode($theSession);
        //Log::debug('sessJson: '.$jsonSession);

        $isVerified = $jsonSession->isAdmin;
        Log::debug('is admin: '. $isVerified);
        return $isVerified;
    } else {
        return false;
    }
}
