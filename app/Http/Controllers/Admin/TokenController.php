<?php 

namespace App\Http\Controllers\Admin;

use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Grpc\ChannelCredentials;
use WebToken\GetAllTokenRequest;
use WebToken\InputTokenRequest;
use WebToken\TokenServiceClient as WebTokenTokenServiceClient;

class TokenController extends Controller
{
    function ShowPage(Request $request) {
        Log::debug('GetAllToken is called.');
        $isadmin = getSessionIsAdmin($request);
        if ($isadmin == true) {
            $grpcClient = new WebTokenTokenServiceClient(webClientGRPCAddress, ['credentials' => ChannelCredentials::createInsecure(),]);
    
            $signinRequest = new GetAllTokenRequest();
    
            list($result, $status) = $grpcClient->DoGetAllToken($signinRequest)->wait();
            $grpcHitStatus = $status->code;
            Log::debug("Get All Token grpcHitStatus: ".$grpcHitStatus);
    
            if ($grpcHitStatus === 0) {
                $respStatus = $result->getStatus();
                Log::debug('Get All Token respStatus: '.$respStatus);
                if ($respStatus == ErrorCode::ErrorSuccess) {
                    $sessionFullName = getSessionFullName($request);
                    $results = $result->getResults();
                    return view('pages.admin.token')->with('token', $results)->with('sessionFullName', $sessionFullName);
                } else {
                    return redirect()->back()->with('message', 'Cannot go to admin');
                }
            }
        } else {
            return redirect()->back()->with('message','Not an admin');
        }
    }

    function inputToken(Request $request) {
        $newArray = explode("&", $request->getContent());
        $payloadArray = [];
        foreach ($newArray as $theString) {
            array_push($payloadArray, explode("=", $theString));
        }

        $code = $payloadArray[0][1];
        $name = $payloadArray[1][1];
        $desc = $payloadArray[2][1];
        $logo = $payloadArray[3][1];

        error_log($request->getContent());

        $grpcClient = new WebTokenTokenServiceClient(webClientGRPCAddress, ['credentials' => ChannelCredentials::createInsecure(),]);

        $grpcRequest = new InputTokenRequest();
        $grpcRequest->setTokenid($code);
        $grpcRequest->setTokenname($name);
        $grpcRequest->setTokendescription($desc);
        $grpcRequest->setTokenlogo($logo);
        
        list($result, $status) = $grpcClient->DoInputToken($grpcRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Input Token grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $result->getStatus();
            Log::debug('Input Token respStatus: '.$respStatus);
            if ($respStatus == ErrorCode::ErrorSuccess) {
                $request->session()->put('jwt', $result->getJwt());
                http_response_code(200);
            } else if ($respStatus == ErrorCode::ErrorWebInvalidSession){
                // Remove sessionId from the session
                return http_response_code(400);
            }
        }
    }
}
?>