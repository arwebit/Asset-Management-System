<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './reportClass.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$reportObj = new report(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getRemarkData = json_decode(file_get_contents('php://input'));
foreach ($getRemarkData as $getRemarkDataVal) {
    $token = $getRemarkDataVal->session_token;
    $login_user = $getRemarkDataVal->session_user;
    $report_id = $getRemarkDataVal->report_id;
    $supervisor_remark = $getRemarkDataVal->remark;
    $report_status = $getRemarkDataVal->report_status;
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
if (empty($report_id)) {
    $report_idErr = "Required";
}
if (empty($supervisor_remark)) {
    $supervisor_remarkErr = "Required";
}
if (empty($report_status)) {
    $report_statusErr = "Required";
}


if (($tokenErr == "") && ($report_idErr == "") && ($supervisor_remarkErr == "")&& ($statusErr == "")) {
    $credential[] = array("SupervisorRemark" => $supervisor_remark, "ReportStatus"=>$report_status, "Slno" => $report_id);
    $remarkStatus = $reportObj->supRemark(json_encode($credential));

    if ($remarkStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully remarked");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to update");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "ReportErr" => $report_idErr, "RemarkErr" => $supervisor_remarkErr,
        "StatusErr"=>$report_statusErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

