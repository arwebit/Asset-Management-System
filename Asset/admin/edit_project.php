<?php
$main_page = "Update project";
$page = "Update project";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    $updateproject_error = false;
    if ($_REQUEST['project_id']) {
        $project_id = $_REQUEST['project_id'];
        if (isset($_REQUEST['save_project'])) {
            $project_name = trim($_REQUEST['project_name']);
            $project_location = trim($_REQUEST['project_location']);
            $project_address = trim($_REQUEST['project_address']);
            $project_scope = trim($_REQUEST['project_scope']);
            $project_start_date = trim($_REQUEST['project_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['project_start_date'])));
            $project_end_date = trim($_REQUEST['project_end_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['project_end_date'])));

            $updateproject_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id,
                "project_name" => $project_name, "project_location" => $project_location, "project_address" => $project_address, "project_scope" => $project_scope,
                "project_start_date" => $project_start_date, "project_end_date" => $project_end_date);
            $rp_data = json_encode($updateproject_data);
            $updateproject_getData = json_decode(callAPI($rp_data, $project_update_api));
            $updateproject_error = $updateproject_getData->error;
            $updateproject_message = $updateproject_getData->message;
            $updateproject_data = $updateproject_getData->data;


            if ($updateproject_error == true) {
                if ($updateproject_message == "Server error") {
                    foreach ($updateproject_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMessage;
                    }
                } else {
                    foreach ($updateproject_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $project_nameErr = $dataVal->ProjectNameErr;
                        $project_locationErr = $dataVal->ProjectLocationErr;
                        $project_scopeErr = $dataVal->ProjectScopeErr;
                        $project_addressErr = $dataVal->ProjectAddressErr;
                        $project_start_dateErr = $dataVal->ProjectStartDateErr;
                        $project_end_dateErr = $dataVal->ProjectEndDateErr;
                    }
                }
            } else {
                $successMsg = "Successfully updated project";
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
                $fproject_scope = $projectDetailVal->project_scope;
                $fproject_address = $projectDetailVal->project_address;
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
                include '../text_editor_css.php';
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
                                                <form class="forms-sample" action="" method="post" id="profile_update">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="project_name">Project name</label> <b class="text-danger"> *</b>
                                                                <input type="text" required="required" class="form-control" name="project_name" id="project_name" placeholder="ENTER PROJECT NAME"
                                                                       value="<?php
                                                                       if ($updateproject_error == false) {
                                                                           echo $fproject_name;
                                                                       } else {
                                                                           echo $project_name;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_nameErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_start_date">Project start date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="project_start_date" id="project_start_date" placeholder="ENTER PROJECT START DATE"
                                                                       value="<?php
                                                                       if ($updateproject_error == false) {
                                                                           echo $fproject_start_date;
                                                                       } else {
                                                                           echo $project_start_date;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_start_dateErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_scope">Project scope</label> <b class="text-danger"> *</b>
                                                                <textarea class="form-control" name="project_scope" id="project_scope">
                                                                    <?php
                                                                    if ($updateproject_error == false) {
                                                                        echo $fproject_scope;
                                                                    } else {
                                                                        echo $project_scope;
                                                                    }
                                                                    ?></textarea>
                                                                <b class="text-danger"><?php echo $project_scopeErr; ?></b>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="project_name">Project location</label> <b class="text-danger"> *</b>
                                                                <input type="text" required="required" class="form-control" name="project_location" id="project_location" placeholder="ENTER PROJECT LOCATION"
                                                                       value="<?php
                                                                       if ($updateproject_error == false) {
                                                                           echo $fproject_location;
                                                                       } else {
                                                                           echo $project_location;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_locationErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_end_date">Project end date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="project_end_date" id="project_end_date" placeholder="ENTER PROJECT END DATE"
                                                                       value="<?php
                                                                       if ($updateproject_error == false) {
                                                                           echo $fproject_end_date;
                                                                       } else {
                                                                           echo $project_end_date;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_end_dateErr; ?></b>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="project_adddress">Project address</label> <b class="text-danger"> *</b>
                                                                <textarea class="form-control" name="project_address" id="project_address">
                                                                    <?php
                                                                    if ($updateproject_error == false) {
                                                                        echo $fproject_address;
                                                                    } else {
                                                                        echo project_address;
                                                                    }
                                                                    ?></textarea>
                                                                <b class="text-danger"><?php echo $project_addressErr; ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="form-group">
                                                                <button type="submit" name="save_project" id="save_project" class="btn btn-primary mr-2">
                                                                    Save</button>
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
                include '../text_editor_js.php';
                ?>
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>
