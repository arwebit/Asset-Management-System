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
    $action = $data_credVal->action;
    $remarks = $data_credVal->remarks;
    $user_role_id = $data_credVal->user_role_id;
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
if ($action == "0") {
    $action = "0";
} else {
    if (empty($action)) {
        $actionErr = "Required";
    }
}
if (empty($remarks)) {
    $remarksErr = "Required";
}
if (empty($user_role_id)) {
    $user_role_idErr = "Required";
}

if (($tokenErr == "") && ($task_detail_idErr == "") && ($user_role_idErr == "") && ($actionErr == "") && ($remarksErr == "")) {
    $credential[] = array("TaskDetailID" => $task_detail_id, "Action" => $action, "Remarks" => $remarks,
        "UserRoleID" => $user_role_id, "LoginUser" => $login_user);
    $updateStatus = $taskMasterObj->updateTaskDetailsRemarks(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated remarks");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "RoleErr" => $user_role_idErr, "ActionErr" => $actionErr,
        "RemarksErr" => $remarksErr, "TaskDetailIDErr" => $task_detail_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

