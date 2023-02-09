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
    $token = trim($getGatepassDataVal->session_token);
    $login_user = trim($getGatepassDataVal->session_user);
    $gatepass_id = trim($getGatepassDataVal->gatepass_id);
    $gp_date = trim($getGatepassDataVal->gp_date);
}

if (empty($token)) {
    $tokenErr = "Required";
} else {
    $tokenCred[] = array("username" => $login_user, "tokenval" => $token);
    $retVal = $loginObj->getLoginToken(json_encode($tokenCred));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
    }
    if ($getCountRecord == 0) {
        $tokenErr = "Token problem.";
    }
}

if (empty($gatepass_id)) {
    $gatepass_idErr = "Required";
}
if (($gatepass_idErr == "") &&  ($tokenErr == "")) {
    $credential[] = array("GatepassID" => $gatepass_id, "GatepassDate" => $gp_date);
    $retVal = $gatepassObj->getSelectedGatepass(json_encode($credential));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
        $getDetails = $retVals['Data'];
    }

    if ($getCountRecord > 0) {
        $succretData[] = array("Records" => $getCountRecord, "Details" => $getDetails);
        $response['error'] = false;
        $response['message'] = "Successfully retrieved";
        $response['data'] = $succretData;
    } else {
        $dataErrs[] = array("UsernameErr" => "No record found", "Records" => "0");
        $response['error'] = true;
        $response['message'] = "Record not exist";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "GatepassErr" => $gatepass_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

