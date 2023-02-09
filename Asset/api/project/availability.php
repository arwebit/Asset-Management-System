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
    $project_id = trim($getProjectDataVal->project_id);
    $status_type = trim($getProjectDataVal->status);
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

if (empty($project_id)) {
    $projectErr = "Required";
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
if (($status_typeErr == "") && ($projectErr == "") && ($tokenErr == "")) {
    $credential[] = array("Createuser" => $create_user, "Project_id" => $project_id, "Status" => $status,
        "Current_date" => $today_date_time);
    $updateStatus = $projectObj->availProject(json_encode($credential));

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
    $dataErrs[] = array("TokenErr" => $tokenErr, "StatusErr" => $status_typeErr, "ProjectErr" => $projectErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data']= $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

