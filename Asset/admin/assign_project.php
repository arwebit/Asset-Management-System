<?php
$main_page = "Project assign";
$page = "Project assign";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['project_id']) {
        $project_id = $_REQUEST['project_id'];
        if (isset($_REQUEST['create_project_assign'])) {
            if (empty($_REQUEST["assign_name"])) {
                $assign_name = "";
            } else {
                $assign_name = implode(",", $_REQUEST["assign_name"]);
            }
            $gatepass_start_date = trim($_REQUEST['gatepass_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['gatepass_start_date'])));
            $gatepass_end_date = trim($_REQUEST['gatepass_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['gatepass_end_date'])));
            $createprojectinfo_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id,
                "project_assign_name" => $assign_name, "gatepass_start_date" => $gatepass_start_date, "gatepass_end_date" => $gatepass_end_date);
            $cp_data = json_encode($createprojectinfo_data);
            $createprojectinfo_getData = json_decode(callAPI($cp_data, $project_assign_create_path));
            $createprojectinfo_error = $createprojectinfo_getData->error;
            $createprojectinfo_message = $createprojectinfo_getData->message;
            $createprojectinfo_data = $createprojectinfo_getData->data;

            if ($createprojectinfo_error == true) {
                if ($createprojectinfo_message == "Server error") {
                    foreach ($createprojectinfo_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMessage;
                    }
                } else {
                    foreach ($createprojectinfo_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $project_assignerErr = $dataVal->ProjectAssignerErr;
                        $gatepass_start_dateErr = $dataVal->GatepassStartDateErr;
                        $gatepass_end_dateErr = $dataVal->GatepassEndDateErr;
                    }
                }
            } else {
                foreach ($createprojectinfo_data as $dataVal) {
                    $successMsg = $dataVal->SuccessMsg;
                }
            }
        }
        $aprojects_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id);
        $aprojects_recv_data = json_encode($aprojects_data);
        $getaprojectsData = json_decode(callAPI($aprojects_recv_data, $selected_project_detail_api));
        $ret_aprojects_error = $getaprojectsData->error;
        $ret_aprojects_message = $getaprojectsData->message;
        $ret_aprojects_data = $getaprojectsData->data;
        if ($ret_aprojects_error == true) {
            $errMsg = $ret_aprojects_message;
        } else {
            foreach ($ret_aprojects_data as $dataVal) {
                $projectDetail = $dataVal->Details;
            }
            foreach ($projectDetail as $projectDetailVal) {
                $fproject_name = $projectDetailVal->project_name;
                $fproject_location = $projectDetailVal->project_location;
                $fproject_start_date = $projectDetailVal->project_start_date;
                $fproject_end_date = $projectDetailVal->project_end_date;
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
                                                                <label for="project_name">Project name</label> <b class="text-danger"> </b>
                                                                <input type="text" readonly="readonly" class="form-control" name="project_name" id="project_name" placeholder="ENTER PROJECT NAME"
                                                                       value="<?php echo $fproject_name; ?>"/>
                                                                <b class="text-danger"><?php echo $project_nameErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_start_date">Project start date</label> <b class="text-danger"> </b>
                                                                <input type="date" readonly="readonly" class="form-control" name="project_start_date" id="project_start_date" placeholder="ENTER PROJECT START DATE"
                                                                       value="<?php echo $fproject_start_date; ?>"/>
                                                                <b class="text-danger"><?php echo $project_start_dateErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="gatepass_start_date">Gatepass start date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="gatepass_start_date" id="gatepass_start_date" placeholder="ENTER GATEPASS START DATE"
                                                                       value="<?php
                                                                       if ($createprojectinfo_error == true) {
                                                                           echo $gatepass_start_date;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $gatepass_start_dateErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="assign_name">Assigner</label> <b class="text-danger"> *</b>
                                                                <select class="form-control js-example-basic-multiple w-100" multiple="multiple" data-placeholder="SELECT EMPLOYEE" name="assign_name[]" id="assign_name" required="required">
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
                                                                            if ($createprojectinfo_error == true) {
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
                                                                <b class="text-danger"><?php echo $project_assignerErr; ?></b>
                                                            </div>                                                      
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="project_name">Project location</label> <b class="text-danger"> </b>
                                                                <input type="text" readonly="readonly" class="form-control" name="project_location" id="project_location" placeholder="ENTER PROJECT LOCATION"
                                                                       value="<?php echo $fproject_location; ?>"/>
                                                                <b class="text-danger"><?php echo $project_locationErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_end_date">Project end date</label> <b class="text-danger"> </b>
                                                                <input type="date" readonly="readonly" class="form-control" name="project_end_date" id="project_end_date" placeholder="ENTER PROJECT END DATE"
                                                                       value="<?php echo $fproject_end_date; ?>"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="gatepass_end_date">Gatepass end date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="gatepass_end_date" id="gatepass_end_date" placeholder="ENTER GATEPASS END DATE"
                                                                       value="<?php
                                                                       if ($createprojectinfo_error == true) {
                                                                           echo $gatepass_end_date;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $gatepass_end_dateErr; ?></b>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <button type="submit" name="create_project_assign" id="create_project_assign" class="btn btn-primary mr-2">Save</button>
                                                            </div>

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
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>
