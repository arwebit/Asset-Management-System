<?php

include '../../global_functions.php';
include '../../../config/header_config.php';
include '../../../config/DBconfig.php';
include './adminData.php';
include '../../loginMember.php';

$adminRecObj = new adminRecords(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getProjectData = json_decode(file_get_contents('php://input'));

foreach ($getProjectData as $getProjectDataVal) {
    $token = trim($getProjectDataVal->session_token);
    $login_user = trim($getProjectDataVal->session_user);
    $create_under = trim($getProjectDataVal->create_under);
    $detail_type = trim($getProjectDataVal->detail_type);
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
    }
}

if (empty($detail_type)) {
    $detail_typeErr = "Required";
}else{
    if($detail_type=="Details"){
        $chk=1;
    }else if($detail_type=="Count"){
        $chk=0;
    }else{
        $detail_typeErr = "Invalid detail type. Must be Details or Count";
    }
}

if (($tokenErr == "")&&($detail_typeErr == "")&&($create_underErr == "")) {
    $searchCred[] = array("CurrentDate"=>$today_date_time, "CreateUnder" => $create_under);
    if($chk==0){
        $retVal = $adminRecObj->getProjectCount(json_encode($searchCred));
    }else{
        $retVal = $adminRecObj->getProjectStatus(json_encode($searchCred));
    }
    
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
   $dataErrs[] = array("TokenErr" => $tokenErr, "DetailTypeErr" => $detail_typeErr,
        "CreateUnderErr" => $create_underErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

