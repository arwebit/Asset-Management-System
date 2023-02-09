<?php

error_reporting(1);
session_start();
ob_start();

date_default_timezone_set('Asia/Kolkata');

function curr_date_time() {
    $cuur_date = date("Y-m-d H:i:s");
    return $cuur_date;
}

function site_url() {
    $link = "http://localhost/A_Client_projects/Asset";
//   $link = "https://arweb.in/Asset_others/Asset";
    return $link;
}

function generate_token($string1, $string2) {
    $gen_token = base64_encode($string1 . $string2);
    return $gen_token;
}

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'SECRET_KEY';
    $secret_iv = 'secret_iv';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function callAPI($data, $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function callMediaAPI($data, $url) {
// Prepare the cURL call to upload the external script
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function resizeImage($resourceType, $image_width, $image_height, $resizeWidth, $resizeHeight) {
    $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
    imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height);
    return $imageLayer;
}

function company_name() {
    $company = "Company name";
    return $company;
}

function company_email() {
    $company_email = "tutorcode992@gmail.com";
    return $company_email;
}

function email_sending($recipient_email, $subject, $msg_content, $attachment = "") {
    $fromname = company_name();
    $fromemail = company_email();
    $mailto = $recipient_email;
    $separator = md5(time());
    $eol = "\r\n";
    $headers = "From: " . $fromname . " <" . $fromemail . ">" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;
    $body = "--" . $separator . $eol;
    $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
    $body .= $msg_content . $eol;
    $body .= "--" . $separator . $eol;
    if ($attachment != "") {
        $base64String = $attachment['Base64Str'];
        $filename = $attachment['FileName'];
        $content = $base64String;
        $body .= "Content-Type:application/octet-stream; name=\"" . $filename . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        $body .= "--" . $separator . "--";
    }
    if (mail($mailto, $subject, $body, $headers)) {
        $send_mail = "Mail sent";
    } else {
        $send_mail = "Mail not sent";
    }
    return $send_mail;
}

?>

