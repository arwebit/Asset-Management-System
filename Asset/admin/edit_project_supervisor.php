<?php
$main_page = "Update project supervisor";
$page = "Update supervisor";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    $updateprojectinfo_error = false;
    if ($_REQUEST['project_info_id']) {
        $project_info_id = $_REQUEST['project_info_id'];
        if (isset($_REQUEST['update_project_info'])) {
            $supervisor_name = trim($_REQUEST["supervisor_name"]);
            $project_supervisor_start_date = trim($_REQUEST['project_supervisor_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['project_supervisor_start_date'])));
            $project_supervisor_end_date = trim($_REQUEST['project_supervisor_end_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['project_supervisor_end_date'])));

            $updateprojectinfo_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_info_id" => $project_info_id,
                "project_supervisor_name" => $supervisor_name, "project_supervisor_start_date" => $project_supervisor_start_date, "project_supervisor_end_date" => $project_supervisor_end_date);
            $cp_data = json_encode($updateprojectinfo_data);
            $updateprojectinfo_getData = json_decode(callAPI($cp_data, $project_info_update_api));
            $updateprojectinfo_error = $updateprojectinfo_getData->error;
            $updateprojectinfo_message = $updateprojectinfo_getData->message;
            $updateprojectinfo_data = $updateprojectinfo_getData->data;

            if ($updateprojectinfo_error == true) {
                if ($updateprojectinfo_message == "Server error") {
                    foreach ($updateprojectinfo_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMessage;
                    }
                } else {
                    foreach ($updateprojectinfo_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $project_supernameErr = $dataVal->ProjectSuperNameErr;
                        $project_supervisor_start_dateErr = $dataVal->ProjectStartDateErr;
                        $project_supervisor_end_dateErr = $dataVal->ProjectEndDateErr;
                    }
                }
            } else {
                $successMsg = "Successfully updated project info";
            }
        }
        $aprojects_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_info_id" => $project_info_id);
        $aprojects_recv_data = json_encode($aprojects_data);
        $getaprojectsData = json_decode(callAPI($aprojects_recv_data, $selected_project_info_detail_api));
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
                $fproject_id = $projectDetailVal->project_id;
                $fproject_supervisor = array_map('trim', explode(",", $projectDetailVal->project_supervisor));
                $fproject_supervisor_start_date = $projectDetailVal->project_supervisor_start_date;
                $fproject_supervisor_end_date = $projectDetailVal->project_supervisor_end_date;
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
                                                                <label for="supervisor_name">Supervisor name</label> <b class="text-danger"> *</b>
                                                                <select class="form-control" name="supervisor_name" id="supervisor_name">
                                                                    <option value="">SELECT SUPERVISOR</option>
                                                                    <?php
                                                                    $user_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                        "status" => "Active", "first_name" => "", "mobile" => "", "email" => "",
                                                                        "role_id_from" => "2", "role_id_to" => "2");

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
                                                                            if ($updateprojectinfo_error == false) {
                                                                                ?>
                                                                                <?= in_array($user_name, $fproject_supervisor) ? 'selected="selected"' : NULL; ?>
                                                                                <?php
                                                                            } else {
                                                                                if (isset($_REQUEST["supervisor_name"]) && is_array($_REQUEST["supervisor_name"]) && in_array($rule_id, $_REQUEST["supervisor_name"])) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                            }
                                                                            ?>><?php echo $user_full_name; ?></option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                </select>
                                                                <b class="text-danger"><?php echo $project_supernameErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_supervisor_end_date">Supervisor end date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="project_supervisor_end_date" id="project_supervisor_end_date" placeholder="ENTER PROJECT END DATE"
                                                                       value="<?php
                                                                       if ($updateprojectinfo_error == false) {
                                                                           echo $fproject_supervisor_end_date;
                                                                       } else {
                                                                           echo $project_supervisor_end_date;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_supervisor_end_dateErr; ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="project_supervisor_start_date">Supervisor start date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="project_supervisor_start_date" id="project_supervisor_start_date" placeholder="ENTER PROJECT START DATE"
                                                                       value="<?php
                                                                       if ($updateprojectinfo_error == false) {
                                                                           echo $fproject_supervisor_start_date;
                                                                       } else {
                                                                           echo $project_supervisor_start_date;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_supervisor_start_dateErr; ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <button type="submit" name="update_project_info" id="update_project_info" class="btn btn-primary mr-2">Save</button>
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
