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
    $create_user = trim($getUserDataVal->session_user);
    $token = trim($getUserDataVal->session_token);
    $mem_first_name = trim($getUserDataVal->mem_first_name);
    $mem_middle_name = trim($getUserDataVal->mem_middle_name);
    $mem_last_name = trim($getUserDataVal->mem_last_name);
    $mem_user_name = trim($getUserDataVal->mem_user_name);
    $mem_address = trim($getUserDataVal->mem_address);
    $mem_email = trim($getUserDataVal->mem_email);
    $mem_role = trim($getUserDataVal->mem_role);
    $mem_mobile = trim($getUserDataVal->mem_mobile);
    $mem_category = trim($getUserDataVal->mem_category);
    $mem_type= trim($getUserDataVal->mem_type);
    $emp_code = trim($getUserDataVal->mem_emp_code);
}

$id = date("YmdHis", strtotime(curr_date_time()));
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
    } else {
        $data = $retVals['Data'];
        foreach ($data as $dataVals) {
            $getCreate_under = $dataVals['create_under'];
        }
        if ($mem_role == -1) {
            $create_under = $mem_user_name;
        } else {
            $create_under = $getCreate_under;
        }
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
if (empty($mem_user_name)) {
    $mem_user_nameErr = "Required";
} else {
        $usernameCred[] = array("username" => $mem_user_name);
        $retVal = $userObj->getDupUser(json_encode($usernameCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord > 0) {
            $mem_user_nameErr = "User exists. Try again";
    }
}
if (empty($mem_role)) {
    $mem_roleErr = "Required";
}
if (empty($mem_category)) {
    $mem_category = null;
}
if (empty($mem_mobile)) {
   $mem_mobileErr = "Required";
} else {
    if (!preg_match("/^[0-9]*$/", $mem_mobile)) {
        $mem_mobileErr = "Only numeric allowed";
    } else {
        if (strlen($mem_mobile) != 10) {
            $mem_mobileErr = "Mobile no. must be 10 digits";
        } else {
            $mobileCred[] = array("mobile_no" => $mem_mobile, "hmobile_no" => "");
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
        $emailCred[] = array("email_id" => $mem_email, "hemail_id" => "");
        $retVal = $userObj->getDupEmail(json_encode($emailCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord > 0) {
            $mem_emailErr = "Duplicate email. Try again";
        }
    }
}
if (empty($mem_type)) {
    $mem_typeErr = "Required";
}

if (empty($emp_code)) {
    $emp_codeErr = "Required";
} else {
         $emp_codeCred[] = array("emp_code" => $emp_code, "hemp_code" => "");
        $retVal = $userObj->getDupEmpCode(json_encode($emp_codeCred));
        foreach ($retVal as $retVals) {
            $getCountRecord = $retVals['Record'];
        }
        if ($getCountRecord > 0) {
            $emp_codeErr = "Duplicate employee code";
        }
}
$password = encrypt_decrypt('encrypt', $mem_user_name);

if (($emp_codeErr == "") &&($mem_typeErr == "") &&($mem_first_nameErr == "") && ($mem_middle_nameErr == "") && ($mem_last_nameErr == "") && ($mem_user_nameErr == "") && ($mem_roleErr == "") && ($mem_emailErr == "") && ($mem_mobileErr == "") && ($tokenErr == "")) {
    $credential[] = array("CreateUnder" => $create_under, "FirstName" => $mem_first_name, "MiddleName" => $mem_middle_name, "LastName" => $mem_last_name,
        "Username" => $mem_user_name, "Role" => $mem_role, "Email" => $mem_email, "Password" => $password, "Address" => $mem_address,
        "Mobile" => $mem_mobile, "Category" => $mem_category, "Createuser" => $create_user, "MemberType" => $mem_type, "EmployeeCode" => $emp_code,
        "Slno" => $id, "Current_date" => $today_date_time);
    $insertStatus = $userObj->createUser(json_encode($credential));

    if ($insertStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully created user");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to insert");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "FirstNameErr" => $mem_first_nameErr, "MiddleNameErr" => $mem_middle_nameErr, 
        "LastNameErr" => $mem_last_nameErr, "UsernameErr" => $mem_user_nameErr, "RoleErr" => $mem_roleErr, "EmailErr" => $mem_emailErr,
        "MobileErr" => $mem_mobileErr,"MemberTypeErr" => $mem_typeErr, "EmployeeCodeErr" => $emp_codeErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

