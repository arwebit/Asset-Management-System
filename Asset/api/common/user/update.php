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
    $modify_user = trim($getUserDataVal->session_user);
    $token = trim($getUserDataVal->session_token);
    $update_user = trim($getUserDataVal->update_user);
    $mem_first_name = trim($getUserDataVal->mem_first_name);
    $mem_middle_name = trim($getUserDataVal->mem_middle_name);
    $mem_last_name = trim($getUserDataVal->mem_last_name);
    $mem_address = trim($getUserDataVal->mem_address);
    $mem_email = trim($getUserDataVal->mem_email);
    $hmem_email = trim($getUserDataVal->mem_hemail);
    $mem_role = trim($getUserDataVal->mem_role);
    $mem_mobile = trim($getUserDataVal->mem_mobile);
    $hmem_mobile = trim($getUserDataVal->mem_hmobile);
    $emp_code = trim($getUserDataVal->mem_emp_code);
    $hemp_code = trim($getUserDataVal->mem_hemp_code);
    $mem_category = trim($getUserDataVal->mem_category);
    $mem_type = trim($getUserDataVal->mem_type);
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

if (empty($mem_first_name)) {
    $mem_first_nameErr = "Required";
} else {
    if (!preg_match("/^[a-zA-Z ]*$/", $mem_first_name)) {
        $mem_first_nameErr = "Only letters and white space allowed";
    }
}
if (empty($mem_middle_name)) {
    $mem_middle_name = "";
} else {
    if (!preg_match("/^[a-zA-Z ]*$/", $mem_middle_name)) {
        $mem_middle_nameErr = "Only letters and white space allowed";
    }
}
if (empty($mem_last_name)) {
    $mem_last_nameErr = "Required";
} else {
    if (!preg_match("/^[a-zA-Z ]*$/", $mem_last_name)) {
        $mem_last_nameErr = "Only letters and white space allowed";
    }
}
if (empty($update_user)) {
    $mem_user_nameErr = "Required";
} else {
    if (!preg_match("/^[a-zA-Z0-9]*$/", $update_user)) {
        $mem_user_nameErr = "Only alphanumeric allowed";
    } else {
        $usernameCred[] = array("username" => $update_user);
        $retVal = $userObj->getDupUser(json_encode($usernameCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord == 0) {
            $mem_user_nameErr = "No user found";
        }
    }
}
if (empty($mem_category)) {
    $mem_category = null;
}
if (empty($mem_role)) {
    $mem_roleErr = "Required";
}
if (empty($mem_category)) {
    $mem_category = "";
}
if (empty($mem_mobile)) {
    $mem_mobile = "";
} else {
    if (!preg_match("/^[0-9]*$/", $mem_mobile)) {
        $mem_mobileErr = "Only numeric allowed";
    } else {
        if (strlen($mem_mobile) != 10) {
            $mem_mobileErr = "Mobile no. must be 10 digits";
        } else {
            $mobileCred[] = array("mobile_no" => $mem_mobile, "hmobile_no" => $hmem_mobile);
            $retVal = $userObj->getDupMobile(json_encode($mobileCred));
            foreach ($retVal as $retVals) {
                $getCountRecord = $retVals['Record'];
            }
            if ($getCountRecord > 0) {
                $mem_mobileErr = "Duplicate number. Try again";
            }
        }
    }
}
if (empty($mem_email)) {
    $mem_emailErr = "Required";
} else {
    if (!filter_var($mem_email, FILTER_VALIDATE_EMAIL)) {
        $mem_emailErr = "Invalid Email";
    } else {
        $emailCred[] = array("email_id" => $mem_email, "hemail_id" => $hmem_email);
        $retVal = $userObj->getDupEmail(json_encode($emailCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord > 0) {
            $mem_emailErr = "Duplicate email. Try again";
        }
    }
}

if (empty($emp_code)) {
    $emp_codeErr = "Required";
} else {
         $emp_codeCred[] = array("emp_code" => $emp_code, "hemp_code" => $hemp_code);
        $retVal = $userObj->getDupEmpCode(json_encode($emp_codeCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord > 0) {
            $emp_codeErr = "Duplicate employee code";
        }
}

if (empty($mem_type)) {
    $mem_typeErr = "Required";
}
        
if (($emp_codeErr == "") &&($mem_typeErr == "") &&($mem_first_nameErr == "") && ($mem_middle_nameErr == "") && ($mem_last_nameErr == "") && ($mem_user_nameErr == "") && ($mem_roleErr == "") && ($mem_emailErr == "") && ($mem_mobileErr == "")&& ($tokenErr == "")) {
     $credential[] = array("FirstName" => $mem_first_name, "MiddleName" => $mem_middle_name, "LastName" => $mem_last_name,
        "Username" => $mem_user_name, "Role" => $mem_role, "Email" => $mem_email, "Address" => $mem_address, "Mobile" => $mem_mobile, 
        "Modifyuser" => $modify_user, "Category" => $mem_category, "MemberType" => $mem_type, "EmployeeCode" => $emp_code, 
         "Updateuser" => $update_user, "Current_date" => $today_date_time);
    $updateStatus = $userObj->updateUser(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated user");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "FirstNameErr" => $mem_first_nameErr, "MiddleNameErr" => $mem_middle_nameErr,
        "LastNameErr" => $mem_last_nameErr, "UsernameErr" => $mem_user_nameErr, "RoleErr" => $mem_roleErr, "EmailErr" => $mem_emailErr,
        "MobileErr" => $mem_mobileErr, "MemberTypeErr" => $mem_typeErr, "EmployeeCodeErr" => $emp_codeErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

