<?php

include './api/common/global_functions.php';
include './api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    if (isset($_REQUEST['gatepass_id'])) {
        $login_user = $_SESSION['asset_member'];
        $login_token = $_SESSION['asset_token'];
        $gatepass_id = $_REQUEST['gatepass_id'];
        $gp_data[] = array("session_token" => $login_token, "session_user" => $login_user, "gatepass_id" => $gatepass_id, "gp_date"=>"");
        $gp_recv_data = json_encode($gp_data);
        $getgpData = json_decode(callAPI($gp_recv_data, $selected_gatepass_api));
        $ret_agp_error = $getgpData->error;
        $ret_agp_message = $getgpData->message;
        $ret_agp_data = $getgpData->data;
        if ($ret_agp_error == true) {
            $errMsg = $ret_agp_message;
        } else {
            foreach ($ret_agp_data as $dataVal) {
                $gatepassDetail = $dataVal->Details;
            }
            foreach ($gatepassDetail as $gatepassDetailVal) {
                $gatepass_pdf = $gatepassDetailVal->gatepass_string;
            }
            $decoded = base64_decode($gatepass_pdf);
            $file = $gatepass_id . '.pdf';
            file_put_contents($file, $decoded);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink($file);
            exit;
        }
    }
}
?>