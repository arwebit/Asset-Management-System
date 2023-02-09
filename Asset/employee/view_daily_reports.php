<?php
$main_page = "View daily reports";
$page = "View daily reports";
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
                                            <h4 class="card-title"><?php echo $main_page; ?></h4>
                                            <div class="table-responsive">

                                                <?php
                                                $agp_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "role_id" => $user_role_id);
                                                $agp_recv_data = json_encode($agp_data);
                                                $getausersData = json_decode(callAPI($agp_recv_data, $all_daily_report_api));
                                                $ret_agp_error = $getausersData->error;
                                                $ret_agp_message = $getausersData->message;
                                                $ret_agp_data = $getausersData->data;

                                                if ($ret_agp_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Gatepass ID</th>
                                                                <th style="font-weight: bolder;">Gatepass date</th>
                                                                <th style="font-weight: bolder;">Status</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" colspan="5">No record found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Gatepass ID</th>
                                                                <th style="font-weight: bolder;">Gatepass date</th>
                                                                <th style="font-weight: bolder;">Status</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_agp_data as $dataVal) {
                                                                $ret_agp_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_agp_detail as $ret_agp_details) {
                                                                $agatepass_link_id = $ret_agp_details->gatepass_link_id;
                                                                $agatepass_id = $ret_agp_details->gatepass_id;
                                                                $adaily_report_id = $ret_agp_details->daily_report_id;
                                                                $aproject_name = $ret_agp_details->project_name;
                                                                $agatepass_date = $ret_agp_details->gatepass_date;
                                                                $agatepass_attendence = $ret_agp_details->gatepass_attendence;
                                                                $aproject_end_date = $ret_agp_details->project_end_date;
                                                                $areport_status = $ret_agp_details->report_status;
                                                                if ($aproject_end_date == "") {
                                                                    $aproject_end_date = "N/A";
                                                                } else {
                                                                    $aproject_end_date = date("d/m/Y", strtotime($aproject_end_date));
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $aproject_name; ?> </td>
                                                                    <td><?php echo $agatepass_id; ?></td>
                                                                    <td><?php echo date("d/m/Y", strtotime($agatepass_date)); ?></td>
                                                                    <td align="center">
                                                                        <?php
                                                                        if ($areport_status == 2) {
                                                                            $badge_status = "warning";
                                                                            $status = "Submitted for verification";
                                                                        } else if ($areport_status == 3) {
                                                                            $badge_status = "danger";
                                                                            $status = "Denied";
                                                                        } else if (($areport_status == "") || $areport_status == 0) {
                                                                            $badge_status = "info";
                                                                            $status = "Pending";
                                                                        } else {
                                                                            $badge_status = "success";
                                                                            $status = "Approved";
                                                                        }
                                                                        ?>
                                                                        <span class="text-white badge badge-<?php echo $badge_status; ?>">
                                                                            <?php echo $status; ?>
                                                                        </span>
                                                                    </td>

                                                                    <td align="center">
                                                                        <?php
                                                                        if (date("Y-m-d", strtotime($agatepass_date)) >= date("Y-m-d", strtotime(curr_date_time()))) {
                                                                            if ($agatepass_attendence == "1") {
                                                                                if ($areport_status == "") {
                                                                                    ?>
                                                                                    <a href="report_entry.php?gatepass_link_id=<?php echo $agatepass_link_id; ?>">
                                                                                        <button class="btn btn-primary btn-rounded btn-sm" id="entry_report">
                                                                                            <span>Entry report</span>
                                                                                        </button>
                                                                                    </a>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <a href="report_edit.php?daily_report_id=<?php echo $adaily_report_id; ?>">
                                                                                        <button class="btn btn-primary btn-rounded btn-sm" id="edit_report">
                                                                                            <span>Edit report</span>
                                                                                        </button>
                                                                                    </a><br/><br/>
                                                                                    <a href="report_media.php?daily_report_id=<?php echo $adaily_report_id; ?>">
                                                                                        <button class="btn btn-success btn-rounded btn-sm" id="media">
                                                                                            <span>Manage media</span>
                                                                                        </button>
                                                                                    </a><br/><br/>
                                                                                    <a href="view_daily_report_details.php?daily_report_id=<?php echo $adaily_report_id; ?>">
                                                                                        <button class="btn btn-info btn-rounded btn-sm" id="view_report">
                                                                                            <span>View details</span>
                                                                                        </button>
                                                                                    </a>
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <span class="text-white badge badge-warning">
                                                                                    Attendance not given
                                                                                </span>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <span class="text-white badge badge-danger">
                                                                                Expired
                                                                            </span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
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