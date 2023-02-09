<?php
$main_page = "Add manual";
$page = "Add manual";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
   if (isset($_REQUEST['create_manual'])) {
        $project_id = trim($_REQUEST['project_id']);
        $manual_title = trim($_REQUEST['manual_title']);
        $manual_descr = trim($_REQUEST['manual_descr']);
        $createmanual_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id,
            "manual_title" => $manual_title, "manual_descr" => $manual_descr);
        $mn_data = json_encode($createmanual_data);
        $createmanual_getData = json_decode(callAPI($mn_data, $manual_create_api));
        $createmanual_error = $createmanual_getData->error;
        $createmanual_message = $createmanual_getData->message;
        $createmanual_data = $createmanual_getData->data;

        if ($createmanual_error == true) {
            if ($createmanual_message == "Server error") {
                foreach ($createmanual_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($createmanual_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $projectErr = $dataVal->ProjectErr;
                    $manual_titleErr = $dataVal->ManualTitleErr;
                    $manual_descrErr = $dataVal->ManualDescrErr;
                }
            }
        } else {
            $successMsg = "Successfully inserted manual";
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
                                            <form class="forms-sample" action="" method="post">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="project_id">Project</label> <b class="text-danger"> *</b>
                                                <select class="form-control" id="project_id" required="required" name="project_id">
                                                    <option value="">SELECT PROJECT</option>
                                                    <?php
                                                    $project_data[] = array("session_token" => $login_token, "session_user" => $login_user, "status" => "Active",
                                                        "project_name" => "", "project_location" => "", "user_role"=>$user_role_id);
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
                                                            if ($createmanual_error == true) {
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
                                                <label for="manual_title">Manual title</label> <b class="text-danger"> *</b>
                                                <input type="text" required="required" class="form-control" name="manual_title" id="manual_title" placeholder="ENTER MANUAL TITLE"
                                                       value="<?php
                                                       if ($createmanual_error == true) {
                                                           echo $manual_title;
                                                       } else {
                                                           echo "";
                                                       }
                                                       ?>"/>
                                                <b class="text-danger"><?php echo $manual_titleErr; ?></b>
                                            </div>
                                            <div class="form-group">
                                                <label for="manual_descr">Manual description</label> <b class="text-danger"> *</b>
                                                <div>
                                                    <textarea id="manual_description" required="required" name="manual_descr" class="form-control" style="margin-top: 30px;">
                                                     <?php
                                                       if ($createmanual_error == true) {
                                                           echo $manual_descr;
                                                       } else {
                                                           echo "";
                                                       }
                                                       ?>
                                                    </textarea>
                                                </div>
                                                <b class="text-danger"><?php echo $manual_descrErr; ?></b>
                                            </div>
                                            <button type="submit" name="create_manual" id="create_manual" class="btn btn-primary mr-2">Save</button>
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
} else {
    header("location:../index.html");
}
?>
