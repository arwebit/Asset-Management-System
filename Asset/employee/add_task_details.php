<?php
$main_page = "Generate report";
$page = "Generate report";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['task_master_id']) {
        $task_master_id = $_REQUEST['task_master_id'];
        if (isset($_REQUEST['create_task_details'])) {
            $task_actual_value = trim($_REQUEST['task_actual_value']);
            $emp_remarks = trim($_REQUEST['emp_remarks']);
            $check_supervisor = trim($_REQUEST['supervisor_name']);

            $create_task_detail_data[] = array("session_token" => $login_token, "session_user" => $login_user, "task_master_id" => $task_master_id,
                "task_actual_value" => $task_actual_value, "emp_remarks" => $emp_remarks, "check_supervisor" => $check_supervisor);
            $cr_data = json_encode($create_task_detail_data);
            $create_task_detail_getData = json_decode(callAPI($cr_data, $task_detail_create_api));
            $create_task_detail_error = $create_task_detail_getData->error;
            $create_task_detail_message = $create_task_detail_getData->message;
            $create_task_detail_data = $create_task_detail_getData->data;

            if ($create_task_detail_error == true) {
                if ($create_task_detail_message == "Server error") {
                    foreach ($create_task_detail_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMessage;
                    }
                } else {
                    foreach ($create_task_detail_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $check_supervisorErr = $dataVal->SupervisorErr;
                        $task_actual_valueErr = $dataVal->ActualValueErr;
                        $emp_remarksErr = $dataVal->EmployeeRemarksErr;
                    }
                }
            } else {
                $successMsg = "Successfully inserted";
            }
        }

        $atasks_data[] = array("session_token" => $login_token, "session_user" => $login_user, "task_master_id" => $task_master_id);
        $atasks_recv_data = json_encode($atasks_data);
        $getatasksData = json_decode(callAPI($atasks_recv_data, $task_detail_api));
        $ret_atasks_error = $getatasksData->error;
        $ret_atasks_message = $getatasksData->message;
        $ret_atasks_data = $getatasksData->data;

        if ($ret_atasks_error == true) {
            $atasksErr = $ret_atasks_status;
        } else {
            foreach ($ret_atasks_data as $dataVal) {
                $ret_atasks_detail = $dataVal->Details;
            }
            foreach ($ret_atasks_detail as $ret_atasks_details) {
                $fproject_id = $ret_atasks_details->project_id;
                $fcategory_id = $ret_atasks_details->category_id;
                $ftask_description = $ret_atasks_details->task_description;
                $ftask_standard_value = $ret_atasks_details->standard_value;
                $ftask_unit = $ret_atasks_details->task_unit;
                $fdeviation_min_range = $ret_atasks_details->min_deviation;
                $fdeviation_max_range = $ret_atasks_details->max_deviation;
                $fproject_supervisor = $ret_atasks_details->project_supervisor;
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
                                                                <label for="project_id">Project</label>
                                                                <select class="form-control select2" id="project_id" required="required" name="project_id" disabled="disabled">
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
                                                                            $sproject_id = $project_details->project_id;
                                                                            $project_name = $project_details->project_name;
                                                                            $project_location = $project_details->project_location;
                                                                            ?>
                                                                            <option value="<?php echo $sproject_id; ?>"
                                                                            <?php
                                                                            if ($fproject_id == $sproject_id) {
                                                                                echo "selected='selected'";
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
                                                                <label for="task_description">Task description</label>
                                                                <input type="text" disabled="disabled" required="required" maxlength="255" class="form-control" name="task_description" id="task_description" placeholder="ENTER TASK DESCRIPTION"
                                                                       value="<?php echo $ftask_description; ?>"/>
                                                                <b class="text-danger"><?php echo $task_descriptionErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="task_standard_value">Task standard value</label>
                                                                <input type="number" disabled="disabled" step="0.01" required="required" class="form-control" name="task_standard_value" id="task_standard_value" placeholder="ENTER TASK STANDARD VALUE"
                                                                       value="<?php echo $ftask_standard_value; ?>"/>
                                                                <b class="text-danger"><?php echo $task_standard_valueErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="deviation_max_range">Deviation maximum range</label>
                                                                <input type="number" step="0.01" required="required" class="form-control" disabled="disabled" name="deviation_max_range" id="deviation_max_range" placeholder="ENTER DEVIATION MAXIMUM RANGE"
                                                                       value="<?php echo $fdeviation_max_range; ?>"/>
                                                                <b class="text-danger"><?php echo $deviation_max_rangeErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="supervisor_name">Checked by (Supervisor name)</label>  <b class="text-danger"> *</b>
                                                                <select class="form-control select2" name="supervisor_name" id="supervisor_name">
                                                                    <option value="<?php echo $user_name; ?>"
                                                                    <?php
                                                                    if ($createtask_detail_error == true) {
                                                                        if ($check_supervisor == "") {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>>SELECT SUPERVISOR</option>
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
                                                                            if ($create_task_detail_error == true) {
                                                                                if ($check_supervisor == $user_name) {
                                                                                    echo "selected='selected'";
                                                                                }
                                                                            } else {
                                                                                if ($fproject_supervisor == $user_name) {
                                                                                    echo "selected='selected'";
                                                                                }
                                                                            }
                                                                            ?>><?php echo $user_full_name; ?></option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                </select>
                                                                <b class="text-danger"><?php echo $check_supervisorErr; ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="category_id">Category</label>
                                                                <select class="form-control select2" id="category_id" required="required" name="category_id" disabled="disabled">
                                                                    <option value="">SELECT CATEGORY</option>
                                                                    <?php
                                                                    $acategorys_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                        "status" => "Active");
                                                                    $acategorys_recv_data = json_encode($acategorys_data);
                                                                    $getacategorysData = json_decode(callAPI($acategorys_recv_data, $category_details_api));
                                                                    $ret_acategorys_error = $getacategorysData->error;
                                                                    $ret_acategorys_message = $getacategorysData->message;
                                                                    $ret_acategorys_data = $getacategorysData->data;

                                                                    if ($ret_acategorys_error == true) {
                                                                        $acategorysErr = $ret_acategorys_status;
                                                                    } else {
                                                                        foreach ($ret_acategorys_data as $dataVal) {
                                                                            $ret_acategorys_detail = $dataVal->Details;
                                                                        }
                                                                        $aslno = 0;
                                                                        foreach ($ret_acategorys_detail as $ret_acategorys_details) {
                                                                            $acategorys_id = $ret_acategorys_details->category_id;
                                                                            $acategorys_name = $ret_acategorys_details->category_name;
                                                                            ?>
                                                                            <option value="<?php echo $acategorys_id; ?>"
                                                                            <?php
                                                                            if ($fcategory_id == $acategorys_id) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                            ?>><?php echo $acategorys_name; ?> </option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                </select>
                                                                <b class="text-danger"><?php echo $categoryErr; ?></b>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="task_unit">Task unit</label>
                                                                <input type="text" required="required" class="form-control" disabled="disabled" name="task_unit" id="task_unit" placeholder="ENTER TASK UNIT"
                                                                       value="<?php echo $ftask_unit; ?>"/>
                                                                <b class="text-danger"><?php echo $task_unitErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="deviation_min_range">Deviation minimum range</label>
                                                                <input type="number" step="0.01" required="required" disabled="disabled" class="form-control" name="deviation_min_range" id="deviation_min_range" placeholder="ENTER DEVIATION MINIMUM RANGE"
                                                                       value="<?php echo $fdeviation_min_range; ?>"/>
                                                                <b class="text-danger"><?php echo $deviation_min_rangeErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="task_actual_value">Task actual value</label>  <b class="text-danger"> *</b>
                                                                <input type="number" step="0.01" required="required" class="form-control" name="task_actual_value" id="task_actual_value" placeholder="ENTER TASK ACTUAL VALUE"
                                                                       value="<?php
                                                                       if ($create_task_detail_error == true) {
                                                                           echo $task_actual_value;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $task_actual_valueErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="emp_remarks">Employee remarks</label> <b class="text-danger"> *</b>
                                                                <textarea class="form-control" name="emp_remarks" id="remark">
                                                                    <?php
                                                                    if ($create_task_detail_error == true) {
                                                                        echo $emp_remarks;
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>
                                                                </textarea>
                                                                <b class="text-danger"><?php echo $emp_remarksErr; ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <button type="submit" name="create_task_details" id="create_task_details" class="btn btn-primary mr-2">Save</button>
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
