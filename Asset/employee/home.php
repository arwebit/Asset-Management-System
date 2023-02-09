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
                $emp_ongoing_project_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                    "employee_supervisor" => "Employee", "user_role" => $user_role_id);
                $emp_encode_data = json_encode($emp_ongoing_project_data);
                $getempopstat = json_decode(callAPI($emp_encode_data, $ongoing_project_search_api));
                $ret_emp_ongprj_error = $getempopstat->error;
                $ret_emp_ongprj_message = $getempopstat->message;
                $ret_emp_ongprj_data = $getempopstat->data;

                if ($ret_emp_ongprj_error == true) {
                    $ongoing_project_name = "Not assinged to any projects till";
                    $ongoing_project_location = "Not assinged to any projects till";
                    $ongoing_project_remaining_days = "Will activate once assigned";
                } else {
                    foreach ($ret_emp_ongprj_data as $empdataVal) {
                        $ret_emp_ongprj_detail = $empdataVal->Details;
                    }
                    foreach ($ret_emp_ongprj_detail as $ret_emp_ongprj_details) {
                        $emp_ongoing_project_name = $ret_emp_ongprj_details->project_name;
                        $emp_ongoing_project_location = $ret_emp_ongprj_details->project_location;
                        $emp_ongoing_project_remaining_days = $ret_emp_ongprj_details->age;
                    }
                }

                $emp_profiledata[] = array("session_token" => $login_token, "session_user" => $login_user,
                    "emp_type" => "Employee", "user_role" => $user_role_id);
                $emp_profile_data = json_encode($emp_profiledata);
                $emp_profileData = json_decode(callAPI($emp_profile_data, $profile_search_api));
                $ret_empprof_error = $emp_profileData->error;
                $ret_empprof_message = $emp_profileData->message;
                $ret_empprof_data = $emp_profileData->data;

                if ($ret_empprof_error == true) {
                    $emp_profErr = $ret_empprof_message;
                } else {
                    foreach ($ret_empprof_data as $empprof_dataVal) {
                        $emp_profile_detail = $empprof_dataVal->Details;
                    }
                    foreach ($emp_profile_detail as $emp_profile_details) {
                        $emp_total_project = $emp_profile_details->total_project;
                        $emp_completed_project = $emp_profile_details->completed_project;
                        $emp_ongoing_project = $emp_profile_details->ongoing_project;
                        $emp_upcoming_project = $emp_profile_details->upcoming_project;
                    }
                }
                ?>
                <script type="text/javascript">
                    var completed = "<?php echo $emp_completed_project; ?>";
                    var ongoing = "<?php echo $emp_ongoing_project; ?>";
                    var upcoming = "<?php echo $emp_upcoming_project; ?>";

                    anychart.onDocumentReady(function () {
                        var donut_data = [['Completed', completed], ['Ongoing', ongoing], ['Upcoming', upcoming]];
                        var bar_data = [['Completed', completed], ['Ongoing', ongoing], ['Upcoming', upcoming]];

                        /********************************* DONUT CHART ****************************************/

                        var donut_chart = anychart.pie(donut_data);
                        donut_chart
                                .title('No. of projects assigned')
                                .radius('40%')
                                .innerRadius('30%');
                        donut_chart.container('donut_chart');
                        donut_chart.draw();

                        /********************************* DONUT CHART ****************************************/

                        /********************************* BAR CHART ****************************************/

                        var bar_chart = anychart.column(bar_data);
                        bar_chart
                                .title('No. of projects assigned')
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
                                                                Ongoing project name : <?php echo $emp_ongoing_project_name; ?><br />
                                                                Ongoing project location : <?php echo $emp_ongoing_project_location; ?>
                                                            </h3><br /><br />
                                                            <h4 class="text-white upgrade-info mb-0">
                                                                My service completed in (in days) : <span class="fw-bold"> <?php echo $emp_ongoing_project_remaining_days; ?></span>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                <div class="card card-rounded table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <div class="card-body">
                                                        <div class="card-title">
                                                            <h4>My status</h4>
                                                        </div>
                                                        <div class="table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
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
                                                                        foreach ($ret_empstatus_data as $emp_status_dataVal) {
                                                                            $ret_empstatus_detail = $emp_status_dataVal->Details;
                                                                        }
                                                                        foreach ($ret_empstatus_detail as $ret_empstatus_details) {
                                                                            $emp_project_name = $ret_empstatus_details->project_name;
                                                                            $emp_project_location = $ret_empstatus_details->project_location;
                                                                            $gatepass_start_date = date("d/m/Y", strtotime($ret_empstatus_details->gatepass_start_date));
                                                                            $gatepass_end_date = date("d/m/Y", strtotime($ret_empstatus_details->gatepass_end_date));
                                                                            $emp_service_period = $gatepass_start_date . " - " . $gatepass_end_date;
                                                                            $emp_diff_days = $ret_empstatus_details->age;

                                                                            if (date("Y-m-d", strtotime($ret_empstatus_details->gatepass_start_date)) <= date("Y-m-d", strtotime(curr_date_time()))) {
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
                                                                            } else {
                                                                                $emp_status = "Upcoming";
                                                                                $badge_status = "warning";
                                                                            }
                                                                            ?>
                                                                            <tr>
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
