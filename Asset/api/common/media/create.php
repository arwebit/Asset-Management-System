<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';
include '../loginMember.php';

$mediaObj = new media(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$loginObj = new loginMember(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$data_cred = json_decode(trim($_REQUEST['data_cred']));
foreach ($data_cred as $data_credVal) {
    $token = $data_credVal->session_token;
    $create_user = $data_credVal->session_user;
}

$files = $_FILES['file_upload'];


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
define("MAX_SIZE", 10485760); // Size limit 10 MB ( Here size is converted to BYTES)
$allowed = array('jpg', 'jpeg', 'png', 'mp4', 'pdf', 'xlsx', 'doc', 'docx', 'JPEG', 'JPG', 'PNG', 'MP4', 'PDF', 'DOC', 'DOCX', 'XLS', 'XLSX');

if ($files['size'] > 0) {
    $path = "media_files/";
    $file_name = basename($files['name']);
    $file_size = $files['size']; // File size in "BYTES"
    $file_type= $files['type'];
    $file_tmp_name = $files['tmp_name'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!(in_array($ext, $allowed))) {
        $fileErr = "Upload JPEG,JPG,PNG,MP4,PDF,'DOC','DOCX','XLS','XLSX' files";
    } else {
        if ($file_size > MAX_SIZE) {
            $fileErr = "Upload less than or equal to 10 MB";
        }else {
            if (($file_type == "image/jpg") || ($file_type == "image/jpeg") || ($file_type == "image/png")) {

                $filename = $id . "." . $ext;
                $sourceProperties = getimagesize($file_tmp_name);
                $sourceImageWidth = $sourceProperties[0];
                $sourceImageHeight = $sourceProperties[1];
                $filepath_storage = $path . $filename;
                $upload_folder="../../../".$filepath_storage;
                if (($sourceImageWidth > 799) && ($sourceImageHeight > 299)) {
                    $src = imagecreatefromjpeg($file_tmp_name);
                    $imageLayer = resizeImage($src, $sourceImageWidth, $sourceImageHeight, 800, 300);
                    if (imagejpeg($imageLayer, $upload_folder)) {
                        $file_move = 1;
                    } else {
                        unlink($upload_folder);
                        $fileErr = "File cannot be inserted into folder";
                    }
                } else {
                    $fileErr = "File size must be greater than or equal to 800*300";
                }
            } else {
                $filename = $id . "." . $ext;
                $filepath_storage = $path . $filename;
                $upload_folder="../../../".$filepath_storage;
                if (move_uploaded_file($file_tmp_name, $upload_folder)) {
                    $file_move = 1;
                } else {
                    unlink($upload_folder);
                    $fileErr = "File cannot be inserted into folder";
                }
            }
        }
    }
} else {
    $fileErr = "Insert file.";
}


if (($tokenErr == "") && ($fileErr == "")) {
    $credential[] = array("MediaPath" => $filepath_storage, "MediaExtension" => $file_type, "Createuser" => $create_user,
        "Slno" => $id, "Current_date" => $today_date_time);
    $insertStatus = $mediaObj->createMedia(json_encode($credential));

    if ($insertStatus > 0) {
        $dataSucc[] = array("SuccessMsg" => "Successfully uploaded");
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
    $dataErrs[] = array("TokenErr" => $tokenErr, "MediaErr" => $fileErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

