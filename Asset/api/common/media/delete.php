<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$mediaObj = new media(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getMediaData = json_decode(file_get_contents('php://input'));

foreach ($getMediaData as $getMediaDataVal) {
    $create_user = trim($getMediaDataVal->session_user);
    $token = trim($getMediaDataVal->session_token);
    $media_id= trim($getMediaDataVal->media_id);
    $media_path= trim($getMediaDataVal->media_path);
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

if ($tokenErr == "") {
    $credential[] = array("DeleteMediaID" => $media_id);
    $deleteStatus = $mediaObj->deleteMedia(json_encode($credential));

    if ($deleteStatus > 0) {
        unlink("../../../".$media_path);
        $dataSucc[] = array("SuccessMsg" => "Successfully deleted media");
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
    $dataErrs[] = array("TokenErr" => $tokenErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

