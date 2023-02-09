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
    $login_user = trim($getUserDataVal->session_user);
    $token = trim($getUserDataVal->session_token);
    $old_pass = trim($getUserDataVal->old_pass);
    $new_pass = trim($getUserDataVal->new_pass);
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

if (empty($old_pass)) {
    $oldpassErr = "Required";
} else {
    $old_pass = encrypt_decrypt('encrypt',$old_pass);
    $passCred[] = array("username" => $login_user, "old_pass" => $old_pass);
    $retVal = $userObj->passwordCheck(json_encode($passCred));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
    }
    if ($getCountRecord == 0) {
        $oldpassErr = "Old password is wrong";
    }
}
if (empty($new_pass)) {
    $newpassErr = "Required";
} else {
    $new_pass = encrypt_decrypt('encrypt',$new_pass);
}

if (($oldpassErr == "") && ($newpassErr == "") && ($tokenErr == "")) {
    $credential[] = array("Loginuser" => $login_user, "NewPass" => $new_pass);
    $updateStatus = $userObj->passwordChange(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully changed password");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "OldPassErr" => $oldpassErr, "NewPassErr" => $newpassErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

