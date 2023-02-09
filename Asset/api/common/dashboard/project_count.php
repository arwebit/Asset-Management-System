<?php

include '../global_functions.php';
include '../../config/header_config.php';
include '../../config/DBconfig.php';
include '../masterAccess.php';

$searchObj = new search(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$getProjectData = json_decode(file_get_contents('php://input'));

foreach ($getProjectData as $getProjectDataVal) {
    $detail_type = trim($getProjectDataVal->detail_type);
}
$detail_type="Count";
$user_role=-2;
$today_date_time = date("Y-m-d", strtotime(curr_date_time()));

if (empty($detail_type)) {
    $detail_typeErr = "Required";
}else{
    if($detail_type=="Details"){
        $chk=1;
    }else if($detail_type=="Count"){
        $chk=0;
    }else{
        $detail_typeErr = "Invalid detail type. Must be Details or Count";
    }
}

if ($detail_typeErr == "") {
    $searchCred[] = array("CurrentDate"=>$today_date_time, "UserRole" => $user_role);
    if($chk==0){
        $retVal = $searchObj->getProjectCount(json_encode($searchCred));
    }else{
        $retVal = $searchObj->getProjectStatus(json_encode($searchCred));
    }
    
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
        $dataErrs[] = array("ErrorMessage" => "No record found", "Records" => "0");
        $response['error'] = true;
        $response['message'] = "Record not exist";
        $response['data'] = $dataErrs;
    }
} else {
    $dataErrs[] = array("DetailTypeErr" => $detail_typeErr);
    $response['error'] = true;
    $response['message'] = "Recorrect errors";
    $response['data'] = $dataErrs;
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>

