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
    $project_id = trim($getManualDataVal->project_id);
    $manual_title = trim($getManualDataVal->manual_title);
    $manual_descr = trim($getManualDataVal->manual_descr);
}

$id = date("YmdHis", strtotime(curr_date_time()));
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

if (empty($project_id)) {
    $project_idErr = "Required";
}

if (empty($manual_title)) {
    $manual_titleErr = "Required";
}

if (empty($manual_descr)) {
    $manual_descrErr = "Required";
}

if (($project_idErr == "") && ($manual_titleErr == "") && ($manual_descrErr == "") && ($tokenErr == "")) {
    $credential[] = array("ProjectID" => $project_id, "ManualTitle" => $manual_title, "ManualDescr" => $manual_descr,
        "Createuser" => $create_user, "Slno" => $id, "Current_date" => $today_date_time);
    $insertStatus = $manualObj->createManual(json_encode($credential));

    if ($insertStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully created manual");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to insert");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectErr" => $project_idErr, "ManualTitleErr" => $manual_titleErr,
        "ManualDescrErr"=>$manual_descrErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

