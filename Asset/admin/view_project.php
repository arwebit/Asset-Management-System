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
                                                                <th style="font-weight: bolder;">Project location and address</th>
                                                                <th style="font-weight: bolder;">Project Period</th>
                                                                <th style="font-weight: bolder;">Project scope</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                                <th style="font-weight: bolder;">Assign</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="6" align="center">No records found</td>
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
                                                                <th style="font-weight: bolder;">Project location and address</th>
                                                                <th style="font-weight: bolder;">Project Period</th>
                                                                <th style="font-weight: bolder;">Project scope</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                                <th style="font-weight: bolder;">Assign</th>
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
                                                                $aproject_scope = $ret_aprojects_details->project_scope;
                                                                $aproject_address = $ret_aprojects_details->project_address;
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
                                                                    <td>Location : <?php echo $aproject_location; ?><br/><br/> Address : <?php echo $aproject_address; ?></td>
                                                                    <td><?php echo $aproject_start_date; ?> to <?php echo $aproject_end_date; ?></td>
                                                                    <td><?php echo $aproject_scope; ?></td>
                                                                    <td align="center">

                                                                        <a href="edit_project.php?project_id=<?php echo $aproject_id; ?>">
                                                                            <button type="button" class="btn btn-info btn-rounded btn-sm" id="edit_project">
                                                                                <span>Edit</span></button>
                                                                        </a><br /><br />
                                                                        <button type="button" class="btn btn-danger btn-rounded btn-sm" id="inactive_active" value="Inactive"
                                                                                onclick="change_status(this.value, '<?php echo $aproject_id; ?>');">
                                                                            <span>Inactive</span></button>
                                                                    </td>
                                                                    <td  align="center">
                                                                        <a href="view_project_supervisor.php?project_id=<?php echo $aproject_id; ?>">
                                                                            <button type="button" class="btn btn-warning btn-rounded btn-sm" id="project_super">
                                                                                <span>Supervisor assign</span></button>
                                                                        </a><br /><br />
                                                                        <?php
                                                                        if ($project_infoRecords > 0) {
                                                                            ?>
                                                                            <a href="assign_project.php?project_id=<?php echo $aproject_id; ?>">
                                                                                <button type="button" class="btn btn-primary btn-rounded btn-sm" id="project_super">
                                                                                    <span>Employee assign</span></button>
                                                                            </a>
                                                                            <?php
                                                                        } else {
                                                                            echo "";
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
                                                                <th style="font-weight: bolder;">Project location and address</th>
                                                                <th style="font-weight: bolder;">Project Period</th>
                                                                <th style="font-weight: bolder;">Project scope</th>
                                                                <th style="font-weight: bolder;">Option</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" colspan="5">No records found</td>
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
                                                                <th style="font-weight: bolder;">Project location and address</th>
                                                                <th style="font-weight: bolder;">Project Period</th>
                                                                <th style="font-weight: bolder;">Project scope</th>
                                                                <th style="font-weight: bolder;">Option</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_iprojects_data as $dataVal) {
                                                                $ret_iprojects_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_iprojects_detail as $ret_iprojects_details) {
                                                                $iproject_id = $ret_iprojects_details->project_id;
                                                                $iproject_name = $ret_iprojects_details->project_name;
                                                                $iproject_location = $ret_iprojects_details->project_location;
                                                                $iproject_scope = $ret_iprojects_details->project_scope;
                                                                $iproject_address = $ret_iprojects_details->project_address;
                                                                $iproject_start_date = date("d/m/Y", strtotime($ret_iprojects_details->project_start_date));
                                                                $iproject_end_date = $ret_iprojects_details->project_end_date;
                                                                if ($iproject_end_date == "") {
                                                                    $iproject_end_date = "N/A";
                                                                } else {
                                                                    $iproject_end_date = date("d/m/Y", strtotime($iproject_end_date));
                                                                }
                                                                ?>
                                                                <tr>
                                                                   <td><?php echo $iproject_name; ?></td>
                                                                    <td>Location : <?php echo $iproject_location; ?><br/><br/> Address : <?php echo $iproject_address; ?></td>
                                                                    <td><?php echo $iproject_start_date; ?> to <?php echo $iproject_end_date; ?></td>
                                                                    <td><?php echo $iproject_scope; ?></td>
                                                                    <td align="center">
                                                                        <button type="button" class="btn btn-info btn-rounded btn-sm" id="inactive_active" value="Active"
                                                                                onclick="change_status(this.value, '<?php echo $iproject_id; ?>');">
                                                                            <span>Active</span></button>

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
            <script type="text/javascript">

                function change_status(project_status, project_id) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";

                    var values = [{session_token: login_token, session_user: login_user, project_id: project_id, status: project_status}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/project/availability.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#success_message").text(RetVal.data);
                                window.location.href = "view_project.php";
                            } else {
                                alert(RetVal.message);
                            }
                        }
                    });
                }
            </script>
        </body>

    </html>
    <?php
} else {
    header("location:../index.html");
}
?>