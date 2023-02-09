<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../projectClass.php';
include '../../common/loginMember.php';

$projectInfoObj = new projectInfo(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getProjectData = json_decode(file_get_contents('php://input'));

foreach ($getProjectData as $getProjectDataVal) {
    $create_user = trim($getProjectDataVal->session_user);
    $token = trim($getProjectDataVal->session_token);
    $project_id = trim($getProjectDataVal->project_id);
    $status= trim($getProjectDataVal->status);
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
if (empty($project_id)) {
    $project_idErr = "Required";
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
if (($tokenErr == "") && ($project_idErr == "")) {
    $searchCred[] = array("CreateUnder" => $create_under,"ProjectID" => $project_id, "Status" => $status);
    $retVal = $projectInfoObj->getProjectInfo(json_encode($searchCred));
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
    $dataErrs[] = array("ProjectIDErr" => $project_idErr, "TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

