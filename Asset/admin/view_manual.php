<?php
$main_page = "View manual";
$page = "View manual";
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
                                            <h4 class="card-title">Active manuals</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $amanuals_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Active", "project_id" => "", "manual_title" => "");
                                                $amanuals_recv_data = json_encode($amanuals_data);
                                                $getausersData = json_decode(callAPI($amanuals_recv_data, $manual_details_api));
                                                $ret_amanuals_error = $getausersData->error;
                                                $ret_amanuals_message = $getausersData->message;
                                                $ret_amanuals_data = $getausersData->data;

                                                if ($ret_amanuals_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Manual title</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" colspan="3">No record found</td>
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
                                                                <th style="font-weight: bolder;">Manual title</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_amanuals_data as $dataVal) {
                                                                $ret_amanuals_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_amanuals_detail as $ret_amanuals_details) {
                                                                $amanual_id = $ret_amanuals_details->manual_id;
                                                                $aproject_name = $ret_amanuals_details->project_name;
                                                                $amanual_title = $ret_amanuals_details->manual_title;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $aproject_name; ?></td>
                                                                    <td><?php echo $amanual_title; ?></td>
                                                                    <td align="center">
                                                                        <a href="edit_manual.php?manual_id=<?php echo $amanual_id; ?>">
                                                                            <button type="button" class="btn btn-info btn-rounded btn-sm" id="edit_manual">
                                                                                <span>Edit</span></button>
                                                                        </a><br/><br/>
                                                                        <button type="button" class="btn btn-danger btn-rounded btn-sm" id="inactive_active" value="Inactive"
                                                                                onclick="change_status(this.value, '<?php echo $amanual_id; ?>');">
                                                                            <span>Inactive</span></button><br/><br/>
                                                                        <a href="manual_details.php?manual_id=<?php echo $amanual_id; ?>">
                                                                            <button type="button" class="btn btn-warning btn-rounded btn-sm" id="edit_manual">
                                                                                <span>View details</span></button>
                                                                        </a>
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
                                            <h4 class="card-title">Inactive manuals</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $imanuals_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Inactive", "project_id" => "", "manual_title" => "");
                                                $imanuals_recv_data = json_encode($imanuals_data);
                                                $getiusersData = json_decode(callAPI($imanuals_recv_data, $manual_details_api));
                                                $ret_imanuals_error = $getiusersData->error;
                                                $ret_imanuals_message = $getiusersData->message;
                                                $ret_imanuals_data = $getiusersData->data;

                                                if ($ret_imanuals_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Manual title</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" colspan="3">No record found</td>
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
                                                                <th style="font-weight: bolder;">Manual title</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_imanuals_data as $dataVal) {
                                                                $ret_imanuals_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_imanuals_detail as $ret_imanuals_details) {
                                                                $imanual_id = $ret_imanuals_details->manual_id;
                                                                $iproject_name = $ret_imanuals_details->project_name;
                                                                $imanual_title = $ret_imanuals_details->manual_title;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $iproject_name; ?></td>
                                                                    <td><?php echo $imanual_title; ?></td>
                                                                    <td align="center">
                                                                        <button type="button" class="btn btn-info btn-rounded btn-sm" id="inactive_active" value="Active"
                                                                                onclick="change_status(this.value, '<?php echo $imanual_id; ?>');">
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

                function change_status(manual_status, manual_id) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";

                    var values = [{session_token: login_token, session_user: login_user, manual_id: manual_id, status: manual_status}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/manual/availability.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#success_message").text(RetVal.data);
                                window.location.href = "view_manual.php";
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