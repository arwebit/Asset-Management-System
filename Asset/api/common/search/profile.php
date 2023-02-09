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
    $emp_type = trim($getUserDataVal->emp_type);
    $user_role = trim($getUserDataVal->user_role);
}
$today_date_time = date("Y-m-d", strtotime(curr_date_time()));

if (empty($user_role)) {
    $user_roleErr = "Required";
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
    }
}
if (empty($emp_type)) {
    $emp_typeErr = "Required";
} else {
    if ($emp_type == "Employee") {
        $emp_type = "Employee";
    } else if ($emp_type == "Supervisor") {
        $emp_type = "Supervisor";
    } else if ($emp_type == "Admin") {
        $emp_type = "Admin";
    } else if ($emp_type == "HR") {
        $emp_type = "HR";
    } else {
        $emp_typeErr = "Invalid. Value must be Employee / Supervisor / Admin / HR";
    }
}

if (($tokenErr == "") && ($emp_typeErr == "") && ($user_roleErr == "")) {
    $searchCred[] = array("EmpType" => $emp_type, "LoginUser" => $login_user,"CurrentDate" => $today_date_time, 
        "UserRole" => $user_role);
    $retVal = $searchObj->getProfile(json_encode($searchCred));
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "EmployeeTypeErr" => $emp_typeErr, "UserRoleErr" => $user_roleErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

