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
}
if (empty($gatepass_id)) {
   $gatepass_idErr = "Required";
}
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

if (($gatepass_idErr == "") && ($tokenErr == "")) {
    $credential[] = array("GatepassID" => $gatepass_id);
    $deleteStatus = $gatepassObj->deleteGatepass(json_encode($credential));

    if ($deleteStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully deleted");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to delete");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "GatepassIDErr" => $gatepass_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

