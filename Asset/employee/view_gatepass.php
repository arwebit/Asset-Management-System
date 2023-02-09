<?php
$main_page = "View gatepass";
$page = "View gatepass";
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
                                            <h4 class="card-title">Active gatepass</h4>
                                            <div class="table-responsive">
                                                <?php
                                                $agp_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Active");
                                                $agp_recv_data = json_encode($agp_data);
                                                $getausersData = json_decode(callAPI($agp_recv_data, $all_gatepass_details_user_api));
                                                $ret_agp_error = $getausersData->error;
                                                $ret_agp_message = $getausersData->message;
                                                $ret_agp_data = $getausersData->data;

                                                if ($ret_agp_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">ID</th>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Location</th>
                                                                <th style="font-weight: bolder;">Period</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="5" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">ID</th>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Location</th>
                                                                <th style="font-weight: bolder;">Period</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_agp_data as $dataVal) {
                                                                $ret_agp_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_agp_detail as $ret_agp_details) {
                                                                $aproject_name = $ret_agp_details->project_name;
                                                                $aproject_location = $ret_agp_details->project_location;
                                                                $agatepass_date = date("d/m/Y", strtotime($ret_agp_details->gatepass_start_date)) . " to " . date("d/m/Y", strtotime($ret_agp_details->gatepass_end_date));
                                                                $agp_id = $ret_agp_details->gatepass_id;
                                                                $agatepass_link_id = $ret_agp_details->gatepass_link_id;
                                                                $agatepass_status = $ret_agp_details->gatepass_status;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $agp_id; ?></td>
                                                                    <td><?php echo $aproject_name; ?> </td>
                                                                    <td><?php echo $aproject_location; ?> </td>
                                                                    <td><?php echo $agatepass_date; ?> </td>
                                                                    <td align="center">
                                                                        <?php
                                                                        if ($agatepass_status != 2) {
                                                                            ?>
                                                                            <a href="../gen_gp_pdf.php?gatepass_id=<?php echo $agp_id; ?>">
                                                                                <button type="button" class="btn btn-info btn-rounded btn-sm" id="download" value="download">
                                                                                    <span>Download</span></button>
                                                                            </a><br/><br/>
                                                                            <a href="view_gatepass_details.php?gatepass_id=<?php echo $agp_id; ?>">
                                                                                <button type="button" class="btn btn-warning btn-rounded btn-sm" id="view" value="download">
                                                                                    <span>View</span></button>
                                                                            </a>

                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                <span class="badge badge-warning text-white">Waiting for approval</span>
                                                                <?php
                                                            }
                                                            ?>
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
                                            <h4 class="card-title">Expired Gatepass</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $igp_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Inactive");
                                                $igp_recv_data = json_encode($igp_data);
                                                $getiusersData = json_decode(callAPI($igp_recv_data, $all_gatepass_details_user_api));
                                                $ret_igp_error = $getiusersData->error;
                                                $ret_igp_message = $getiusersData->message;
                                                $ret_igp_data = $getiusersData->data;

                                                if ($ret_igp_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">ID</th>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Location</th>
                                                                <th style="font-weight: bolder;">Period</th>
                                                                <th style="font-weight: bolder;">Status</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="5" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">ID</th>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Location</th>
                                                                <th style="font-weight: bolder;">Period</th>
                                                                <th style="font-weight: bolder;">Status</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_igp_data as $dataVal) {
                                                                $ret_igp_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_igp_detail as $ret_igp_details) {
                                                                $iassigner_name = $ret_igp_details->member_first_name . " " . $ret_igp_details->member_middle_name . " " . $ret_igp_details->member_last_name;
                                                                $iproject_name = $ret_igp_details->project_name;
                                                                $iproject_location = $ret_igp_details->project_location;
                                                                $igp_id = $ret_igp_details->gatepass_id;
                                                                $igatepass_date = date("d/m/Y", strtotime($ret_igp_details->gatepass_start_date)) . " to " . date("d/m/Y", strtotime($ret_igp_details->gatepass_end_date));
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $igp_id; ?></td>
                                                                    <td><?php echo $iproject_name; ?></td>
                                                                    <td><?php echo $iproject_location; ?></td>
                                                                    <td><?php echo $igatepass_date; ?></td>
                                                                    <td align="center">
                                                                        <span class="badge badge-info text-white">The gatepass is expired</span>
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

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Rejected Gatepass</h4>
                                            <div class="table-responsive">
                                                <?php
                                                $rgp_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Rejected");
                                                $rgp_recv_data = json_encode($rgp_data);
                                                $getrusersData = json_decode(callAPI($rgp_recv_data, $all_gatepass_details_user_api));
                                                $ret_rgp_error = $getrusersData->error;
                                                $ret_rgp_message = $getrusersData->message;
                                                $ret_rgp_data = $getrusersData->data;

                                                if ($ret_rgp_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">ID</th>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Location</th>
                                                                <th style="font-weight: bolder;">Period</th>
                                                                <th style="font-weight: bolder;">Status</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="5" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">ID</th>
                                                                <th style="font-weight: bolder;">Project</th>
                                                                <th style="font-weight: bolder;">Location</th>
                                                                <th style="font-weight: bolder;">Period</th>
                                                                <th style="font-weight: bolder;">Status</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_rgp_data as $dataVal) {
                                                                $ret_rgp_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_rgp_detail as $ret_rgp_details) {
                                                                $rslno++;
                                                                $rassigner_name = $ret_rgp_details->member_first_name . " " . $ret_rgp_details->member_middle_name . " " . $ret_rgp_details->member_last_name;
                                                                $rproject_name = $ret_rgp_details->project_name;
                                                                $rproject_location = $ret_rgp_details->project_location;
                                                                $rgp_id = $ret_rgp_details->gatepass_id;
                                                                $rgatepass_date = date("d/m/Y", strtotime($ret_rgp_details->gatepass_start_date)) . " to " . date("d/m/Y", strtotime($ret_rgp_details->gatepass_end_date));
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $rgp_id; ?></td>
                                                                    <td><?php echo $rproject_name; ?></td>
                                                                    <td><?php echo $rproject_location; ?></td>
                                                                    <td><?php echo $rgatepass_date; ?></td>
                                                                    <td align="center">
                                                                        <span class="badge badge-danger text-white">The gatepass is rejected</span>
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