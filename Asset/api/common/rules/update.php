<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$ruleObj = new rule(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getRuleData = json_decode(file_get_contents('php://input'));

foreach ($getRuleData as $getRuleDataVal) {
    $modify_user = trim($getRuleDataVal->modify_user);
    $token = trim($getRuleDataVal->token);
    $update_rule_id = trim($getRuleDataVal->update_rule_id);
    $rule_name = trim($getRuleDataVal->rule_name);
}

$today_date_time = curr_date_time();

if (empty($token)) {
    $tokenErr = "Required";
} else {
    $tokenCred[] = array("username" => $modify_user, "tokenval" => $token);
    $retVal = $loginObj->getLoginToken(json_encode($tokenCred));
    foreach ($retVal as $retVals) {
        $getCountRecord = $retVals['Record'];
    }
    if ($getCountRecord == 0) {
        $tokenErr = "Token problem.";
    }
}

if (empty($rule_name)) {
    $rule_nameErr = "Required";
} else {
    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $rule_name)) {
        $rule_nameErr = "Only alphanumeric and white space allowed";
    }
}

if (($rule_nameErr == "") && ($tokenErr == "")) {
    $credential[] = array("RuleName" => $rule_name, "UpdateruleID" => $update_rule_id, "Modifyuser" => $create_user,
        "Current_date" => $today_date_time);
    $updateStatus = $ruleObj->updateRule(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated rule");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "RuleNameErr" => $rule_nameErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

