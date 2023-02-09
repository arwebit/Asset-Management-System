<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './taskGenerate.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$taskMasterObj = new taskMaster(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getTaskData = json_decode(file_get_contents('php://input'));

foreach ($getTaskData as $getTaskDataVal) {
    $token = trim($getTaskDataVal->session_token);
    $login_user = trim($getTaskDataVal->session_user);
    $user_role = trim($getTaskDataVal->user_role);    
    $task_master_id = trim($getTaskDataVal->task_master_id);
}
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
    }else {
        $data = $retVals['Data'];
        foreach ($data as $dataVals) {
            $create_under = $dataVals['create_under'];
        }
    }
}

if ($tokenErr == "") {
    $credential[]=array("CreateUnder" => $create_under,"TaskMasterID"=>$task_master_id, "UserName"=>$login_user, 
        "UserRole"=>$user_role);
    $retVal = $taskMasterObj->getTaskMasterDetails(json_encode($credential));
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

