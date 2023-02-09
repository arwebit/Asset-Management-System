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
                $sup_ongoing_project_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                    "employee_supervisor" => "Supervisor", "user_role" => $user_role_id);
                $sup_encode_data = json_encode($sup_ongoing_project_data);
                $getsupopstat = json_decode(callAPI($sup_encode_data, $ongoing_project_search_api));
                $ret_sup_ongprj_error = $getsupopstat->error;
                $ret_sup_ongprj_message = $getsupopstat->message;
                $ret_sup_ongprj_data = $getsupopstat->data;

                if ($ret_sup_ongprj_error == true) {
                    $ongoing_project_name = "Not assinged to any projects till";
                    $ongoing_project_location = "Not assinged to any projects till";
                    $ongoing_project_remaining_days = "Will activate once assigned";
                } else {
                    foreach ($ret_sup_ongprj_data as $supdataVal) {
                        $ret_sup_ongprj_detail = $supdataVal->Details;
                    }
                    foreach ($ret_sup_ongprj_detail as $ret_sup_ongprj_details) {
                        $sup_ongoing_project_name = $ret_sup_ongprj_details->project_name;
                        $sup_ongoing_project_location = $ret_sup_ongprj_details->project_location;
                        $sup_ongoing_project_remaining_days = $ret_sup_ongprj_details->age;
                    }
                }

                $sup_profiledata[] = array("session_token" => $login_token, "session_user" => $login_user,
                    "emp_type" => "Supervisor", "user_role" => $user_role_id);
                $sup_profile_data = json_encode($sup_profiledata);
                $sup_profileData = json_decode(callAPI($sup_profile_data, $profile_search_api));
                $ret_supprof_error = $sup_profileData->error;
                $ret_supprof_message = $sup_profileData->message;
                $ret_supprof_data = $sup_profileData->data;

                if ($ret_supprof_error == true) {
                    $sup_profErr = $ret_supprof_message;
                } else {
                    foreach ($ret_supprof_data as $supprof_dataVal) {
                        $sup_profile_detail = $supprof_dataVal->Details;
                    }
                    foreach ($sup_profile_detail as $sup_profile_details) {
                        $sup_total_project = $sup_profile_details->total_project;
                        $sup_completed_project = $sup_profile_details->completed_project;
                        $sup_ongoing_project = $sup_profile_details->ongoing_project;
                        $sup_upcoming_project = $sup_profile_details->upcoming_project;
                    }
                }
                ?>
                <script type="text/javascript">
                    var completed = "<?php echo $sup_completed_project; ?>";
                    var ongoing = "<?php echo $sup_ongoing_project; ?>";
                    var upcoming = "<?php echo $sup_upcoming_project; ?>";

                    anychart.onDocumentReady(function () {
                        var donut_data = [['Completed', completed], ['Ongoing', ongoing], ['Upcoming', upcoming]];
                        var bar_data = [['Completed', completed], ['Ongoing', ongoing], ['Upcoming', upcoming]];

                        /********************************* DONUT CHART ****************************************/

                        var donut_chart = anychart.pie(donut_data);
                        donut_chart
                                .title('No. of projects supervised')
                                .radius('40%')
                                .innerRadius('30%');
                        donut_chart.container('donut_chart');
                        donut_chart.draw();

                        /********************************* DONUT CHART ****************************************/

                        /********************************* BAR CHART ****************************************/

                        var bar_chart = anychart.column(bar_data);
                        bar_chart
                                .title('No. of projects supervised')
                        bar_chart.container("bar_chart");
                        bar_chart.draw();

                        /********************************* BAR CHART ****************************************/
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
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div class="table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                            <div id="donut_chart" style="width: 100%;height: 100%;margin: 0;padding: 0;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 grid-margin stretch-card">
                                                <div class="card card-rounded">
                                                    <div class="card-body">
                                                        <div id="bar_chart" style="width: 100%;height: 100%;margin: 0;padding: 0;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row flex-grow">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                                <div class="card card-rounded table-darkBGImg">
                                                    <div class="card-body">
                                                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                                            <h3 class="text-white upgrade-info mb-0">
                                                                Ongoing project name : <?php echo $sup_ongoing_project_name; ?><br />
                                                                Ongoing project location : <?php echo $sup_ongoing_project_location; ?>
                                                            </h3><br /><br />
                                                            <h4 class="text-white upgrade-info mb-0">
                                                                My service completed in (in days) : <span class="fw-bold"> <?php echo $sup_ongoing_project_remaining_days; ?></span>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card card-rounded table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <h4>My status</h4>
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
                                                                            <th>Project name</th>
                                                                            <th>Service period</th>
                                                                            <th>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="3" align="center">No records found</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <table class="table table-hover sampleTable">
                                                                    <thead>
                                                                        <tr>
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
                                        </div><br />
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
                                                                                } else {
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
                                                                            } else {
                                                                                if ($emp_gatepass_status == 2) {
                                                                                    $emp_status = "Pending for approval";
                                                                                    $badge_status = "warning";
                                                                                } else {
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
