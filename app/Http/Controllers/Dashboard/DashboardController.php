<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ErrorCode;
use App\Http\Controllers\Controller;
use General\GeneralCategoryRequest;
use General\GeneralServiceClient;
use General\GeneralSubCategoryRequest;
use Illuminate\Http\Request;
use Grpc\ChannelCredentials;
use Illuminate\Support\Facades\Log;
use Presales\PresalesServiceClient;
use Presales\PresalesViewRequest;
use WebEDC\EDCServiceClient;
use WebEDC\SeeAllTransactionRequest;
use Illuminate\Support\Facades\Auth;
use Presales\PresalesAddData;
use Presales\PresalesAddList;
use Presales\PresalesAddRequest;
use Presales\PresalesByIDViewRequest;

class DashboardController extends Controller
{
    function showPage(Request $request)
    {
        $grpcClient = new PresalesServiceClient(webClientGRPCAddress, ['credentials' => ChannelCredentials::createInsecure(),]);

        // Prepare the request

        $signinRequest = new PresalesViewRequest();
        $signinRequest->setSignature($request->session()->get('token'));
        $signinRequest->setRemoteip($request->ip());
        $signinRequest->setWeburl($request->fullUrl());
        $signinRequest->setLangid('ID');
        $signinRequest->setAdminid($request->session()->get('userid'));
        $signinRequest->setEmail($request->session()->get('email'));
        $signinRequest->setUserid($request->session()->get('userid'));

        list($resultall, $status) = $grpcClient->DoPresalesView($signinRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Principal List grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $resultall->getStatus();
            Log::debug('Principal List respStatus: '.$respStatus);

            if ($respStatus == ErrorCode::ErrorSuccess) {
                $indexarr = [1];
                $intarr = 1;
                $onesitem = [];
                $onesid = [];
                foreach ($resultall->getResults() as $key => $value) {
                    $signinRequest = new PresalesByIDViewRequest();
                    $signinRequest->setSignature($request->session()->get('token'));
                    $signinRequest->setRemoteip($request->ip());
                    $signinRequest->setWeburl($request->fullUrl());
                    $signinRequest->setLangid('ID');
                    $signinRequest->setAdminid($request->session()->get('userid'));
                    $signinRequest->setEmail($request->session()->get('email'));
                    $signinRequest->setUserid($request->session()->get('userid'));
                    $signinRequest->setPrincipalid($resultall->getResults()[$key]->getPrincipalid());

                    array_push($onesid, $resultall->getResults()[$key]->getPrincipalid());

                    list($result, $status) = $grpcClient->DoPresalesByIDView($signinRequest)->wait();

                    $grpcHitStatus = $status->code;
                    Log::debug("Principal List grpcHitStatus: ".$grpcHitStatus);

                    if ($grpcHitStatus === 0) {
                        $respStatus = $result->getStatus();
                        Log::debug('Principal List respStatus: '.$respStatus);

                        if ($respStatus == ErrorCode::ErrorSuccess) {
                            array_push($onesitem, $result->getResults());
                            $intarr++;
                            array_push($indexarr, $intarr);
                        } else {
                            Auth::logout();
                            // $request->session()->remove('token');

                            $request->session()->flush();
                            // session_unset();

                            return redirect('/')->with('message', 'Failed to get principal data. '. $result->getDescription());
                        }
                    } else {
                        // Failed to call grpc
                        // Remove sessionId from the session
                        $request->session()->remove('token');
            
                        return redirect('/')->with('message', 'Failed to Get Principal data. Server not available. Please try again later');
                    }
                }
                // dd($onesitem[0][0]);
                return view('pages.dashboard.dashboard')->with('results', $resultall->getResults())->with('index', $indexarr)->with('itemdetail', $onesitem)->with('onesid', $onesid);
            } else {
                // Reset all existing session
                Auth::logout();

                // $request->session()->remove('token');

                $request->session()->flush();
                // session_unset();

                return redirect('/')->with('message', 'Failed to get all principal data. '. $resultall->getDescription());
                // return redirect('/signout');
            }
        } else {
            // Failed to call grpc
            // Remove sessionId from the session
            $request->session()->remove('token');

            return redirect('/')->with('message', 'Failed to Get all Principal data. Server not available. Please try again later');
        }
    }

    function showInputPage(Request $request)
    {
        $grpcClient = new GeneralServiceClient(generalGRPCAddress, ['credentials' => ChannelCredentials::createInsecure(),]);

        $categoryList = [];
        $subcatList = [];
        // Prepare the request

        $signinRequest = new GeneralCategoryRequest();
        $signinRequest->setRemoteip($request->ip());
        $signinRequest->setWeburl($request->fullUrl());
        $signinRequest->setLangid('ID');

        list($resultall, $status) = $grpcClient->DoGetCategory($signinRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Category List grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {
            $respStatus = $resultall->getStatus();
            Log::debug('Category List respStatus: '.$respStatus);

            if ($respStatus == ErrorCode::ErrorSuccess) {
                foreach ($resultall->getResults() as $value) {
                    array_push($categoryList, $value);

                    $subcatWrapper = [];

                    $subcatRequest = new GeneralSubCategoryRequest();
                    $subcatRequest->setRemoteip($request->ip());
                    $subcatRequest->setWeburl($request->fullUrl());
                    $subcatRequest->setLangid('ID');
                    $subcatRequest->setCategoryid($value->getId());

                    list($resultsub, $status) = $grpcClient->DoGetSubCategory($subcatRequest)->wait();

                    $grpcHitStatus = $status->code;
                    Log::debug("Sub Category List grpcHitStatus: ".$grpcHitStatus);

                    if ($grpcHitStatus === 0) {
                        $respStatus = $resultsub->getStatus();
                        Log::debug('Sub Category List respStatus: '.$respStatus);

                        if ($respStatus == ErrorCode::ErrorSuccess) {
                            foreach ($resultsub->getResults() as $value2) {
                                array_push($subcatWrapper, $value2);
                            }
                        } else {
                            Auth::logout();

                            // $request->session()->remove('token');

                            $request->session()->flush();
                            // session_unset();

                            return redirect('/')->with('message', 'Failed to get sub cat data. '. $resultall->getDescription());
                        }
                    } else {
                        $request->session()->remove('token');

                        return redirect('/')->with('message', 'Failed to Get sub Category data. Server not available. Please try again later');
                    }

                    array_push($subcatList, $subcatWrapper);
                }

                return view('pages.dashboard.inputcompany')->with('cat', $categoryList)->with('subcat', $subcatList);
            } else {
                Auth::logout();

                // $request->session()->remove('token');

                $request->session()->flush();
                // session_unset();

                return redirect('/')->with('message', 'Failed to get all principal data. '. $resultall->getDescription());
            }
        } else {
            $request->session()->remove('token');

            return redirect('/')->with('message', 'Failed to Get all Category data. Server not available. Please try again later');
        }
    }

    function submitCompany(Request $request) {
        $newArray = explode("&", $request->getContent());
        $payloadArray = [];
        foreach ($newArray as $theString) {
            array_push($payloadArray, explode("=", $theString));
        }

        $grpcClient = new PresalesServiceClient(webClientGRPCAddress, ['credentials' => ChannelCredentials::createInsecure(),]);

        $signinRequest = new PresalesAddRequest();
        $signinRequest->setSignature($request->session()->get('token'));
        $signinRequest->setRemoteip($request->ip());
        $signinRequest->setWeburl($request->fullUrl());
        $signinRequest->setLangid('ID');
        $signinRequest->setAdminid($request->session()->get('userid'));
        $signinRequest->setEmail($request->session()->get('email'));
        $signinRequest->setUserid($request->session()->get('userid'));

        $grpcRequestList = new PresalesAddData();
        $grpcRequestList->setFirstname($payloadArray[0][1]);
        $grpcRequestList->setLastname($payloadArray[1][1]);
        $grpcRequestList->setCompany($payloadArray[2][1]);
        $grpcRequestList->setBrandname($payloadArray[3][1]);
        $grpcRequestList->setCategoryid($payloadArray[4][1]);
        $grpcRequestList->setSubcategoryid($payloadArray[5][1]);
        $grpcRequestList->setEmailaddress($payloadArray[6][1]);
        $grpcRequestList->setPhone($payloadArray[7][1]);
        $arrTemp = [];
        array_push($arrTemp, $grpcRequestList);
        $signinRequest->setData($arrTemp);

        list($resultall, $status) = $grpcClient->DoPresalesAdd($signinRequest)->wait();

        $grpcHitStatus = $status->code;
        Log::debug("Add Principal grpcHitStatus: ".$grpcHitStatus);

        if ($grpcHitStatus === 0) {

            $respStatus = $resultall->getStatus();
            Log::debug('Add Principal respStatus: '.$respStatus);

            if ($respStatus == ErrorCode::ErrorSuccess) {
                return redirect('/dashboard');
            } else {
                return redirect('/dashboard');
            }
        } else {
            // Failed to call grpc
            // Remove sessionId from the session
            $request->session()->remove('token');

            return redirect('/')->with('message', 'Failed to Add Principal data. Server not available. Please try again later');
        }
    }
}
