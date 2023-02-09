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
    $project_supervisor_name = trim($getProjectDataVal->project_supervisor_name);
    $project_start_date = trim($getProjectDataVal->project_supervisor_start_date);
    $project_end_date = trim($getProjectDataVal->project_supervisor_end_date);
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

if (empty($project_supervisor_name)) {
    $project_supervisor_nameErr = "Required";
}
if (empty($project_start_date)) {
    $project_start_dateErr = "Required";
}
if (empty($project_end_date)) {
    $project_end_dateErr = "Required";
}

$project_supname= explode(",", $project_supervisor_name);
if (($project_idErr == "") && ($project_supervisor_nameErr == "") && ($project_start_dateErr == "") && ($project_end_dateErr == "") && ($tokenErr == "")) {
    foreach($project_supname as $project_supnameVal){
        $id++;
    $credential[] = array("ProjectId" => $project_id, "ProjectSuperName" => $project_supnameVal, "ProjectStartDate" => $project_start_date,
        "ProjectEndDate" => $project_end_date, "Createuser" => $create_user,"Slno" => $id, "Current_date" => $today_date_time);
    $insertStatus = $projectInfoObj->createProjectInfo(json_encode($credential));
    }
    if ($insertStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully created project");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectIdErr" => $project_nameErr,"ProjectSuperNameErr" => $project_supervisor_nameErr,
        "ProjectStartDateErr" => $project_start_dateErr, "ProjectEndDateErr" => $project_end_dateErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

