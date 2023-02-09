<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$userObj = new user(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getUserData = json_decode(file_get_contents('php://input'));

foreach ($getUserData as $getUserDataVal) {
    $create_user = trim($getUserDataVal->session_user);
    $token = trim($getUserDataVal->session_token);
    $user_name = trim($getUserDataVal->user_name);
    $status_type = trim($getUserDataVal->status);
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

if (empty($user_name)) {
    $user_nameErr = "Required";
} else {
    if (!preg_match("/^[a-zA-Z0-9]*$/", $user_name)) {
        $user_nameErr = "Only alphanumeric allowed";
    } else {
        $usernameCred[] = array("username" => $user_name);
        $retVal = $userObj->getDupUser(json_encode($usernameCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord == 0) {
            $user_nameErr = "No user found";
        }
    }
}
if (empty($status_type)) {
    $status_typeErr = "Required";
} else {
    if ($status_type == "Active") {
        $status = 1;
    } else if ($status_type == "Inactive") {
        $status = 0;
    } else {
        $status_typeErr = "Invalid status type";
    }
}
if (($status_typeErr == "") && ($user_nameErr == "") && ($tokenErr == "")) {
    $credential[] = array("Createuser" => $create_user, "Username" => $user_name, "Status" => $status,
        "Current_date" => $today_date_time);
    $updateStatus = $userObj->availUser(json_encode($credential));

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
    $dataErrs[] = array("TokenErr" => $tokenErr, "StatusErr" => $status_typeErr, "UsernameErr" => $user_nameErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

