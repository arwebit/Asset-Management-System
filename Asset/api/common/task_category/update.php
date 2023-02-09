<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$categoryObj = new category(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getCategoryData = json_decode(file_get_contents('php://input'));

foreach ($getCategoryData as $getCategoryDataVal) {
    $create_user = trim($getCategoryDataVal->session_user);
    $token = trim($getCategoryDataVal->session_token);
    $category_id = trim($getCategoryDataVal->category_id);
    $category_name = trim($getCategoryDataVal->category_name);
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

if (empty($category_name)) {
    $category_nameErr = "Required";
}else{
    $category_name=ucwords($category_name);
}

if (($category_nameErr == "") && ($tokenErr == "")) {
    $credential[] = array("CategoryName" => $category_name, "Createuser" => $create_user, 
        "CategoryID"=>$category_id, "Current_date" => $today_date_time);
    $updateStatus = $categoryObj->updateCategory(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated category");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "CategoryNameErr" => $category_nameErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

