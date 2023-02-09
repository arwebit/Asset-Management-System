<?php
$main_page = "View user";
$page = "View user";
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
                                            <h4 class="card-title">Active users</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $ausers_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Active", "first_name" => "", "mobile" => "", "email" => "",
                                                    "role_id_from" => "3", "role_id_to" => "3");
                                                $ausers_recv_data = json_encode($ausers_data);
                                                $getausersData = json_decode(callAPI($ausers_recv_data, $all_user_details_api));
                                                $ret_ausers_error = $getausersData->error;
                                                $ret_ausers_message = $getausersData->message;
                                                $ret_ausers_data = $getausersData->data;

                                                if ($ret_ausers_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Name</th>
                                                                <th style="font-weight: bolder;">Employee code</th>
                                                                <th style="font-weight: bolder;">Username</th>
                                                                <th style="font-weight: bolder;">Email</th>
                                                                <th style="font-weight: bolder;">Mobile</th>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Role</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="8" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Name</th>
                                                                 <th style="font-weight: bolder;">Employee code</th>
                                                                <th style="font-weight: bolder;">Username</th>
                                                                <th style="font-weight: bolder;">Email</th>
                                                                <th style="font-weight: bolder;">Mobile</th>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Role</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_ausers_data as $dataVal) {
                                                                $ret_ausers_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_ausers_detail as $ret_ausers_details) {
                                                                $aemp_code = $ret_ausers_details->emp_code;
                                                                $ausers_name = $ret_ausers_details->username;
                                                                $amem_name = $ret_ausers_details->member_first_name . " " . $ret_ausers_details->member_middle_name . " " . $ret_ausers_details->member_last_name;
                                                                $aemail = $ret_ausers_details->member_email;
                                                                $amobile = $ret_ausers_details->member_mobile;
                                                                $arole_name = $ret_ausers_details->role_name;
                                                                $acategory_name = $ret_ausers_details->category_name;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $amem_name; ?></td>
                                                                    <td><?php echo $aemp_code; ?></td>
                                                                     <td><?php echo $ausers_name; ?></td>
                                                                    <td><?php echo $aemail; ?></td>
                                                                    <td><?php echo $amobile; ?></td>
                                                                    <td><?php echo $acategory_name; ?></td>
                                                                    <td><?php echo $arole_name; ?></td>
                                                                    <td align="center">
                                                                        <button type="button" class="btn btn-danger btn-rounded btn-sm" id="inactive_active" value="Inactive"
                                                                                onclick="change_status(this.value, '<?php echo $ausers_name; ?>');">
                                                                            <span>Inactive</span></button>
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
                                            <h4 class="card-title">Inactive users</h4>
                                            <div class="table-responsive">
                                                <?php
                                                $iusers_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Inactive", "first_name" => "", "mobile" => "", "email" => "",
                                                    "role_id_from" => "3", "role_id_to" => "3");
                                                $iusers_recv_data = json_encode($iusers_data);
                                                $getiusersData = json_decode(callAPI($iusers_recv_data, $all_user_details_api));
                                                $ret_iusers_error = $getiusersData->error;
                                                $ret_iusers_message = $getiusersData->message;
                                                $ret_iusers_data = $getiusersData->data;

                                                if ($ret_iusers_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Name</th>
                                                                <th style="font-weight: bolder;">Employee code</th>
                                                                <th style="font-weight: bolder;">Username</th>
                                                                <th style="font-weight: bolder;">Email</th>
                                                                <th style="font-weight: bolder;">Mobile</th>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Role</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="8" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Name</th>
                                                                <th style="font-weight: bolder;">Employee code</th>
                                                                <th style="font-weight: bolder;">Username</th>
                                                                <th style="font-weight: bolder;">Email</th>
                                                                <th style="font-weight: bolder;">Mobile</th>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Role</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_iusers_data as $dataVal) {
                                                                $ret_iusers_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_iusers_detail as $ret_iusers_details) {
                                                                $iemp_code = $ret_iusers_details->emp_code;
                                                                $iusers_name = $ret_iusers_details->username;
                                                                $imem_name = $ret_iusers_details->member_first_name . " " . $ret_iusers_details->member_middle_name . " " . $ret_iusers_details->member_last_name;
                                                                $iemail = $ret_iusers_details->member_email;
                                                                $imobile = $ret_iusers_details->member_mobile;
                                                                $icategory_name = $ret_iusers_details->category_name;
                                                                $irole_name = $ret_iusers_details->role_name;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $imem_name; ?></td>
                                                                    <td><?php echo $iemp_code; ?></td>
                                                                    <td><?php echo $iusers_name; ?></td>
                                                                    <td><?php echo $iemail; ?></td>
                                                                    <td><?php echo $imobile; ?></td>
                                                                    <td><?php echo $icategory_name; ?></td>
                                                                    <td><?php echo $irole_name; ?></td>
                                                                    <td align="center">
                                                                        <button type="button" class="btn btn-info btn-rounded btn-sm" id="inactive_active" value="Active"
                                                                                onclick="change_status(this.value, '<?php echo $iusers_name; ?>');">
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

                function change_status(user_status, username) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";

                    var values = [{session_token: login_token, session_user: login_user, user_name: username, status: user_status}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/common/user/availability.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#success_message").text(RetVal.data);
                                window.location.href = "view_user.php";
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