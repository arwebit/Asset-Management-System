<?php
$main_page = "Add tasks";
$page = "Add tasks";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if (isset($_REQUEST['create_task'])) {
        $project_id = trim($_REQUEST['project_id']);
        $task_description = trim($_REQUEST['task_description']);
        $task_standard_value = trim($_REQUEST['task_standard_value']);
        $category_id = trim($_REQUEST['category_id']);
        $task_unit = trim($_REQUEST['task_unit']);
        $deviation_min_range = trim($_REQUEST['deviation_min_range']);
        $deviation_max_range = trim($_REQUEST['deviation_max_range']);

        $createtask_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id,
            "task_description" => $task_description, "task_standard_value" => $task_standard_value, "category_id" => $category_id,
            "task_unit" => $task_unit, "deviation_min_range" => $deviation_min_range, "deviation_max_range" => $deviation_max_range);
        $cr_data = json_encode($createtask_data);
        $createtask_getData = json_decode(callAPI($cr_data, $task_create_api));
        $createtask_error = $createtask_getData->error;
        $createtask_message = $createtask_getData->message;
        $createtask_data = $createtask_getData->data;

        if ($createtask_error == true) {
            if ($createtask_message == "Server error") {
                foreach ($createtask_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($createtask_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $projectErr = $dataVal->ProjectErr;
                    $categoryErr = $dataVal->CategoryErr;
                    $task_descriptionErr = $dataVal->TaskDescriptionErr;
                    $task_standard_valueErr = $dataVal->StandardValueErr;
                    $task_unitErr = $dataVal->TaskUnitErr;
                    $deviation_min_rangeErr = $dataVal->DeviationMinErr;
                    $deviation_max_rangeErr = $dataVal->DeviationMaxErr;
                }
            }
        } else {
            $successMsg = "Successfully created task";
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
                                                            <label for="project_id">Project</label> <b class="text-danger"> *</b>
                                                            <select class="form-control select2" id="project_id" required="required" name="project_id">
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
                                                                        if ($createtask_error == true) {
                                                                            if ($sproject_id == $project_id) {
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
                                                            <label for="task_description">Task description</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" maxlength="255" class="form-control" name="task_description" id="task_description" placeholder="ENTER TASK DESCRIPTION"
                                                                   value="<?php
                                                                   if ($createtask_error == true) {
                                                                       echo $task_description;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $task_descriptionErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="task_standard_value">Task standard value</label> <b class="text-danger"> *</b>
                                                            <input type="number" step="0.01" required="required" class="form-control" name="task_standard_value" id="task_standard_value" placeholder="ENTER TASK STANDARD VALUE"
                                                                   value="<?php
                                                                   if ($createtask_error == true) {
                                                                       echo $task_standard_value;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $task_standard_valueErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="deviation_max_range">Deviation maximum range</label> <b class="text-danger"> *</b>
                                                            <input type="number" step="0.01" required="required" class="form-control" name="deviation_max_range" id="deviation_max_range" placeholder="ENTER DEVIATION MAXIMUM RANGE"
                                                                   value="<?php
                                                                   if ($createtask_error == true) {
                                                                       echo $deviation_max_range;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $deviation_max_rangeErr; ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="category_id">Category</label> <b class="text-danger"> *</b>
                                                            <select class="form-control select2" id="category_id" required="required" name="category_id">
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
                                                                        if ($createtask_error == true) {
                                                                            if ($acategorys_id == $category_id) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        } else {
                                                                            echo "";
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
                                                            <label for="task_unit">Task unit</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="task_unit" id="task_unit" placeholder="ENTER TASK UNIT"
                                                                   value="<?php
                                                                   if ($createtask_error == true) {
                                                                       echo $task_unit;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $task_unitErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="deviation_min_range">Deviation minimum range</label> <b class="text-danger"> *</b>
                                                            <input type="number" step="0.01" required="required" class="form-control" name="deviation_min_range" id="deviation_min_range" placeholder="ENTER DEVIATION MINIMUM RANGE"
                                                                   value="<?php
                                                                   if ($createtask_error == true) {
                                                                       echo $deviation_min_range;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $deviation_min_rangeErr; ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <button type="submit" name="create_task" id="create_task" class="btn btn-primary mr-2">Save</button>
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
} else {
    header("location:../index.html");
}
?>
