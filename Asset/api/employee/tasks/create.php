<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './taskGenerate.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$taskMasterObj = new taskMaster(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$data_cred = json_decode(file_get_contents('php://input'));
foreach ($data_cred as $data_credVal) {
    $token = $data_credVal->session_token;
    $login_user = $data_credVal->session_user;
    $project_id = $data_credVal->project_id;
    $task_description = $data_credVal->task_description;
    $task_standard_value = $data_credVal->task_standard_value;
    $category_id = $data_credVal->category_id;
    $task_unit = $data_credVal->task_unit;
    $deviation_min_range = $data_credVal->deviation_min_range;
    $deviation_max_range = $data_credVal->deviation_max_range;
}

$id = date("YmdHis", strtotime(curr_date_time()));
$today_date_time = curr_date_time();

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
if (empty($project_id)) {
    $project_idErr = "Required";
}
if (empty($task_description)) {
    $task_descriptionErr = "Required";
}
if (empty($task_standard_value)) {
    $task_standard_valueErr = "Required";
} else {
    if (!preg_match("/^[0-9.]*$/", $task_standard_value)) {
        $task_standard_valueErr = "Only numeric and dot allowed";
    }
}
if (empty($category_id)) {
    $category_idErr = "Required";
}

if (empty($task_unit)) {
    $task_unitErr = "Required";
}
if (empty($deviation_min_range)) {
    $deviation_min_rangeErr = "Required";
} else {
    if (!preg_match("/^[0-9.]*$/", $deviation_min_range)) {
        $deviation_min_rangeErr = "Only numeric and dot allowed";
    }
}

if (empty($deviation_max_range)) {
    $deviation_max_rangeErr = "Required";
} else {
    if (!preg_match("/^[0-9.]*$/", $deviation_max_range)) {
        $deviation_max_rangeErr = "Only numeric and dot allowed";
    }
}

if (($tokenErr == "") && ($project_idErr == "") && ($task_descriptionErr == "") && ($task_standard_valueErr == "") && ($category_idErr == "") && ($task_unitErr == "") && ($deviation_min_rangeErr == "") && ($deviation_max_rangeErr == "")) {
    $credential[] = array("ProjectID" => $project_id, "CategoryID" => $category_id, "TaskDescription" => $task_description,
        "TaskUnit" => $task_unit, "StandardValue" => $task_standard_value, "MinDeviation" => $deviation_min_range,
        "MaxDeviation" => $deviation_max_range, "Createuser" => $login_user, "Slno" => $id, "Current_date" => $today_date_time);
    $insertStatus = $taskMasterObj->createTaskMaster(json_encode($credential));

    if ($insertStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully inserted");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectErr" => $project_idErr, "CategoryErr" => $category_idErr,
        "TaskDescriptionErr" => $task_descriptionErr, "StandardValueErr" => $task_standard_valueErr, "TaskUnitErr" => $task_unitErr,
        "DeviationMaxErr" => $deviation_max_rangeErr, "DeviationMinErr" => $deviation_min_rangeErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

