<?php
$main_page = "Project supervisor";
$page = "Project supervisor";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['project_id']) {
        $project_id = $_REQUEST['project_id'];
        if (isset($_REQUEST['create_project_info'])) {
            if (empty($_REQUEST["supervisor_name"])) {
                $supervisor_name = "";
            } else {
                $supervisor_name = implode(",", $_REQUEST["supervisor_name"]);
            }
            $project_supervisor_start_date = trim($_REQUEST['project_supervisor_start_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['project_supervisor_start_date'])));
            $project_supervisor_end_date = trim($_REQUEST['project_supervisor_end_date']) == "" ? "" : date("Y-m-d", strtotime(trim($_REQUEST['project_supervisor_end_date'])));

            $createprojectinfo_data[] = array("session_token" => $login_token, "session_user" => $login_user, "project_id" => $project_id,
                "project_supervisor_name" => $supervisor_name, "project_supervisor_start_date" => $project_supervisor_start_date, "project_supervisor_end_date" => $project_supervisor_end_date);
            $cp_data = json_encode($createprojectinfo_data);
            $createprojectinfo_getData = json_decode(callAPI($cp_data, $project_info_create_api));
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
                        $project_supernameErr = $dataVal->ProjectSuperNameErr;
                        $project_supervisor_start_dateErr = $dataVal->ProjectStartDateErr;
                        $project_supervisor_end_dateErr = $dataVal->ProjectEndDateErr;
                    }
                }
            } else {
                $successMsg = "Successfully inserted project info";
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
                                                <h4 class="card-title"><?php echo $main_page . " (" . $fproject_name . '-' . $fproject_location . ")"; ?></h4>
                                                <form class="forms-sample" action="" method="post">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="supervisor_name">Supervisor name</label> <b class="text-danger"> *</b>
                                                                <select class="form-control js-example-basic-multiple w-100" multiple="multiple" data-placeholder="SELECT SUPERVISOR" name="supervisor_name[]" id="supervisor_name">
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
                                                                <b class="text-danger"><?php echo $project_supernameErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_supervisor_end_date">Supervisor end date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="project_supervisor_end_date" id="project_supervisor_end_date" placeholder="ENTER PROJECT END DATE"
                                                                       value="<?php
                                                                       if ($createprojectinfo_error == true) {
                                                                           echo $project_supervisor_end_date;
                                                                       } else {
                                                                           echo $fproject_end_date;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_supervisor_end_dateErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" name="create_project_info" id="create_project_info" class="btn btn-primary mr-2">Save</button>
                                                            </div>

                                                            <b class="text-success"><?php echo $successMsg; ?></b>
                                                            <b class="text-danger"><?php echo $errmsg; ?></b>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="project_supervisor_start_date">Supervisor start date</label> <b class="text-danger"> *</b>
                                                                <input type="date" required="required" class="form-control" name="project_supervisor_start_date" id="project_supervisor_start_date" placeholder="ENTER PROJECT START DATE"
                                                                       value="<?php
                                                                       if ($createprojectinfo_error == true) {
                                                                           echo $project_supervisor_start_date;
                                                                       } else {
                                                                           echo $fproject_start_date;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $project_supervisor_start_dateErr; ?></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title"><?php echo $main_page . " (" . $fproject_name . '-' . $fproject_location . ")"; ?></h4>
                                                <div class="table-responsive">

                                                    <?php
                                                    $aprojects_info_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                        "project_id" => $project_id, "status" => "");
                                                    $aprojects_info_recv_data = json_encode($aprojects_info_data);
                                                    $getaproject_infoData = json_decode(callAPI($aprojects_info_recv_data, $project_info_detail_api));
                                                    $ret_aprojects_info_error = $getaproject_infoData->error;
                                                    $ret_aprojects_info_message = $getaproject_infoData->message;
                                                    $ret_aprojects_info_data = $getaproject_infoData->data;

                                                    if ($ret_aprojects_error == true) {
                                                        ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th style="font-weight: bolder;">Supervisor name</th>
                                                                    <th style="font-weight: bolder;">Start date</th>
                                                                    <th style="font-weight: bolder;">End date</th>
                                                                    <th style="font-weight: bolder;">Status</th>
                                                                    <th style="font-weight: bolder;">Option</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="5" align="center">No records found</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <table class="table table-hover sampleTable">
                                                            <thead>
                                                                <tr>
                                                                    <th style="font-weight: bolder;">Supervisor name</th>
                                                                    <th style="font-weight: bolder;">Start date</th>
                                                                    <th style="font-weight: bolder;">End date</th>
                                                                    <th style="font-weight: bolder;">Status</th>
                                                                    <th style="font-weight: bolder;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($ret_aprojects_info_data as $dataVal) {
                                                                    $ret_aprojects_detail = $dataVal->Details;
                                                                }
                                                                foreach ($ret_aprojects_detail as $ret_aprojects_details) {
                                                                    $aproject_info_id = $ret_aprojects_details->project_info_id;
                                                                    $aproject_supervisor_name = $ret_aprojects_details->member_first_name . " " . $ret_aprojects_details->member_middle_name . " " . $ret_aprojects_details->member_last_name;
                                                                    $aproject_supervisor_start_date = date("d/m/Y", strtotime($ret_aprojects_details->project_supervisor_start_date));
                                                                    $aproject_supervisor_end_date = $ret_aprojects_details->project_supervisor_end_date;
                                                                    if ($aproject_supervisor_end_date == "") {
                                                                        $project_supervisor_end_date = "N/A";
                                                                    } else {
                                                                        $project_supervisor_end_date = date("d/m/Y", strtotime($aproject_supervisor_end_date));
                                                                    }
                                                                    $aproject_status = $ret_aprojects_details->project_info_status;

                                                                    if ($aproject_status == "1") {
                                                                        $aproject_status_desc = "Active";
                                                                    } else {
                                                                        $aproject_status_desc = "Inactive";
                                                                    }
                                                                    if ($aproject_supervisor_end_date != "") {
                                                                        $date1 = date_create(date("Y-m-d", strtotime($aproject_supervisor_end_date)));
                                                                        $date2 = date_create(date("Y-m-d", strtotime(curr_date_time())));
                                                                        $diff = date_diff($date2, $date1);
                                                                        $datedifference = $diff->format("%R%a days");
                                                                    } else {
                                                                        $datedifference = "0";
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $aproject_supervisor_name; ?></td>
                                                                        <td><?php echo $aproject_supervisor_start_date; ?></td>
                                                                        <td><?php echo $project_supervisor_end_date; ?></td>
                                                                        <td><?php echo $aproject_status_desc; ?></td>
                                                                        <td>
                                                                            <a href="edit_project_supervisor.php?project_info_id=<?php echo $aproject_info_id; ?>">
                                                                                <button type="button" class="btn btn-warning btn-rounded btn-sm" id="edit_project">
                                                                                    <span>Edit</span></button>
                                                                            </a><br/><br/>
                                                                            <?php
                                                                            if ($datedifference >= 0) {
                                                                                if ($aproject_status == "1") {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-danger btn-rounded btn-sm" id="inactive_active" value="Inactive"
                                                                                            onclick="change_status(this.value, '<?php echo $aproject_info_id; ?>');">
                                                                                        <span>Inactive</span></button>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <button type="button" class="btn btn-success btn-rounded btn-sm" id="inactive_active" value="Active"
                                                                                            onclick="change_status(this.value, '<?php echo $aproject_info_id; ?>');">
                                                                                        <span>Active</span></button>
                                                                                        <?php
                                                                                    }
                                                                                } 
                                                                                ?>

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

                    function change_status(status, project_info_id) {
                        var login_user = "<?php echo $login_user; ?>";
                        var login_token = "<?php echo $login_token; ?>";

                        var values = [{session_token: login_token, session_user: login_user, project_info_id: project_info_id, status: status}];

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>/api/project/project_info/availability.php",
                            dataType: "json",
                            data: JSON.stringify(values),
                            success: function (RetVal) {
                                if (RetVal.message === "Success") {
                                    $("#success_message").text(RetVal.data);
                                    window.location.href = "view_project_supervisor.php?project_id=<?php echo $project_id; ?>";
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
    }
} else {
    header("location:../index.html");
}
?>