<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$searchObj = new search(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getUserData = json_decode(file_get_contents('php://input'));

foreach ($getUserData as $getUserDataVal) {
    $token = trim($getUserDataVal->session_token);
    $login_user = trim($getUserDataVal->session_user);
    $employee_supervisor = trim($getUserDataVal->employee_supervisor);
    $user_role = trim($getUserDataVal->user_role);
}

$today_date_time = date("Y-m-d", strtotime(curr_date_time()));

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
            $create_unders = $dataVals['create_under'];
        }
    }
}

if (empty($user_role)) {
   $user_roleErr = "Required";
}else{
    if($user_role==-2){
        $create_under="";
    } else {
        $create_under=$create_unders;
    }
}
if (empty($employee_supervisor)) {
    $employee_supervisorErr = "Required";
} else {
    if ($employee_supervisor == "Employee") {
        $employee_supervisor = "Employee";
    } else if ($employee_supervisor == "Supervisor") {
        $employee_supervisor = "Supervisor";
    } else {
        $employee_supervisorErr = "Invalid. Value must be Employee or Supervisor";
    }
}

if (($tokenErr == "") && ($employee_supervisorErr == "")&& ($user_roleErr == "")) {
    $searchCred[] = array("CreateUnder" => $create_under,  "Employee_Supervisor" => $employee_supervisor,
        "CurrentDate" => $today_date_time, "LoginUser" => $login_user, "UserRole" => $user_role);
    $retVal = $searchObj->getOngoingProjectDetails(json_encode($searchCred));
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "Employee_SupervisorErr" => $employee_supervisorErr, "UserRoleErr" => $user_roleErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

