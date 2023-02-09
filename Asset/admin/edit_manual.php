<?php
$main_page = "Update manual";
$page = "Update manual";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    $updatemanual_error = false;
    if ($_GET['manual_id']) {
        $manual_id = $_GET['manual_id'];
        if (isset($_REQUEST['save_manual'])) {
            $project_id = trim($_REQUEST['project_id']);
            $manual_title = trim($_REQUEST['manual_title']);
            $manual_descr = trim($_REQUEST['manual_descr']);

            $updatemanual_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id,
                "manual_title" => $manual_title, "manual_descr" => $manual_descr, "manual_id" => $manual_id);
            $rp_data = json_encode($updatemanual_data);
            $updatemanual_getData = json_decode(callAPI($rp_data, $manual_update_api));
            $updatemanual_error = $updatemanual_getData->error;
            $updatemanual_message = $updatemanual_getData->message;
            $updatemanual_data = $updatemanual_getData->data;


            if ($updatemanual_error == true) {
                if ($updatemanual_message == "Server error") {
                    foreach ($updatemanual_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMessage;
                    }
                } else {
                    foreach ($updatemanual_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $projectErr = $dataVal->ProjectErr;
                        $manual_titleErr = $dataVal->ManualTitleErr;
                        $manual_descrErr = htmlentities($dataVal->ManualDescrErr);
                    }
                }
            } else {
                $successMsg = "Successfully updated manual";
            }
        }
        $amanuals_data[] = array("session_token" => $login_token, "session_user" => $login_user, "manual_id" => $manual_id);
        $amanuals_recv_data = json_encode($amanuals_data);
        $getamanualsData = json_decode(callAPI($amanuals_recv_data, $selected_manual_detail_api));
        $ret_amanuals_error = $getamanualsData->error;
        $ret_amanuals_message = $getamanualsData->message;
        $ret_amanuals_data = $getamanualsData->data;
        if ($ret_amanuals_error == true) {
            $errMsg = $ret_amanuals_message;
        } else {
            foreach ($ret_amanuals_data as $dataVal) {
                $manualDetail = $dataVal->Details;
            }
            foreach ($manualDetail as $manualDetailVal) {
                $fproject_id = $manualDetailVal->project_id;
                $fmanual_title = $manualDetailVal->manual_title;
                $fmanual_descr = $manualDetailVal->manual_descr;
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
                                                <form class="forms-sample" action="" method="post" id="manual_update">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="project_id">Project</label> <b class="text-danger"> *</b>
                                                                <select class="form-control select2" id="project_id" name="project_id" required="required">
                                                                    <option value="">SELECT PROJECT</option>
                                                                    <?php
                                                                    $project_data[] = array("session_token" => $login_token, "session_user" => $login_user, "status" => "Active",
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
                                                                            if ($updatemanual_error == true) {
                                                                                if ($sproject_id == $project_id) {
                                                                                    echo "selected='selected'";
                                                                                }
                                                                            } else {
                                                                                if ($sproject_id == $fproject_id) {
                                                                                    echo "selected='selected'";
                                                                                }
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
                                                                <label for="manual_title">Manual title</label> <b class="text-danger"> *</b>
                                                                <input type="text" required="required" class="form-control" name="manual_title" id="manual_title" placeholder="ENTER MANUAL TITLE"
                                                                       value="<?php
                                                                       if ($updatemanual_error == false) {
                                                                           echo $fmanual_title;
                                                                       } else {
                                                                           echo $manual_title;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $manual_titleErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="manual_descr">Manual description</label> <b class="text-danger"> *</b>
                                                                <div id="editor">
                                                                    <textarea id="manual_description" required="required" name="manual_descr" class="form-control" style="margin-top: 30px;">
                                                                        <?php
                                                                        if ($updatemanual_error == false) {
                                                                            echo $fmanual_descr;
                                                                        } else {
                                                                            echo $manual_descr;
                                                                        }
                                                                        ?>
                                                                    </textarea>
                                                                </div>
                                                                <b class="text-danger"><?php echo $manual_descrErr; ?></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" name="save_manual" id="save_manual" class="btn btn-primary mr-2">
                                                        Save</button>
                                                    <b class="text-success"><?php echo $successMsg; ?></b>
                                                    <b class="text-danger"><?php echo $errmsg; ?></b>
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
