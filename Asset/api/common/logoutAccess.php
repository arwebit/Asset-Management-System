<?php

include './global_functions.php';
include '../config/DBconfig.php';
include './loginMember.php';

$loginMemberObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getLoginData = json_decode(file_get_contents('php://input'));

foreach ($getLoginData as $getLoginDataVal) {
    $token = $getLoginDataVal->session_token;
    $username = $getLoginDataVal->session_user;
}

if (empty($token)) {
    $tokenErr = "Required";
} else {
    $tokenCred[] = array("username" => $username, "tokenval" => $token);
    $retVal = $loginMemberObj->getLoginToken(json_encode($tokenCred));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
    }
    if ($getCountRecord == 0) {
        $tokenErr = "Token problem.";
    }
}
if ($tokenErr == "") {
    $tokenCred[] = array("Username" => $username, "TokenVal" => "");
    $saveTokenStatus = $loginMemberObj->saveToken(json_encode($tokenCred));
    if ($saveTokenStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully logged out");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    }else{
        $dataErr[] = array("ErrorMsg" => "Failed logged out");
        $response['error'] = true;
        $response['message'] = "Failed";
        $response['data'] = $dataErr;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

