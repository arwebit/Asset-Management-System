<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../projectClass.php';
include '../../common/loginMember.php';
include '../../common/masterAccess.php';
include '../../employee/gatepass/gatepassGeneration.php';
include "../../lib/pdflib/autoload.php";
include '../../lib/phpqrcode/qrlib.php';

$projectInfoObj = new projectInfo(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$gatepassObj = new gatepass(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$userObj = new user(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$projectObj = new project(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getProjectData = json_decode(file_get_contents('php://input'));

foreach ($getProjectData as $getProjectDataVal) {
    $create_user = trim($getProjectDataVal->session_user);
    $token = trim($getProjectDataVal->session_token);
    $project_id = trim($getProjectDataVal->project_id);
    $project_assign_name = trim($getProjectDataVal->project_assign_name);
    $gatepass_start_date = trim($getProjectDataVal->gatepass_start_date);
    $gatepass_end_date = trim($getProjectDataVal->gatepass_end_date);
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
    }
}

if (empty($project_id)) {
    $project_idErr = "Required";
}

if (empty($project_assign_name)) {
    $project_assign_nameErr = "Required";
}
if (empty($gatepass_start_date)) {
    $gatepass_start_dateErr = "Required";
}
if (empty($gatepass_end_date)) {
    $gatepass_end_dateErr = "Required";
}
if ((!empty($gatepass_start_date)) && (!empty($gatepass_end_date))) {
    $date1 = date_create(date("Y-m-d", strtotime($gatepass_start_date)));
    $date2 = date_create(date("Y-m-d", strtotime($gatepass_end_date)));
    $diff_days = date_diff($date1, $date2)->format("%a");
    $diffDays = $diff_days + 1;
}


$project_assigner_name = explode(",", $project_assign_name);
if (($project_idErr == "") && ($project_assign_nameErr == "") && ($gatepass_start_dateErr == "") && ($gatepass_end_dateErr == "") && ($tokenErr == "")) {

    $assigner = explode(",", $project_assign_name);
    $company_name = company_name();
    $gatepassID = date("YmdHis");
    foreach ($assigner as $assignerVal) {
        $gatepass_status = 1;
        $gatepassID++;
        $usercredential[] = array("Username" => $assignerVal);
        $userVal = $userObj->getSelectedUserDetails(json_encode($usercredential));
        foreach ($userVal as $userVals) {
            $getUserDetails = $userVals['Data'];
        }
        foreach ($getUserDetails as $getUserVals) {
            $employee_id = $getUserVals['member_id'];
            $employee_name = $getUserVals['member_first_name'] . " " . $getUserVals['member_middle_name'] . " " . $getUserVals['member_last_name'];
            $employee_email = $getUserVals['member_email'];
        }

        $appusercredential[] = array("Username" => $create_user);
        $appuserVal = $userObj->getSelectedUserDetails(json_encode($appusercredential));
        foreach ($appuserVal as $appuserVals) {
            $getAppUserDetails = $appuserVals['Data'];
        }
        foreach ($getAppUserDetails as $getAppUserVals) {
            $approver_name = $getAppUserVals['member_first_name'] . " " . $getAppUserVals['member_middle_name'] . " " . $getAppUserVals['member_last_name'];
            $approver_role = $getAppUserVals['role_name'];
        }
        $projectcredential[] = array("ProjectID" => $project_id);
        $projectVal = $projectObj->getSelectedProjectDetails(json_encode($projectcredential));
        foreach ($projectVal as $projectVals) {
            $getProjectDetails = $projectVals['Data'];
        }
        foreach ($getProjectDetails as $getProjectVals) {
            $project_name = $getProjectVals['project_name'];
            $project_location = $getProjectVals['project_location'];
            $project_start_date = $getProjectVals['project_start_date'];
            $project_end_date = $getProjectVals['project_end_date'];
        }
        $today_date_time = curr_date_time();
        $qr_text = "Gatepass ID : $gatepassID, Employee ID : $employee_id, Gatepass date : $gatepass_start_date to $gatepass_end_date";
        $path = "../../employee/gatepass/qr/";
        $qr_code_image = $path . rand(0, 999999) . ".png";
        $ecc = "L";
        $pixel_size = 5;
        $frame_size = "";
        QRcode::png($qr_text, $qr_code_image, $ecc, $pixel_size, $frame_size);
        $output = "<div style='width: 45%; margin-left:30%; font-family: Helvetica Neue,Helvetica,Arial,sans-serif;'>
        <div style='width: 100%;min-height: 100px; color: #FFFFFF; font-weight: bold; background-color: #FF5F1F; padding: 15px;border-radius: 10px;'>
            <table cellpadding='5px' cellspacing='0' style='width: 100%; color: #FFFFFF;'>
                <tr style='font-size:12px;'>
                    <th colspan='2'>$company_name</th>
                </tr>
                <tr style='font-size:14px;'>
                    <th colspan='2'>$employee_name</th>
                </tr>
                <tr style='font-size:12px;'>
                    <th>Approved by</th>
                    <th>Project ID</th>
                </tr>
                <tr style='font-size:14px;'>
                    <th>$approver_name</th>
                    <th>$project_id</th>
                </tr>
            </table>
        </div>
        <div style='width: 112%; border-radius: 10px 10px 10px 10px; min-height:50px; color: #FFFFFF; font-weight: bold; background-color: #F0FFF0;'>
            <div style='padding:5px;'>
                <table cellspacing='0' cellpadding='5px' border='0' style='width: 100%;'>
                <tr style='font-size: 12px;'>
                    <th width='33%' valign='middle' align='center'>Work</th>
                    <th width='33%' valign='middle' align='center'>Location</th>
                    <th width='34%' valign='middle' align='center'>Project Date</th>
                </tr>
                <tr style='font-size: 11px;'>
                    <th valign='top' align='center'>$project_name</th>
                    <th valign='top' align='center'>$project_location</th>
                    <th valign='top' align='center'>" . date('d/m/Y', strtotime($project_start_date)) . " to
                        " . date('d/m/Y', strtotime($project_end_date)) . "</th>
                </tr>
            </table>
            </div>
            <img src='$qr_code_image' style='margin-left:99px; width:110px; height:110px;' alt='QR_code'/>
                <div style='color:#000000; margin-left:88px; margin-top:10px; font-weight: bold;'>$employee_id</div>
         </div>
    </div>";
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($output);
        $fileName = $gatepassID . ".pdf";
        $mpdf->Output($fileName);
        $dir = site_url() . "/api/project/project_info/";
        $b64_pdf_str = base64_encode(file_get_contents($dir . $fileName));
        unlink($fileName);
        array_map('unlink', glob($path . "*.*"));
        $gp_credential[] = array("Username" => $assignerVal, "ProjectID" => $project_id, "PDFStr" => $b64_pdf_str, "GatepassStartDate" => $gatepass_start_date,
            "GatepassEndDate" => $gatepass_end_date, "GatepassStatus" => $gatepass_status, "Createuser" => $create_user, "Slno" => $gatepassID, "Current_date" => $today_date_time);
        $insertgpStatus = $gatepassObj->createGatepass(json_encode($gp_credential));


        if ($insertgpStatus > 0) {
            $count = 0;
            while ($count < $diffDays) {
                $gp_link_credential[] = array("GatepassID" => $gatepassID, "GatepassDate" => $gatepass_start_date);
                $gatepassObj->createGatepassLink(json_encode($gp_link_credential));
                $count++;
                $gatepass_start_date = date("Y-m-d", strtotime($gatepass_start_date . '+ 1 days'));
            }
            $body="";
            $subject = "Gatepass Email";
            $body .= "<h1>Test Gatepass mail</h1>";
            $attachment = array("Base64Str" => $b64_pdf_str, "FileName" => $fileName);
            email_sending($employee_email, $subject, $body, $attachment);
            $dataSucc[] = array("SuccessMsg" => "Successfully generated gatepass", "PDFString" => $b64_pdf_str);
            $response['error'] = false;
            $response['message'] = "Success";
            $response['data'] = $dataSucc;
            
        } else {
            $dataSucc[] = array("ErrorMsg" => "Cannot generate gatepass.");
            $response['error'] = false;
            $response['message'] = "Failed to generate gatepass";
            $response['data'] = $dataSucc;
        }
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectIdErr" => $project_nameErr, "ProjectAssignerErr" => $project_assign_nameErr,
        "GatepassStartDateErr" => $gatepass_start_dateErr, "GatepassEndDateErr" => $gatepass_end_dateErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

