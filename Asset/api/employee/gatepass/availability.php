<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './gatepassGeneration.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$gatepassObj = new gatepass(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getGatepassData = json_decode(file_get_contents('php://input'));

foreach ($getGatepassData as $getGatepassDataVal) {
    $create_user = trim($getGatepassDataVal->session_user);
    $token = trim($getGatepassDataVal->session_token);
    $gatepass_id = trim($getGatepassDataVal->gatepass_id);
    $status_type = trim($getGatepassDataVal->status);
}
$today_date_time = curr_date_time();

if (empty($token)) {
    $tokenErr = "Required";
} else {
    $tokenCred[] = array("username" => $create_user, "tokenval" => $token);
    $retVal = $loginObj->getLoginToken(json_encode($tokenCred));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
    }
    if ($getCountRecord == 0) {
        $tokenErr = "Token problem.";
    }
}

if (empty($status_type)) {
    $status_typeErr = "Required";
} else {
    if ($status_type == "Accepted") {
        $status = 1;
    } else if ($status_type == "Rejected") {
        $status = 3;
    } else {
        $status_typeErr = "Invalid status type";
    }
}
if (($status_typeErr == "") && ($tokenErr == "")) {
    $credential[] = array("Createuser" => $create_user, "GatepassID" => $gatepass_id, "Status" => $status,
        "Current_date" => $today_date_time);
    $updateStatus = $gatepassObj->availGatepass(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully $status_type");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to change");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "StatusErr" => $status_typeErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

