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
    $token = trim($getUserDataVal->session_token);
    $login_user = trim($getUserDataVal->session_user);
    $status = trim($getUserDataVal->status);
    $user_name = trim($getUserDataVal->user_name);
    $first_name = trim($getUserDataVal->first_name);
    $mobile = trim($getUserDataVal->mobile);
    $email = trim($getUserDataVal->email);
    $role_id_from = trim($getUserDataVal->role_id_from);
    $role_id_to = trim($getUserDataVal->role_id_to);
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
if($login_user=="Superadmin"){
    $created_under="";
}else{
    $created_under=$create_under;
}

if (empty($status)) {
    $status = "";
} else {
    if ($status == "Active") {
        $status = "Active";
    } else if ($status == "Inactive") {
        $status = "Inactive";
    } else {
        $statusErr = "Invalid status type";
    }
}
if (empty($first_name)) {
    $first_name = "";
}
if (empty($mobile)) {
    $mobile = "";
}
if (empty($email)) {
    $email = "";
}
if (($statusErr == "") && ($first_nameErr == "") && ($mobileErr == "") && ($emailErr == "") && ($tokenErr == "")) {
    $searchCred[] = array("CreateUnder" => $created_under, "FirstName" => $first_name, "Mobile" => $mobile, "Email" => $email, "RoleIDFrom" => $role_id_from,
        "RoleIDTo" => $role_id_to, "Status" => $status);
    $retVal = $userObj->getUserDetails(json_encode($searchCred));
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
    $dataErrs[] = array("FirstNameErr" => $first_nameErr,"MobileErr" => $mobileErr,"EmailErr" => $emailErr,
        "StatusErr" => $statusErr, "TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

