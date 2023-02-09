<?php

include '../common/global_functions.php';
include '../config/header_config.php';
include '../config/DBconfig.php';
include './manualClass.php';
include '../common/loginMember.php';

$manualObj = new manual(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getManualData = json_decode(file_get_contents('php://input'));

foreach ($getManualData as $getManualDataVal) {
    $create_user = trim($getManualDataVal->session_user);
    $token = trim($getManualDataVal->session_token);
    $status = trim($getManualDataVal->status);
    $manual_id = trim($getManualDataVal->manual_id);
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
if (empty($manual_id)) {
    $manual_idErr = "Required";
}

if (($tokenErr == "") && ($manual_idErr == "")) {
    $searchCred[] = array("ManualID" => $manual_id);
    $retVal = $manualObj->getSelectedManualDetails(json_encode($searchCred));
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
    $dataErrs[] = array("ManualIDErr" => $manual_idErr, "TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

