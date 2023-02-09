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
    $status = trim($getManualDataVal->status);
    $project_id = trim($getManualDataVal->project_id);
    $manual_title = trim($getManualDataVal->manual_title);
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
    }else {
        $data = $retVals['Data'];
        foreach ($data as $dataVals) {
            $create_under = $dataVals['create_under'];
        }
    }
}


if (empty($status)) {
    $status = "";
} else {
    if ($status == "Active") {
        $status = "Active";
    } else if ($status == "Inactive") {
        $status = "Inactive";
    } else {
        $statusErr = "Invalid status type";
    }
}

if (empty($project_id)) {
    $project_id = "";
}
if (empty($manual_title)) {
    $manual_title = "";
}

if (($tokenErr == "")&&($statusErr == "")) {
    $searchCred[] = array("CreateUnder" => $create_under, "ProjectID" => $project_id, "ManualTitle" => $manual_title, "Status" => $status);
    $retVal = $manualObj->getManualDetails(json_encode($searchCred));
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
  $dataErrs[] = array("StatusErr" => $statusErr, "TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

