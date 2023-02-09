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
    $create_user = trim($getRuleDataVal->create_user);
    $token = trim($getRuleDataVal->token);
    $rule_id = trim($getRuleDataVal->rule_id);
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
if (empty($rule_id)) {
    $rule_idErr = "Required";
}

if (($rule_idErr == "") && ($tokenErr == "")) {
    $credential[] = array("RuleId" => $rule_id);
    $retVal = $ruleObj->getSelectedRuleDetails(json_encode($credential));
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
       $dataErrs[] = array("ErrorMsg" => "No record found");
        $response['error'] = true;
        $response['message'] = "Record not found";
        $response['data'] = $dataErrs;
    }
} else {
   $dataErrs[] = array("TokenErr" => $tokenErr, "RuleErr" => $rule_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

