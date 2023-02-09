<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$userObj = new user(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getUserRuleData = json_decode(file_get_contents('php://input'));

foreach ($getUserRuleData as $getUserRuleDataVal) {
    $create_user = trim($getUserRuleDataVal->create_user);
    $token = trim($getUserRuleDataVal->token);
    $user_name = trim($getUserRuleDataVal->user_name);
    $rule_previlige = trim($getUserRuleDataVal->rule_previlige);
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
if (empty($rule_previlige)) {
    $rule_previlige = "0";
} else {
    if (!preg_match("/^[0-9,]*$/", $rule_previlige)) {
        $rule_previligeErr = "Only numbers and commas are allowed";
    }
}

if (($user_nameErr == "") && ($rule_previligeErr == "") && ($tokenErr == "")) {
    $credential[] = array("Createuser" => $create_user, "Username" => $user_name, "RulePrevilige" => $rule_previlige,
        "Current_date" => $today_date_time);
    $updateStatus = $userObj->setUserRules(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully set rules");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to set rules");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "UsernameErr" => $user_nameErr, "RulePreviligeErr" => $rule_previligeErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

