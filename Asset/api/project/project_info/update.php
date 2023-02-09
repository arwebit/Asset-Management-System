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
    $project_info_id = trim($getProjectDataVal->project_info_id);
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

if (empty($project_info_id)) {
    $project_info_idErr = "Required";
}

if (empty($project_supervisor_name)) {
    $project_supervisor_nameErr = "Required";
}
if (empty($project_start_date)) {
    $project_start_dateErr = "Required";
}
if (empty($project_end_date)) {
    $project_end_dateErr = "Required";
} else {
    $date1 = date_create(date("Y-m-d", strtotime($project_end_date)));
    $date2 = date_create(date("Y-m-d", strtotime(curr_date_time())));
    $diff = date_diff($date2, $date1);
    $datedifference = $diff->format("%R%a days");
    if($datedifference>=0){
        $status="1";
    }else{
        $status="0";
    }
}


if (($project_info_idErr == "") && ($project_supervisor_nameErr == "") && ($project_start_dateErr == "") && ($project_end_dateErr == "") && ($tokenErr == "")) {
    $credential[] = array("ProjectInfoId" => $project_info_id, "ProjectSuperName" => $project_supervisor_name, "ProjectStartDate" => $project_start_date,
        "ProjectEndDate" => $project_end_date, "Status" => $status, "Createuser" => $create_user, "Current_date" => $today_date_time);
    $updateStatus = $projectInfoObj->updateProjectInfo(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated project info");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectIdErr" => $project_nameErr, "ProjectSuperNameErr" => $project_supervisor_nameErr,
        "ProjectStartDateErr" => $project_start_dateErr, "ProjectEndDateErr" => $project_end_dateErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

