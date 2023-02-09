<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './reportClass.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$reportObj = new report(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$data_cred = json_decode(file_get_contents('php://input'));
foreach ($data_cred as $data_credVal) {
    $token = $data_credVal->session_token;
    $create_user = $data_credVal->session_user;
    $id = $data_credVal->daily_report_id;
    $safety_clearence = $data_credVal->safety_clearence;
    $hr_clearence = $data_credVal->hr_clearence;
    $reportdt = $data_credVal->reportdt;
    $supervisor_name = $data_credVal->supervisor_name;
    $work_done = $data_credVal->work_done;
    $work_status = $data_credVal->work_status;
    $work_done_by = $data_credVal->work_done_by;
    $details = $data_credVal->details;
    $emp_remark = $data_credVal->emp_remark;
    $material_shortage = $data_credVal->material_shortage;
    $reference = $data_credVal->reference;
    $pending_work = $data_credVal->pending_work;
    
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

if (empty($reference)) {
    $referenceErr = "Required";
}
if (empty($reportdt)) {
    $reportdtErr = "Required";
}
if (empty($supervisor_name)) {
    $supervisor_nameErr = "Required";
}

if (empty($work_done)) {
    $work_doneErr = "Required";
}
if (empty($work_status)) {
    $work_statusErr = "Required";
}

if (empty($work_done_by)) {
    $work_done_byErr = "Required";
}
if (empty($details)) {
    $detailsErr = "Required";
}
if (empty($emp_remark)) {
    $emp_remarkErr = "Required";
}
if (empty($material_shortage)) {
    $material_shortageErr = "Required";
}
if (empty($pending_work)) {
    $pending_workErr = "Required";
}

if (($tokenErr == "") && ($project_idErr == "") && ($referenceErr == "") && ($reportdtErr == "") && ($supervisor_nameErr == "") && ($work_doneErr == "") && ($work_statusErr == "") && ($work_done_byErr == "") && ($detailsErr == "") && ($emp_remarkErr == "") && ($material_shortageErr == "")&& ($pending_workErr == "")) {
    $credential[] = array("SafetyCL" => $safety_clearence, "HrCL" => $hr_clearence, "ReportDateTime" => $reportdt,
        "SupervisorName" => $supervisor_name, "WorkDone" => $work_done, "WorkStatus" => $work_status, "WorkDoneBy" => $work_done_by,
        "Details" => $details, "EmpRemark" => $emp_remark, "MatShortage" => $material_shortage, "Reference" => $reference,
       "PendingWork" => $pending_work, "Status" => "2", "Createuser" => $create_user, "Slno" => $id, "Current_date" => $today_date_time);
    $updateStatus = $reportObj->updateReport(json_encode($credential));

    if ($updateStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully updated and submitted");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "ProjectErr" => $project_idErr, "ReferenceErr" => $referenceErr, "ReportDateErr" => $reportdtErr, "SupervisorErr" => $supervisor_nameErr,
        "WorkDoneErr" => $work_doneErr, "WorkStatusErr" => $work_statusErr, "WorkDoneByErr" => $work_done_byErr, "DetailsErr" => $detailsErr,
        "EmpRemarkErr" => $emp_remarkErr, "MatShortageErr" => $material_shortageErr, "PendingWorkErr" => $pending_workErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

