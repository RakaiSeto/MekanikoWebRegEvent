<?php

namespace App\Helpers;


class ErrorCode {
    const ErrorServer = "900";
	const ErrorBadAccount = "106";
	const ErrorBadPassword = "107";
	const ErrorSuccess = "000";
	const ErrorUnverifiedAccount = "101";
	const ErrorWebInvalidSession = "704";
	const ErrorAccountAlreadyExists = "102";
	const ErrorInvalidParameter = "902";
	const ErrorSendEmail = "903";
	const ErrorNoOTP = "904";
	const ErrorBadOTP = "905";
}

?>