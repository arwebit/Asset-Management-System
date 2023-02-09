<?php

include '../common/global_functions.php';
include '../config/header_config.php';
include '../config/DBconfig.php';
include './projectClass.php';
include '../common/loginMember.php';

$projectObj = new project(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getProjectData = json_decode(file_get_contents('php://input'));

foreach ($getProjectData as $getProjectDataVal) {
    $login_user = trim($getProjectDataVal->session_user);
    $token = trim($getProjectDataVal->session_token);
    $user_role = trim($getProjectDataVal->user_role);
    $status = trim($getProjectDataVal->status);
    $project_name = trim($getProjectDataVal->project_name);
    $project_location = trim($getProjectDataVal->project_location);
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

if (empty($project_name)) {
    $project_name = "";
}
if (empty($project_location)) {
    $project_location = "";
}
if (empty($user_role)) {
    $user_roleErr = "Required";
} else {
    if ($user_role < 2) {
        $login_user = "";
    }else{
        $login_user=$login_user;
    }
}
if (($tokenErr == "") && ($statusErr == "") && ($user_roleErr == "")) {
    $searchCred[] = array("CreateUnder" => $create_under, "LoginUser" => $login_user, "ProjectName" => $project_name, 
        "ProjectLocation" => $project_location, "Status" => $status, "UserRole"=>$user_role);
    $retVal = $projectObj->getProjectDetails(json_encode($searchCred));
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
    $dataErrs[] = array("StatusErr" => $statusErr, "RoleErr" => $user_roleErr, "TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

