<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './reportClass.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$reportObj = new report(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getReportData = json_decode(file_get_contents('php://input'));

foreach ($getReportData as $getReportDataVal) {
    $token = trim($getReportDataVal->session_token);
    $login_user = trim($getReportDataVal->session_user);
    $report_id = trim($getReportDataVal->report_id);
}
if (empty($report_id)) {
    $report_idErr = "Required";
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

if (($tokenErr == "") && ($report_idErr == "")) {
    $searchCred[] = array("Status" => $status, "ReportID"=>$report_id);
    $retVal = $reportObj->getSelectedReportDetails(json_encode($searchCred));
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
        $dataErrs[] = array("ErrorMessage" => "No record found", "Records" => "0");
        $response['error'] = true;
        $response['message'] = "Record not exist";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "ReportIDErr" => $report_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

