<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$roleObj = new role(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getRoleData = json_decode(file_get_contents('php://input'));

foreach ($getRoleData as $getRoleDataVal) {
    $token = trim($getRoleDataVal->session_token);
    $login_user = trim($getRoleDataVal->session_user);
    $status = trim($getRoleDataVal->status);
    $role_name = trim($getRoleDataVal->role_name);
    $role_id_from = trim($getRoleDataVal->role_id_from);
    $role_id_to = trim($getRoleDataVal->role_id_to);
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
if (empty($role_name)) {
    $role_name = "";
}
if (empty($role_id_from)) {
    $role_id_from = "";
}
if (empty($role_id_to)) {
    $role_id_to = "";
}
if (($statusErr == "") && ($tokenErr == "")) {
    $searchCred[] = array("RoleName" => $role_name, "RoleIDFrom" => $role_id_from, "RoleIDTo" => $role_id_to, "Status" => $status);
    $retVal = $roleObj->getRoleDetails(json_encode($searchCred));
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

