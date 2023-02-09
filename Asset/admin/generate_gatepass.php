<?php
$main_page = "Generate gatepass";
$page = "Generate gatepass";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if (isset($_REQUEST['gen_gp'])) {
        $gp_username = trim($_REQUEST['username']);
        $gp_project_id = trim($_REQUEST['project_id']);
        $gatepass_start_date = trim($_REQUEST['gatepass_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['gatepass_start_date'])));
        $gatepass_end_date = trim($_REQUEST['gatepass_end_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['gatepass_end_date'])));

        $gengp_data[] = array("session_token" => $login_token, "session_user" => $login_user, "username" => $gp_username, "project_id" => $gp_project_id,
            "gatepass_start_date" => $gatepass_start_date, "gatepass_end_date" => $gatepass_end_date, "status" => "1");
        $cu_data = json_encode($gengp_data);
        $gengp_getData = json_decode(callAPI($cu_data, $gatepass_create_api));
        $gengp_error = $gengp_getData->error;
        $gengp_message = $gengp_getData->message;
        $gengp_data = $gengp_getData->data;

        if ($gengp_error == true) {
            if ($gengp_message == "Server error") {
                foreach ($gengp_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($gengp_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $userErr = $dataVal->UsernameErr;
                    $projectErr = $dataVal->ProjectErr;
                    $gatepass_start_dateErr = $dataVal->GatepassStartDateErr;
                    $gatepass_end_dateErr = $dataVal->GatepassEndDateErr;
                }
            }
        } else {
            foreach ($gengp_data as $dataVal) {
                $pdf_str = $dataVal->PDFString;
                $gatepass_id = $dataVal->GatepassID;
            }

            $successMsg = "Successfully generated gatepass";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <!-- Required meta tags -->
            <?php
            include '../header_links.php';
            ?>
        </head>

        <body onload="startTime()">
            <div class="container-scroller">
                <?php include './top_menu.php'; ?>
                <!-- partial -->
                <div class="container-fluid page-body-wrapper">
                    <!-- partial:../partials/_settings-panel.html -->
                    <?php include './side_menu.php'; ?>

                    <!-- partial -->
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title"><?php echo $main_page; ?></h4>
                                            <form class="forms-sample" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="user">User</label> <b class="text-danger"> *</b>
                                                            <select class="form-control select2" id="username" name="username">
                                                                <option value="">SELECT USER</option>
                                                                <?php
                                                                $user_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                    "status" => "Active", "first_name" => "", "mobile" => "", "email" => "",
                                                                    "role_id_from" => "3", "role_id_to" => "3");

                                                                $user_recv_data = json_encode($user_data);
                                                                $getuserData = json_decode(callAPI($user_recv_data, $all_user_details_api));
                                                                $ret_user_error = $getuserData->error;
                                                                $ret_user_message = $getuserData->message;
                                                                $ret_user_data = $getuserData->data;

                                                                if ($ret_user_error == true) {
                                                                    //   $roleErr = $user_message;
                                                                } else {
                                                                    foreach ($ret_user_data as $dataVal) {
                                                                        $user_detail = $dataVal->Details;
                                                                    }
                                                                    foreach ($user_detail as $user_details) {
                                                                        $user_name = $user_details->username;
                                                                        $user_full_name = $user_details->member_first_name . " " . $user_details->member_middle_name . " " . $user_details->member_last_name;
                                                                        ?>
                                                                        <option value="<?php echo $user_name; ?>"
                                                                        <?php
                                                                        if ($gengp_error == true) {
                                                                            if ($gp_username == $user_name) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        } else {
                                                                            echo "";
                                                                        }
                                                                        ?>><?php echo $user_full_name; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                            </select>
                                                            <b class="text-danger"><?php echo $userErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="gatepass_start_date">Gatepass start date</label> <b class="text-danger"> *</b>
                                                            <input type="date" required="required" class="form-control" name="gatepass_start_date" id="gatepass_start_date" placeholder="ENTER GATEPASS START DATE"
                                                                   value="<?php
                                                                   if ($gengp_error == true) {
                                                                       echo $gatepass_start_date;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $gatepass_start_dateErr; ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="project">Project</label> <b class="text-danger"> *</b>
                                                            <select class="form-control select2" id="project_id" name="project_id">
                                                                <option value="">SELECT PROJECT</option>
                                                                <?php
                                                                $project_data[] = array("session_token" => $login_token, "session_user" => $login_user, "status" => "",
                                                                    "project_name" => "", "project_location" => "", "user_role" => $user_role_id);
                                                                $project_recv_data = json_encode($project_data);
                                                                $getprojectData = json_decode(callAPI($project_recv_data, $project_details_api));
                                                                $ret_project_error = $getprojectData->error;
                                                                $ret_project_message = $getprojectData->message;
                                                                $ret_project_data = $getprojectData->data;

                                                                if ($ret_project_error == true) {
                                                                    // $projectErr = $ret_project_message;
                                                                } else {
                                                                    foreach ($ret_project_data as $dataVal) {
                                                                        $project_detail = $dataVal->Details;
                                                                    }
                                                                    foreach ($project_detail as $project_details) {
                                                                        $project_id = $project_details->project_id;
                                                                        $project_name = $project_details->project_name;
                                                                        $project_location = $project_details->project_location;
                                                                        ?>
                                                                        <option value="<?php echo $project_id; ?>"
                                                                        <?php
                                                                        if ($gengp_error == true) {
                                                                            if ($gp_project_id == $project_id) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        } else {
                                                                            echo "";
                                                                        }
                                                                        ?>><?php echo $project_name; ?> (<?php echo $project_location; ?>)</option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                            </select>
                                                            <b class="text-danger"><?php echo $projectErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="gatepass_end_date">Gatepass end date</label> <b class="text-danger"> *</b>
                                                            <input type="date" required="required" class="form-control" name="gatepass_end_date" id="gatepass_end_date" placeholder="ENTER GATEPASS END DATE"
                                                                   value="<?php
                                                                   if ($gengp_error == true) {
                                                                       echo $gatepass_end_date;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $gatepass_end_dateErr; ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <button type="submit" name="gen_gp" id="gen_gp" class="btn btn-primary mr-2">
                                                            Generate</button>
                                                        <b class="text-success"><?php echo $successMsg; ?></b>
                                                        <b class="text-danger"><?php echo $errmsg; ?></b>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Requested gatepass</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $agp_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Request_all");
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
                                                                <th style="font-weight: bolder;">Gatepass ID</th>
                                                                <th style="font-weight: bolder;">Member Name / ID</th>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Project location</th>
                                                                <th style="font-weight: bolder;">Gatepass date</th>
                                                                <th style="font-weight: bolder;">Action</th>
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
                                                                <th style="font-weight: bolder;">Gatepass ID</th>
                                                                <th style="font-weight: bolder;">Member Name / ID</th>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Project location</th>
                                                                <th style="font-weight: bolder;">Gatepass date</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_agp_data as $dataVal) {
                                                                $ret_agp_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_agp_detail as $ret_agp_details) {
                                                                $amember_id = $ret_agp_details->member_id;
                                                                $amember_name = $ret_agp_details->member_first_name . " " . $ret_agp_details->member_middle_name . " " . $ret_agp_details->member_last_name;
                                                                $aproject_name = $ret_agp_details->project_name;
                                                                $aproject_location = $ret_agp_details->project_location;
                                                                $agp_id = $ret_agp_details->gatepass_id;
                                                                $agatepass_start_date = date("d/m/Y", strtotime($ret_agp_details->gatepass_start_date));
                                                                $agatepass_end_date = date("d/m/Y", strtotime($ret_agp_details->gatepass_end_date));
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $agp_id; ?></td>
                                                                    <td><?php echo $amember_name; ?> / <?php echo $amember_id; ?></td>
                                                                    <td><?php echo $aproject_name; ?></td>
                                                                    <td><?php echo $aproject_location; ?></td>
                                                                    <td><?php echo $agatepass_start_date . " to " . $agatepass_end_date; ?></td>
                                                                    <td align="center">
                                                                        <button type="button" class="btn btn-success btn-rounded btn-sm" id="inactive_active" value="Accepted"
                                                                                onclick="change_status(this.value, '<?php echo $agp_id; ?>');">
                                                                            <span>Accept</span></button><br /><br />

                                                                        <button type="button" class="btn btn-danger btn-rounded btn-sm" id="inactive_active" value="Rejected"
                                                                                onclick="change_status(this.value, '<?php echo $agp_id; ?>');">
                                                                            <span>Reject</span></button>
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
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>
                        <!-- content-wrapper ends -->
                        <!-- partial:../partials/_footer.html -->
                        <?php
                        include '../footer.php';
                        ?>
                        <!-- partial -->
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

                function change_status(gatepass_status, gatepass_id) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";
                    var conf = confirm('Are you sure?');
                    if (conf) {
                        var values = [{session_token: login_token, session_user: login_user, gatepass_id: gatepass_id, status: gatepass_status}];

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>/api/employee/gatepass/availability.php",
                            dataType: "json",
                            data: JSON.stringify(values),
                            success: function (RetVal) {
                                if (RetVal.message === "Success") {
                                    $("#success_message").text(RetVal.data);
                                    window.location.href = "generate_gatepass.php";
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
} else {
    header("location:../index.html");
}
?>
