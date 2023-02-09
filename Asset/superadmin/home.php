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
                        if ($user_role == -1) {
                            $admin_record = $cnt_details->record;
                        }
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
                    "detail_type" => "Count", "user_role"=>$user_role_id);
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
                    var admin_record = "<?php echo $admin_record; ?>";
                    var hr_record = "<?php echo $hr_record; ?>";
                    var supervisor_record = "<?php echo $supervisor_record; ?>";
                    var employee_record = "<?php echo $employee_record; ?>";

                    var completed_project = "<?php echo $completed_project; ?>";
                    var ongoing_project = "<?php echo $ongoing_project; ?>";
                    var upcoming_project = "<?php echo $upcoming_project; ?>";

                    anychart.onDocumentReady(function () {
                        var emp_data = [['Admin', admin_record], ['HR', hr_record], ['Supervisor', supervisor_record], ['Employee', employee_record]];
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
