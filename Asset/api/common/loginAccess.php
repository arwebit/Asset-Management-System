<?php

include './global_functions.php';
include '../config/DBconfig.php';
include './loginMember.php';

$loginMemberObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    unset($_SESSION['asset_member']);
    unset($_SESSION['asset_token']);
}
$getLoginData = json_decode(file_get_contents('php://input'));
$today_date_time = curr_date_time();
foreach ($getLoginData as $getLoginDataVal) {
    $username = $getLoginDataVal->username;
    $password = $getLoginDataVal->password;
    $role_id = $getLoginDataVal->user_role_id;
}

if (empty($username)) {
    $usernameErr = "Required";
}
if (empty($password)) {
    $passwordErr = "Required";
} else {
    $pwd = encrypt_decrypt('encrypt', $password);
}
if (empty($role_id)) {
    $role_idErr = "Required";
}
if (($usernameErr == "") && ($passwordErr == "")) {
    $user_passCred[] = array("username" => $username, "password" => $pwd);
    $getCountRecord = $loginMemberObj->getLoginAccess(json_encode($user_passCred));

    if ($getCountRecord > 0) {
        $userCred[] = array("username" => $username);
        $getLoginActiveRecord = $loginMemberObj->getLoginActive(json_encode($userCred));
        if ($getLoginActiveRecord > 0) {
            $user_roleCred[] = array("username" => $username, "user_role_id" => $role_id);
            $getLoginRoleRecord = $loginMemberObj->getLoginRoleMatch(json_encode($user_roleCred));
            if ($getLoginRoleRecord > 0) {
                $token = generate_token($username, $password);
                $tokenCred[] = array("Username" => $username, "TokenVal" => $token);
                $saveTokenStatus = $loginMemberObj->saveToken(json_encode($tokenCred));
                if ($saveTokenStatus > 0) {
                    $loginDetailsCred[] = array("username" => $username);
                    $retVal = $loginMemberObj->getLoginDetails(json_encode($loginDetailsCred));
                    foreach ($retVal as $retVals) {
                        $getDetails = $retVals['Data'];
                    }
                    if ($role_id == 3) {
                        $gatepassCred[] = array("Username" => $username, "Status" => "0",
                            "Createuser" => $username, "Current_date" => $today_date_time);
                        $loginMemberObj->projectAssignGPInactive(json_encode($gatepassCred));
                    } else {
                        $proj_infoCred[] = array("Username" => $username, "Status" => "0",
                            "Createuser" => $username, "Current_date" => $today_date_time);
                        $loginMemberObj->projectInfoInactive(json_encode($proj_infoCred));
                    }
                    $succretData[] = array("Token" => $token, "Details" => $getDetails);
                    $response['error'] = false;
                    $response['message'] = "Successfully logged in";
                    $response['data'] = $succretData;
                } else {
                    $dataErrs[] = array("ErrorMessage" => "Cannot generate token");
                    $response['error'] = true;
                    $response['message'] = "Token problem";
                    $response['data'] = $dataErrs;
                }
            } else {
                $dataErrs[] = array("ErrorMessage" => "Username and user-role doesnot match");
                $response['error'] = true;
                $response['message'] = "User and role mismatch";
                $response['data'] = "Username and user-role doesnot match";
                $response['data'] = $dataErrs;
            }
        } else {
            $dataErrs[] = array("ErrorMessage" => "The user is inactive");
            $response['error'] = true;
            $response['message'] = "In-active user";
            $response['data'] = $dataErrs;
        }
    } else {
        $dataErrs[] = array("ErrorMessage" => "Wrong credentials");
        $response['error'] = true;
        $response['message'] = "Record not exist";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("UserNameErr" => $usernameErr, "PasswordErr" => $passwordErr, "RoleErr" => $role_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

