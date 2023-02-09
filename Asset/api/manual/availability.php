<?php

include '../common/global_functions.php';
include '../config/header_config.php';
include '../config/DBconfig.php';
include './manualClass.php';
include '../common/loginMember.php';

$manualObj = new manual(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getManualData = json_decode(file_get_contents('php://input'));

foreach ($getManualData as $getManualDataVal) {
    $create_user = trim($getManualDataVal->session_user);
    $token = trim($getManualDataVal->session_token);
    $manual_id = trim($getManualDataVal->manual_id);
    $status_type = trim($getManualDataVal->status);
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

if (empty($manual_id)) {
  $manualErr = "Required";
}

if (empty($status_type)) {
    $status_typeErr = "Required";
} else {
    if ($status_type == "Active") {
        $status = 1;
    }else if ($status_type == "Inactive") {
        $status = 0;
    }else{
       $status_typeErr = "Invalid status type"; 
    }
}
if (($status_typeErr == "") && ($manualErr == "") && ($tokenErr == "")) {
    $credential[] = array("Createuser" => $create_user, "Manual_id" => $manual_id, "Status" => $status,
        "Current_date" => $today_date_time);
    $updateStatus = $manualObj->availManual(json_encode($credential));

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
    $dataErrs[] = array("TokenErr" => $tokenErr, "StatusErr" => $status_typeErr, "ManualErr" => $manualErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data']= $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

