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
    $task_detail_id = $data_credVal->task_detail_id;
    $task_actual_value = $data_credVal->task_actual_value;
    $check_supervisor = $data_credVal->check_supervisor;
    $emp_remarks = $data_credVal->emp_remarks;
}

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
if (empty($task_detail_id)) {
    $task_detail_idErr = "Required";
}
if (empty($task_actual_value)) {
    $task_actual_valueErr = "Required";
} else {
    if (!preg_match("/^[0-9.]*$/", $task_actual_value)) {
        $task_actual_valueErr = "Only numeric and dot allowed";
    }
}
if (empty($check_supervisor)) {
    $check_supervisorErr = "Required";
}
if (empty($emp_remarks)) {
    $emp_remarksErr = "Required";
}


if (($tokenErr == "") && ($task_detail_idErr == "") && ($task_actual_valueErr == "") && ($check_supervisorErr == "") && ($emp_remarksErr == "")) {
    $credential[] = array("TaskDetailID" => $task_detail_id, "TaskActualValue" => $task_actual_value, "Supervisor" => $check_supervisor,
        "EmpRemarks" => $emp_remarks, "Createuser" => $login_user, "Current_date" => $today_date_time);
    $updateStatus = $taskMasterObj->updateTaskDetails(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "SupervisorErr" => $check_supervisorErr, "ActualValueErr" => $task_actual_valueErr,
        "EmployeeRemarksErr" => $emp_remarksErr, "TaskDetailID"=>$task_detail_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

