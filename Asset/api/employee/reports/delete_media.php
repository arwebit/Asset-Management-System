<?php

include '../../common/global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../../common/loginMember.php';
include './reportClass.php';

$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mediaObj = new media(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$data_cred = json_decode(file_get_contents('php://input'));
foreach ($data_cred as $data_credVal) {
    $token = $data_credVal->session_token;
    $create_user = $data_credVal->session_user;
    $daily_report_id = $data_credVal->daily_report_id;
    $report_media_id = $data_credVal->report_media_id;
    $report_media_path = $data_credVal->report_media_path;
}
if (empty($report_media_id)) {
    $report_media_idErr = "Required";
}
if (($tokenErr == "") && ($report_media_idErr == "")) {
    $credential[] = array("ReportID" => $daily_report_id, "DeleteMediaID" => $report_media_id);
    $deleteStatus = $mediaObj->deleteMadia(json_encode($credential));

    if ($deleteStatus > 0) {
        unlink($report_media_path);
        $dataSucc[] = array("SuccessMsg" => "Successfully deleted");
        $response['error'] = false;
        $response['message'] = "Success";
        $response['data'] = $dataSucc;
    } else {
        $dataErrs[] = array("ErrorMsg" => "Failed to delete");
        $response['error'] = true;
        $response['message'] = "Server error";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("TokenErr" => $tokenErr, "MediaIDErr" => $report_media_idErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

