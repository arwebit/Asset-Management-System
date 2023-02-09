<?php
$main_page = "Generate report";
$page = "Generate report";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['gatepass_link_id']) {
        $gatepass_link_id = $_REQUEST['gatepass_link_id'];
        if (isset($_REQUEST['create_report'])) {
            $safety_clearence = trim($_REQUEST['safety_cl']);
            $hr_clearence = trim($_REQUEST['hr_cl']);
            $reportdt = $_REQUEST['reportdt'] == "" ? "" : date("Y-m-d H:i:s", strtotime($_REQUEST['reportdt']));
            $supervisor_name = trim($_REQUEST['supervisor_name']);
            $work_done = trim($_REQUEST['work_done']);
            $work_status = trim($_REQUEST['work_status']);
            $work_done_by = trim($_REQUEST['work_done_by']);
            $details = trim($_REQUEST['details']);
            $emp_remark = trim($_REQUEST['emp_remark']);
            $material_shortage = trim($_REQUEST['material_shortage']);
            $reference = trim($_REQUEST['reference']);
            $pending_work = trim($_REQUEST['pending_work']);

            $report_data[] = array("session_token" => $login_token, "session_user" => $login_user, "gatepass_link_id" => $gatepass_link_id, "safety_clearence" => $safety_clearence,
                "hr_clearence" => $hr_clearence, "reportdt" => $reportdt, "supervisor_name" => $supervisor_name, "work_done" => $work_done,
                "work_status" => $work_status, "work_done_by" => $work_done_by, "details" => $details, "emp_remark" => $emp_remark,
                "material_shortage" => $material_shortage, "reference" => $reference, "pending_work" => $pending_work);
            $data_cred = json_encode($report_data);
            $rep_getData = json_decode(callAPI($data_cred, $report_create_api));
            $rep_error = $rep_getData->error;
            $rep_message = $rep_getData->message;
            $rep_data = $rep_getData->data;

            if ($rep_error == true) {
                if ($rep_message == "Server error") {
                    foreach ($rep_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMsg;
                    }
                } else {
                    foreach ($rep_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $date_timeErr = $dataVal->ReportDateErr;
                        $work_doneErr = $dataVal->WorkDoneErr;
                        $work_done_byErr = $dataVal->WorkDoneByErr;
                        $detailsErr = $dataVal->DetailsErr;
                        $material_shortageErr = $dataVal->MatShortageErr;
                        $supervisor_nameErr = $dataVal->SupervisorErr;
                        $work_statusErr = $dataVal->WorkStatusErr;
                        $emp_remarkErr = $dataVal->EmpRemarkErr;
                        $referenceErr = $dataVal->ReferenceErr;
                        $pending_workErr = $dataVal->PendingWorkErr;
                    }
                }
            } else {
                $successMsg = "Successfully submitted report";
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
                                                <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="safety_cl">Safety clearence</label> <b class="text-danger"> *</b>
                                                                <select name="safety_cl" id="safety_cl" class="form-control select2">
                                                                    <option value="Y"
                                                                    <?php
                                                                    if ($rep_error == true) {
                                                                        if ($safety_clearence == "Y") {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>>YES</option>
                                                                    <option value="N"
                                                                    <?php
                                                                    if ($rep_error == true) {
                                                                        if ($safety_clearence == "N") {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>>NO</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="reportdt">Report date-time</label> <b class="text-danger"> *</b>
                                                                <input type="datetime-local" class="form-control" name="reportdt" id="reportdt" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo date("Y-m-d\TH:i", strtotime($reportdt));
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $date_timeErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="work_done">Work done</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="work_done" placeholder="ENTER WORK DONE" id="work_done" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $work_done;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $work_doneErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="work_done_by">Work done by</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="work_done_by" placeholder="ENTER WORK DONE BY" id="work_done_by" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $work_done_by;
                                                                       } else {
                                                                           echo $profile_name;
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $work_done_byErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="details">Details</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="details" placeholder="ENTER DETAILS" id="details" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $details;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $detailsErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="material_shortage">Material shortage</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="material_shortage" placeholder="ENTER MATERIAL SHORTAGE" id="material_shortage" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $material_shortage;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $material_shortageErr; ?></b>
                                                            </div>

                                                        </div>
                                                        <!-- /.col-6 -->
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="hr_cl">HR clearence</label> <b class="text-danger"> *</b>
                                                                <select name="hr_cl" id="hr_cl" class="form-control select2">
                                                                    <option value="Y" <?php
                                                                    if ($rep_error == true) {
                                                                        if ($hr_clearence == "Y") {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>>YES</option>
                                                                    <option value="N" <?php
                                                                    if ($rep_error == true) {
                                                                        if ($hr_clearence == "N") {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>>YES</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="supervisor_name">Supervisor</label> <b class="text-danger"> *</b>
                                                                <select name="supervisor_name" id="supervisor_name" class="form-control select2">
                                                                    <option value="" <?php
                                                                    if ($rep_error == true) {
                                                                        if ($supervisor_name == "") {
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
                                                                            if ($rep_error == true) {
                                                                                if ($supervisor_name == $user_name) {
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
                                                                <b class="text-danger"><?php echo $supervisor_nameErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="work_status">Work status</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="work_status" placeholder="ENTER WORK STATUS" id="work_status" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $work_status;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $work_statusErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="reference">Reference</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="reference" placeholder="ENTER REFERENCE" id="reference" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $reference;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $referenceErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pending_work">Pending work</label> <b class="text-danger"> *</b>
                                                                <input type="text" class="form-control" name="pending_work" placeholder="ENTER PENDING WORK" id="pending_work" required="required"
                                                                       value="<?php
                                                                       if ($rep_error == true) {
                                                                           echo $pending_work;
                                                                       } else {
                                                                           echo "";
                                                                       }
                                                                       ?>"/>
                                                                <b class="text-danger"><?php echo $pending_workErr; ?></b>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="emp_remark">Employee remark</label> <b class="text-danger"> *</b>
                                                                <textarea class="form-control" name="emp_remark" id="remark">
                                                                    <?php
                                                                    if ($rep_error == true) {
                                                                        echo $emp_remark;
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>
                                                                </textarea>
                                                                <b class="text-danger"><?php echo $emp_remarkErr; ?></b>
                                                            </div>
                                                        </div>
                                                        <!-- /.col-6 -->
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <button type="submit" name="create_report" id="create_report" class="btn btn-primary mr-2">
                                                                Submit</button>
                                                            <b class="text-success"><?php echo $successMsg; ?></b>
                                                            <b class="text-danger"><?php echo $errmsg; ?></b>
                                                        </div>
                                                    </div>
                                                    <!-- /.row -->
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
