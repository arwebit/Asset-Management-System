<?php
$main_page = "Request gatepass";
$page = "Request gatepass";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if (isset($_REQUEST['gen_gp'])) {
        $gp_username = trim($login_user);
        $gp_project_id = trim($_REQUEST['project_id']);
        $gatepass_start_date = trim($_REQUEST['gatepass_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['gatepass_start_date'])));
        $gatepass_end_date = trim($_REQUEST['gatepass_end_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['gatepass_end_date'])));

        $gengp_data[] = array("session_token" => $login_token, "session_user" => $login_user, "username" => $gp_username, "project_id" => $gp_project_id,
            "gatepass_start_date" => $gatepass_start_date, "gatepass_end_date" => $gatepass_end_date, "status" => "2");
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

            $successMsg = "Successfully requested gatepass for approval";
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
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="project">Project</label> <b class="text-danger"> *</b>
                                                            <select class="form-control select2" id="project_id" name="project_id" required="required">
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
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
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
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <button type="submit" name="gen_gp" id="gen_gp" class="btn btn-primary mr-2">
                                                            Request gatepass</button>
                                                        <b class="text-success"><?php echo $successMsg; ?></b>
                                                        <b class="text-danger"><?php echo $errmsg; ?></b>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>                                
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

                    var values = [{session_token: login_token, session_user: login_user, gatepass_id: gatepass_id, status: gatepass_status}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/employee/gatepass/availability.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#success_message").text(RetVal.data);
                                window.location.href = "gatepass_generate.php";
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
