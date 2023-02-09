<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$categoryObj = new empcategory(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getCategoryData = json_decode(file_get_contents('php://input'));

foreach ($getCategoryData as $getCategoryDataVal) {
    $create_user = trim($getCategoryDataVal->session_user);
    $token = trim($getCategoryDataVal->session_token);
    $category_id = trim($getCategoryDataVal->category_id);
}

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
if (empty($category_id)) {
    $category_idErr = "Required";
}

if (($category_idErr == "") && ($tokenErr == "")) {
    $credential[] = array("CategoryId" => $category_id);
    $retVal = $categoryObj->getSelectedCategoryDetails(json_encode($credential));
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
       $dataErrs[] = array("ErrorMsg" => "No record found", "Records" => "0");
        $response['error'] = true;
        $response['message'] = "Record not found";
        $response['data'] = $dataErrs;
    }
} else {
   $dataErrs[] = array("TokenErr" => $tokenErr, "CategoryErr" => $category_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

