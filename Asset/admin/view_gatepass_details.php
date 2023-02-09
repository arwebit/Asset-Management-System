<?php
$main_page = "Gatepass details";
$page = "Gatepass details";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['gatepass_id']) {
        $gatepass_id = $_REQUEST['gatepass_id'];
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
                                                <h4 class="card-title">Gatepass ID :
                                                    <?php echo $gatepass_id; ?></h4>
                                                <div class="table-responsive">

                                                    <?php
                                                    $agp_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                        "gatepass_id" => $gatepass_id, "gp_date" => "");
                                                    $agp_recv_data = json_encode($agp_data);
                                                    $getausersData = json_decode(callAPI($agp_recv_data, $selected_gatepass_api));
                                                    $ret_agp_error = $getausersData->error;
                                                    $ret_agp_message = $getausersData->message;
                                                    $ret_agp_data = $getausersData->data;

                                                    if ($ret_agp_error == true) {
                                                        ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th style="font-weight: bolder;">Gatepass date</th>
                                                                    <th style="font-weight: bolder;">Login time</th>
                                                                    <th style="font-weight: bolder;">Logout time</th>
                                                                    <th style="font-weight: bolder;">Attendence / Login and Logout</th>
                                                                    <th style="font-weight: bolder;">Action</th>
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
                                                                    <th style="font-weight: bolder;">Gatepass date</th>
                                                                    <th style="font-weight: bolder;">Login time</th>
                                                                    <th style="font-weight: bolder;">Logout time</th>
                                                                    <th style="font-weight: bolder;">Attendence / Login and Logout</th>
                                                                    <th style="font-weight: bolder;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($ret_agp_data as $dataVal) {
                                                                    $ret_agp_detail = $dataVal->Details;
                                                                }
                                                                foreach ($ret_agp_detail as $ret_agp_details) {
                                                                    $agp_link_id = $ret_agp_details->gatepass_link_id;
                                                                    $agatepass_date = date("d/m/Y", strtotime($ret_agp_details->gatepass_date));
                                                                    $agatepass_attendence = $ret_agp_details->gatepass_attendence;
                                                                    $alogin_logout_status = $ret_agp_details->login_logout_status;
                                                                    $alogin_time = $ret_agp_details->login_time;
                                                                    $alogout_time = $ret_agp_details->logout_time;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $agatepass_date; ?> </td>
                                                                        <td><?php echo $alogin_time; ?> </td>
                                                                        <td><?php echo $alogout_time; ?> </td>
                                                                        <td align="center">
                                                                            <?php
                                                                            if (date("Y-m-d", strtotime($ret_agp_details->gatepass_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
                                                                                if ($agatepass_attendence == 1) {
                                                                                    ?>
                                                                                    <span class="text-white badge badge-success">Present</span><br/><br/>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <span class="text-white badge badge-danger">Absent</span><br/><br/>
                                                                                    <?php
                                                                                }
                                                                                if ($alogin_logout_status == 1) {
                                                                                    ?>
                                                                                    <span class="text-white badge badge-success">Logged in </span>
                                                                                    <?php
                                                                                } else {
                                                                                    if ($alogout_time == null) {
                                                                                        ?>
                                                                                        <span class="text-white badge badge-info">Not in work</span>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <span class="text-white badge badge-danger">Logged out</span>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <span class="text-white badge badge-warning">Upcoming</span> 
                                                                                <?php
                                                                            }
                                                                            ?> </td>
                                                                        <td align="center">
                                                                            <?php
                                                                            if (date("Y-m-d", strtotime($ret_agp_details->gatepass_date)) < date("Y-m-d", strtotime(curr_date_time()))) {
                                                                                ?>
                                                                                <span class="text-white badge badge-danger">Expired</span>
                                                                                <?php
                                                                            } else if (date("Y-m-d", strtotime($ret_agp_details->gatepass_date)) > date("Y-m-d", strtotime(curr_date_time()))) {
                                                                                ?>
                                                                                <span class="text-white badge badge-warning">Upcoming</span> 
                                                                                <?php
                                                                            } else {
                                                                                if ($agatepass_attendence == 1) {
                                                                                    $atttendence_value = "Inactive";
                                                                                    $att_val = "Mark as absent";
                                                                                    $att_btn_class = "btn-danger";
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-sm btn-rounded <?php echo $att_btn_class; ?>" id="gp_attendence" value="<?php echo $atttendence_value; ?>"
                                                                                            onclick="gatepass_attendence(this.value, '<?php echo $agp_link_id; ?>');">
                                                                                        <span><?php echo $att_val; ?></span></button><br/><br/>
                                                                                    <?php
                                                                                } else {
                                                                                    $atttendence_value = "Active";
                                                                                    $att_val = "Mark as present";
                                                                                    $att_btn_class = "btn-success";
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-sm btn-rounded <?php echo $att_btn_class; ?>" id="gp_attendence" value="<?php echo $atttendence_value; ?>"
                                                                                            onclick="gatepass_attendence(this.value, '<?php echo $agp_link_id; ?>');">
                                                                                        <span><?php echo $att_val; ?></span></button><br/><br/>
                                                                                    <?php
                                                                                }

                                                                                if ($alogin_logout_status == 1) {
                                                                                    $login_logout_value = "Logout";
                                                                                    $log_toogle_val = "Logout";
                                                                                    $log_btn_class = "btn-danger";
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-sm btn-rounded <?php echo $log_btn_class; ?>" id="gp_login_logout" value="<?php echo $login_logout_value; ?>"
                                                                                            onclick="gatepass_login_logout(this.value, '<?php echo $agp_link_id; ?>');">
                                                                                        <span><?php echo $log_toogle_val; ?></span></button><br/><br/>
                                                                                    <?php
                                                                                } else {
                                                                                    if (($alogin_logout_status == 0) && ($alogout_time == null)) {
                                                                                        $login_logout_value = "Login";
                                                                                        $log_toogle_val = "Login";
                                                                                        $log_btn_class = "btn-success";
                                                                                        ?>
                                                                                        <button type="button" class="btn btn-sm btn-rounded <?php echo $log_btn_class; ?>" id="gp_login_logout" value="<?php echo $login_logout_value; ?>"
                                                                                                onclick="gatepass_login_logout(this.value, '<?php echo $agp_link_id; ?>');">
                                                                                            <span><?php echo $log_toogle_val; ?></span></button><br/><br/>
                                                                                        <?php
                                                                                    }
                                                                                }
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
                <script type="text/javascript">

                    function gatepass_attendence(gp_attendence, gatepass_link_id) {
                        var login_user = "<?php echo $login_user; ?>";
                        var login_token = "<?php echo $login_token; ?>";

                        var values = [{session_token: login_token, session_user: login_user, gatepass_link_id: gatepass_link_id, status: gp_attendence}];

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>/api/employee/gatepass/attendence.php",
                            dataType: "json",
                            data: JSON.stringify(values),
                            success: function (RetVal) {
                                if (RetVal.message === "Success") {
                                    $("#success_message").text(RetVal.data);
                                    window.location.href = "view_gatepass_details.php?gatepass_id=<?php echo $gatepass_id; ?>";
                                } else {
                                    alert(RetVal.message);
                                }
                            }
                        });
                    }

                    function gatepass_login_logout(gp_login_logout, gatepass_link_id) {
                        var login_user = "<?php echo $login_user; ?>";
                        var login_token = "<?php echo $login_token; ?>";
                        var conf=confirm("Are you sure?");
if(conf){
                        var values = [{session_token: login_token, session_user: login_user, gatepass_link_id: gatepass_link_id, status: gp_login_logout}];

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>/api/employee/gatepass/login_logout.php",
                            dataType: "json",
                            data: JSON.stringify(values),
                            success: function (RetVal) {
                                if (RetVal.message === "Success") {
                                    $("#success_message").text(RetVal.data);
                                    window.location.href = "view_gatepass_details.php?gatepass_id=<?php echo $gatepass_id; ?>";
                                } else {
                                    alert(RetVal.message);
                                }
                            }
                        });
                    }
                    }
                </script>
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>