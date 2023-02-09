<?php
$main_page = "Home";
$page = "Dashboard";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <?php include '../header_links.php'; ?>
        </head>
        <body onload="startTime()">
            <div class="container-scroller">
                <?php
                include './top_menu.php';
                $member_count_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                    "user_role"=>$user_role_id);
                $mem_count_data = json_encode($member_count_data);
                $getmemcnt = json_decode(callAPI($mem_count_data, $user_count_api));
                $ret_memcnt_error = $getmemcnt->error;
                $ret_memcnt_message = $getmemcnt->message;
                $ret_memcnt_data = $getmemcnt->data;

                if ($ret_memcnt_error == true) {
                    $data_err = $ret_memcnt_message;
                } else {
                    foreach ($ret_memcnt_data as $ret_memcnt_dataVal) {
                        $cnt_detail = $ret_memcnt_dataVal->Details;
                    }
                    foreach ($cnt_detail as $cnt_details) {
                        $user_role = $cnt_details->user_role;
                        if ($user_role == 1) {
                            $hr_record = $cnt_details->record;
                        }
                        if ($user_role == 2) {
                            $supervisor_record = $cnt_details->record;
                        }
                        if ($user_role == 3) {
                            $employee_record = $cnt_details->record;
                        }
                    }
                }

                $proj_count_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                    "detail_type" => "Count", "user_role" => $user_role_id);
                $project_count_data = json_encode($proj_count_data);
                $getprjcnt = json_decode(callAPI($project_count_data, $project_detail_api));
                $ret_prjcnt_error = $getprjcnt->error;
                $ret_prjcnt_message = $getprjcnt->message;
                $ret_prjcnt_data = $getprjcnt->data;

                if ($ret_prjcnt_error == true) {
                    $data_err = $ret_memcnt_message;
                } else {
                    foreach ($ret_prjcnt_data as $ret_prjcnt_dataVal) {
                        $prj_cnt = $ret_prjcnt_dataVal->Details;
                    }
                    foreach ($prj_cnt as $prj_cnts) {
                        $completed_project = $prj_cnts->completed_project;
                        $ongoing_project = $prj_cnts->ongoing_project;
                        $upcoming_project = $prj_cnts->upcoming_project;
                    }
                }
                ?>
                <script type="text/javascript">
                    var hr_record = "<?php echo $hr_record; ?>";
                    var supervisor_record = "<?php echo $supervisor_record; ?>";
                    var employee_record = "<?php echo $employee_record; ?>";

                    var completed_project = "<?php echo $completed_project; ?>";
                    var ongoing_project = "<?php echo $ongoing_project; ?>";
                    var upcoming_project = "<?php echo $upcoming_project; ?>";

                    anychart.onDocumentReady(function () {
                        var emp_data = [['HR', hr_record], ['Supervisor', supervisor_record], ['Employee', employee_record]];
                        var prj_data = [['Completed', completed_project], ['Ongoing', ongoing_project], ['Upcoming', upcoming_project]];

                        /********************************* EMPLOYEE CHART ****************************************/

                        var emp_chart = anychart.column(emp_data);
                        emp_chart
                                .title('Member records')
                        emp_chart.container("member_graph");
                        emp_chart.draw();

                        /********************************* EMPLOYEE CHART ****************************************/

                        /********************************* EMPLOYEE CHART ****************************************/

                        var project_chart = anychart.column(prj_data);
                        project_chart
                                .title('Project records')
                        project_chart.container("project_graph");
                        project_chart.draw();

                        /********************************* EMPLOYEE CHART ****************************************/
                    });

                </script>
                <!-- partial -->
                <div class="container-fluid page-body-wrapper">
                    <?php include './side_menu.php'; ?>
                    <!-- partial -->
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="home-tab">
                                        <div class="d-sm-flex align-items-center justify-content-between">

                                            <!--<div>
                                                <div class="btn-wrapper">
                                                    <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
                                                    <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                                                    <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
                                                </div>
                                            </div>-->
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div id="member_graph" style="width: 100%;height: 100%;margin: 0;padding: 0;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="card card-rounded ">
                                                    <div class="card-body">
                                                        <div id="project_graph" style="width: 100%;height: 100%;margin: 0;padding: 0;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <br/>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card card-rounded table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <h4>Project status</h4>
                                                        </div>
                                                        <div class="table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                            <?php
                                                            $proj_detail_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                "detail_type" => "Details", "user_role" => $user_role_id);
                                                            $project_detail_data = json_encode($proj_detail_data);
                                                            $getprjdetail = json_decode(callAPI($project_detail_data, $project_detail_api));
                                                            $ret_prjdetail_error = $getprjdetail->error;
                                                            $ret_prjdetail_message = $getprjdetail->message;
                                                            $ret_prjdetail_data = $getprjdetail->data;

                                                            if ($ret_prjdetail_error == true) {
                                                                ?>
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Project name</th>
                                                                            <th>Project period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td align="center" colspan="3">No records found</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            <?php } else {
                                                                ?>
                                                                <table class="table table-hover sampleTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Project name</th>
                                                                            <th>Project period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        foreach ($ret_prjdetail_data as $ret_prjdetail_dataVal) {
                                                                            $prj_detail = $ret_prjdetail_dataVal->Details;
                                                                        }
                                                                        foreach ($prj_detail as $prj_details) {
                                                                            $proj_project_name = $prj_details->project_name;
                                                                            $proj_project_location = $prj_details->project_location;
                                                                            $proj_project_status = $prj_details->project_status;
                                                                            $project_start_date = date("d/m/Y", strtotime($prj_details->project_start_date));
                                                                            $project_end_date = date("d/m/Y", strtotime($prj_details->project_end_date));
                                                                            $proj_service_period = $project_start_date . " - " . $project_end_date;
                                                                            $proj_diff_days = $prj_details->age;
                                                                            if ($proj_project_status != 0) {
                                                                                if (date("Y-m-d", strtotime($prj_details->project_start_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
                                                                                    if ($proj_diff_days == 0) {
                                                                                        $project_status = "Completing today";
                                                                                        $badge_status = "danger";
                                                                                    } else if ($proj_diff_days > 0) {
                                                                                        $project_status = "Completing in " . $proj_diff_days . " day(s)";
                                                                                        $badge_status = "danger";
                                                                                    } else {
                                                                                        $project_status = "Completed";
                                                                                        $badge_status = "success";
                                                                                    }
                                                                                } else {
                                                                                    $project_status = "Upcoming";
                                                                                    $badge_status = "warning";
                                                                                }
                                                                            } else {
                                                                                $project_status = "Completed";
                                                                                $badge_status = "success";
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $proj_project_name; ?></td>
                                                                                <td><?php echo $proj_service_period; ?></td>
                                                                                <td>
                                                                                    <div class="text-white badge badge-<?php echo $badge_status; ?>">
                                                                                        <?php echo $project_status; ?>
                                                                                    </div>
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
                                        </div><br/>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card card-rounded table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <h4>Supervisor status</h4>
                                                        </div>
                                                        <div class="table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">

                                                            <?php
                                                            $sup_status_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                "employee_supervisor" => "Supervisor", "user_role" => $user_role_id);
                                                            $supstatus_data = json_encode($sup_status_data);
                                                            $supStatusData = json_decode(callAPI($supstatus_data, $emp_sup_details_api));
                                                            $ret_supstatus_error = $supStatusData->error;
                                                            $ret_supstatus_message = $supStatusData->message;
                                                            $ret_supstatus_data = $supStatusData->data;

                                                            if ($ret_supstatus_error == true) {
                                                                ?>
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Supervisor name</th>
                                                                            <th>Project name</th>
                                                                            <th>Service period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td align="center" colspan="4">No records found</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <table class="table table-hover sampleTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Supervisor name</th>
                                                                            <th>Project name</th>
                                                                            <th>Service period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        foreach ($ret_supstatus_data as $sup_status_dataVal) {
                                                                            $ret_supstatus_detail = $sup_status_dataVal->Details;
                                                                        }
                                                                        foreach ($ret_supstatus_detail as $ret_supstatus_details) {
                                                                            $sup_name = $ret_supstatus_details->member_first_name . " " . $ret_supstatus_details->member_middle_name . " " . $ret_supstatus_details->member_last_name;
                                                                            $sup_project_name = $ret_supstatus_details->project_name;
                                                                            $sup_project_location = $ret_supstatus_details->project_location;
                                                                            $project_supervisor_start_date = date("d/m/Y", strtotime($ret_supstatus_details->project_supervisor_start_date));
                                                                            $project_supervisor_end_date = date("d/m/Y", strtotime($ret_supstatus_details->project_supervisor_end_date));
                                                                            $sup_service_period = $project_supervisor_start_date . " - " . $project_supervisor_end_date;
                                                                            $sup_diff_days = $ret_supstatus_details->age;

                                                                            if (date("Y-m-d", strtotime($ret_supstatus_details->project_supervisor_start_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
                                                                                if ($sup_diff_days == 0) {
                                                                                    $sup_status = "Completing today";
                                                                                    $badge_status = "danger";
                                                                                } else if ($sup_diff_days > 0) {
                                                                                    $sup_status = "Free in " . $sup_diff_days . " day(s)";
                                                                                    $badge_status = "danger";
                                                                                } else {
                                                                                    $sup_status = "Completed";
                                                                                    $badge_status = "success";
                                                                                }
                                                                            } else {
                                                                                $sup_status = "Upcoming";
                                                                                $badge_status = "warning";
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $sup_name; ?></td>
                                                                                <td><?php echo $sup_project_name; ?></td>
                                                                                <td><?php echo $sup_service_period; ?></td>
                                                                                <td>
                                                                                    <div class="text-white badge badge-<?php echo $badge_status; ?>">
                                                                                        <?php echo $sup_status; ?>
                                                                                    </div>
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
                                        </div><br/>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card card-rounded table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <h4>Employee status</h4>
                                                        </div>
                                                        <div class="table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">

                                                            <?php
                                                            $emp_status_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                "employee_supervisor" => "Employee", "user_role" => $user_role_id);
                                                            $empstatus_data = json_encode($emp_status_data);
                                                            $empStatusData = json_decode(callAPI($empstatus_data, $emp_sup_details_api));
                                                            $ret_empstatus_error = $empStatusData->error;
                                                            $ret_empstatus_message = $empStatusData->message;
                                                            $ret_empstatus_data = $empStatusData->data;

                                                            if ($ret_empstatus_error == true) {
                                                                ?>
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Employee name</th>
                                                                            <th>Project name</th>
                                                                            <th>Service period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td align="center" colspan="4">No records found</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <table class="table table-hover sampleTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Employee name</th>
                                                                            <th>Project name</th>
                                                                            <th>Service period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        foreach ($ret_empstatus_data as $emp_status_dataVal) {
                                                                            $ret_empstatus_detail = $emp_status_dataVal->Details;
                                                                        }
                                                                        foreach ($ret_empstatus_detail as $ret_empstatus_details) {
                                                                            $emp_name = $ret_empstatus_details->member_first_name . " " . $ret_empstatus_details->member_middle_name . " " . $ret_empstatus_details->member_last_name;
                                                                            $emp_project_name = $ret_empstatus_details->project_name;
                                                                            $emp_project_location = $ret_empstatus_details->project_location;
                                                                            $emp_gatepass_status = $ret_empstatus_details->gatepass_status;
                                                                            $gatepass_start_date = date("d/m/Y", strtotime($ret_empstatus_details->gatepass_start_date));
                                                                            $gatepass_end_date = date("d/m/Y", strtotime($ret_empstatus_details->gatepass_end_date));
                                                                            $emp_service_period = $gatepass_start_date . " - " . $gatepass_end_date;
                                                                            $emp_diff_days = $ret_empstatus_details->age;

                                                                            if (date("Y-m-d", strtotime($ret_empstatus_details->gatepass_start_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
                                                                                if ($emp_gatepass_status == 2) {
                                                                                    $emp_status = "Pending for approval";
                                                                                $badge_status = "warning";
                                                                                }else{
                                                                                if ($emp_diff_days == 0) {
                                                                                    $emp_status = "Completing today";
                                                                                    $badge_status = "danger";
                                                                                } else if ($emp_diff_days > 0) {
                                                                                    $emp_status = "Free in " . $emp_diff_days . " day(s)";
                                                                                    $badge_status = "danger";
                                                                                } else {
                                                                                    $emp_status = "Completed";
                                                                                    $badge_status = "success";
                                                                                }
                                                                            }
                                                                            }else {
                                                                                 if ($emp_gatepass_status == 2) {
                                                                                    $emp_status = "Pending for approval";
                                                                                $badge_status = "warning";
                                                                                }else{
                                                                                $emp_status = "Upcoming";
                                                                                $badge_status = "warning";
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $emp_name; ?></td>
                                                                                <td><?php echo $emp_project_name; ?></td>
                                                                                <td><?php echo $emp_service_period; ?></td>
                                                                                <td>
                                                                                    <div class="text-white badge badge-<?php echo $badge_status; ?>">
                                                                                        <?php echo $emp_status; ?>
                                                                                    </div>
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
        </body>
    </html>
    <?php
} else {
    header("location:../index.html");
}
?>
