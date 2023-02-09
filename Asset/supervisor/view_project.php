<?php
$main_page = "View project";
$page = "View project";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <?php include '../header_links.php'; ?>
        </head>

        <body onload="startTime()">
            <div class="container-scroller">
                <?php include './top_menu.php'; ?>
                <!-- partial -->
                <div class="container-fluid page-body-wrapper">
                    <?php include './side_menu.php'; ?>
                    <!-- partial -->
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Active projects</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $aprojects_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Active", "project_name" => "", "project_location" => "", "user_role" => $user_role_id);
                                                $aprojects_recv_data = json_encode($aprojects_data);
                                                $getausersData = json_decode(callAPI($aprojects_recv_data, $project_details_api));
                                                $ret_aprojects_error = $getausersData->error;
                                                $ret_aprojects_message = $getausersData->message;
                                                $ret_aprojects_data = $getausersData->data;

                                                if ($ret_aprojects_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Project location</th>
                                                                <th style="font-weight: bolder;">Project start date</th>
                                                                <th style="font-weight: bolder;">Project end date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Project location</th>
                                                                <th style="font-weight: bolder;">Project start date</th>
                                                                <th style="font-weight: bolder;">Project end date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_aprojects_data as $dataVal) {
                                                                $ret_aprojects_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_aprojects_detail as $ret_aprojects_details) {
                                                                $aproject_id = $ret_aprojects_details->project_id;
                                                                $aproject_name = $ret_aprojects_details->project_name;
                                                                $aproject_location = $ret_aprojects_details->project_location;
                                                                $aproject_start_date = date("d/m/Y", strtotime($ret_aprojects_details->project_start_date));
                                                                $aproject_end_date = $ret_aprojects_details->project_end_date;
                                                                if ($aproject_end_date == "") {
                                                                    $aproject_end_date = "N/A";
                                                                } else {
                                                                    $aproject_end_date = date("d/m/Y", strtotime($aproject_end_date));
                                                                }

                                                                $aprojects_info_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                    "project_id" => $aproject_id, "status" => "");
                                                                $aprojects_info_recv_data = json_encode($aprojects_info_data);
                                                                $getaproject_infoData = json_decode(callAPI($aprojects_info_recv_data, $project_info_detail_api));
                                                                $ret_aprojects_info_error = $getaproject_infoData->error;
                                                                $ret_aprojects_info_message = $getaproject_infoData->message;
                                                                $ret_aprojects_info_data = $getaproject_infoData->data;

                                                                if ($ret_aprojects_error == true) {
                                                                    $ausersErr = $ret_aprojects_status;
                                                                } else {
                                                                    foreach ($ret_aprojects_info_data as $dataVal) {
                                                                        $project_infoRecords = $dataVal->Records;
                                                                    }
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $aproject_name; ?></td>
                                                                    <td><?php echo $aproject_location; ?></td>
                                                                    <td><?php echo $aproject_start_date; ?></td>
                                                                    <td><?php echo $aproject_end_date; ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Inactive projects</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $iprojects_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Inactive", "project_name" => "", "project_location" => "", "user_role" => $user_role_id);
                                                $iprojects_recv_data = json_encode($iprojects_data);
                                                $getiusersData = json_decode(callAPI($iprojects_recv_data, $project_details_api));
                                                $ret_iprojects_error = $getiusersData->error;
                                                $ret_iprojects_message = $getiusersData->message;
                                                $ret_iprojects_data = $getiusersData->data;

                                                if ($ret_iprojects_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Project location</th>
                                                                <th style="font-weight: bolder;">Project start date</th>
                                                                <th style="font-weight: bolder;">Project end date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" colspan="4">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Project location</th>
                                                                <th style="font-weight: bolder;">Project start date</th>
                                                                <th style="font-weight: bolder;">Project end date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_iprojects_data as $dataVal) {
                                                                $ret_iprojects_detail = $dataVal->Details;
                                                            }
                                                            $islno = 0;
                                                            foreach ($ret_iprojects_detail as $ret_iprojects_details) {
                                                                $islno++;
                                                                $iproject_id = $ret_iprojects_details->project_id;
                                                                $iproject_name = $ret_iprojects_details->project_name;
                                                                $iproject_location = $ret_iprojects_details->project_location;
                                                                $iproject_start_date = date("d/m/Y", strtotime($ret_iprojects_details->project_start_date));
                                                                $iproject_end_date = $ret_iprojects_details->project_end_date;
                                                                if ($iproject_end_date == "") {
                                                                    $iproject_end_date = "N/A";
                                                                } else {
                                                                    $iproject_end_date = date("d/m/Y", strtotime($iproject_end_date));
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $islno; ?></td>
                                                                    <td><?php echo $iproject_name; ?></td>
                                                                    <td><?php echo $iproject_location; ?></td>
                                                                    <td><?php echo $iproject_start_date; ?></td>
                                                                    <td><?php echo $iproject_end_date; ?></td>                                                                    
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content-wrapper ends -->
                        <?php
                        include '../footer.php';
                        ?>
                    </div>
                    <!-- main-panel ends -->
                </div>
                <!-- page-body-wrapper ends -->
            </div>
            <!-- container-scroller -->
            <?php
            include '../footer_links.php';
            ?>
        </body>
    </html>
    <?php
} else {
    header("location:../index.html");
}
?>