<?php

include '../common/global_functions.php';
include '../config/header_config.php';
include '../config/DBconfig.php';
include './projectClass.php';
include '../common/loginMember.php';

$projectObj = new project(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getProjectData = json_decode(file_get_contents('php://input'));

foreach ($getProjectData as $getProjectDataVal) {
    $create_user = trim($getProjectDataVal->session_user);
    $token = trim($getProjectDataVal->session_token);
    $status = trim($getProjectDataVal->status);
    $project_id = trim($getProjectDataVal->project_id);
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
if (empty($project_id)) {
    $project_idErr = "Required";
}

if (($tokenErr == "") && ($project_idErr == "")) {
    $searchCred[] = array("ProjectID" => $project_id);
    $retVal = $projectObj->getSelectedProjectDetails(json_encode($searchCred));
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

