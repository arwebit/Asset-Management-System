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
    $modify_user = trim($getProjectDataVal->session_user);
    $token = trim($getProjectDataVal->session_token);
    $project_id = trim($getProjectDataVal->project_id);
    $project_name = trim($getProjectDataVal->project_name);
    $project_location = trim($getProjectDataVal->project_location);
     $project_scope = trim($getProjectDataVal->project_scope);
    $project_address = trim($getProjectDataVal->project_address);
    $project_start_date = trim($getProjectDataVal->project_start_date);
    $project_end_date = trim($getProjectDataVal->project_end_date);
}

$today_date_time = curr_date_time();

if (empty($token)) {
    $tokenErr = "Required";
} else {
    $tokenCred[] = array("username" => $modify_user, "tokenval" => $token);
    $retVal = $loginObj->getLoginToken(json_encode($tokenCred));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
    }
    if ($getCountRecord == 0) {
        $tokenErr = "Token problem.";
    }
}

if (empty($project_name)) {
    $project_nameErr = "Required";
}

if (empty($project_location)) {
    $project_locationErr = "Required";
}
if (empty($project_start_date)) {
    $project_start_dateErr = "Required";
}
if (empty($project_end_date)) {
    $project_end_dateErr = "Required";
}
if (empty($project_scope)) {
    $project_scopeErr = "Required";
}
if (empty($project_address)) {
    $project_addressErr = "Required";
}

if (($project_nameErr == "") && ($project_locationErr == "") &&($project_scopeErr == "") && ($project_addressErr == "") && ($project_start_dateErr == "")  && ($project_end_dateErr == "") && ($tokenErr == "")) {
    $credential[] = array("ProjectName" => $project_name, "ProjectLocation" => $project_location, "ProjectStartDate" => $project_start_date,
        "ProjectEndDate" => $project_end_date,  "ProjectScope" => $project_scope, "ProjectAddress" => $project_address,
        "Modifyuser" => $modify_user, "Updateprojectid" => $project_id, "Current_date" => $today_date_time);
    $updateStatus = $projectObj->updateProject(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated project");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectNameErr" => $project_nameErr, "ProjectStartDateErr" => $project_start_dateErr,
        "ProjectLocationErr" => $project_locationErr, "ProjectScopeErr" => $project_scopeErr, "ProjectAddressErr" => $project_addressErr,
        "ProjectEndDateErr" => $project_end_dateErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

