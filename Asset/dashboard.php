<?php
$page = "Asset management : Dashboard";
include './api/common/global_functions.php';
include './api_links.php';

if (!is_dir("./media_files")) {
    mkdir("./media_files", 0777, true);
}
if (!is_dir("./api/employee/gatepass/qr")) {
    mkdir("./api/employee/gatepass/qr", 0777, true);
}
if (!is_dir("./api/employee/reports/report_media")) {
    mkdir("./api/employee/reports/report_media", 0777, true);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo $page; ?> </title>
        <link rel="stylesheet" href="assets/css/basic_style.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/font-awesome/css/font-awesome.min.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/feather/feather.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/mdi/css/materialdesignicons.min.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/ti-icons/css/themify-icons.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/typicons/typicons.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/simple-line-icons/css/simple-line-icons.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/css/vendor.bundle.base.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/images/favicon.png?v=<?php echo time(); ?>" />
        <script src="assets/js/anychart/anychart-base.min.js?v=<?php echo time(); ?>"></script>
    </head>
    <body onload="startTime()">
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar default-layout col-lg-12 col-12 p-0 d-flex align-items-top flex-row fixed-top">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                    <div class="me-3">
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                            <span class="icon-menu"></span>
                        </button>
                    </div>
                    <div>
                        <a class="navbar-brand brand-logo" href="dashboard.php">
                            <img src="assets/images/logo.png" alt="logo" />
                        </a>
                        <a class="navbar-brand brand-logo-mini" href="dashboard.php">
                            <img src="assets/images/logo-mini.png" alt="logo" />
                        </a>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-top">
                    <ul class="navbar-nav">
                        <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                            <h1 class="welcome-text"><span id="greetings"></span>, <span class="text-black fw-bold">Guest</span></h1>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <span id="date"></span> &nbsp;&nbsp; <span id="time"></span>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <?php
            $member_count_data[] = array();
            $mem_count_data = json_encode($member_count_data);
            $getmemcnt = json_decode(callAPI($mem_count_data, $over_user_count_api));
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

            $proj_count_data[] = array();
            $project_count_data = json_encode($proj_count_data);
            $getprjcnt = json_decode(callAPI($project_count_data, $over_project_count_api));
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

                    var project_chart = anychart.pie(prj_data);
                    project_chart
                            .title('Project records')
                            .radius('40%')
                            .innerRadius('30%');
                    project_chart.container("project_graph");
                    project_chart.draw();

                    /********************************* EMPLOYEE CHART ****************************************/
                });

            </script>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="mdi mdi-settings menu-icon"></i>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#ui-category" aria-expanded="false" aria-controls="ui-basic">
                                <i class="menu-icon mdi mdi-power"></i>
                                <span class="menu-title">Login</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="ui-category">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item"> <a class="nav-link" href="login.php?role_value=0">Login</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="login.php?role_value=-2">Superadmin login</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="login.php?role_value=-1">Admin login</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="login.php?role_value=2">Supervisor login</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="login.php?role_value=3">Employee login</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
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

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                                Designed by <a href="https://krtech.in/" target="_blank">KrTech</a> with <i class="menu-icon mdi mdi-heart"></i> from India.</span>
                            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2021. All rights reserved.</span>
                        </div>
                    </footer>
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <script src="assets/js/digital_clock.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/vendor.bundle.base.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/progressbar.min.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/off-canvas.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/hoverable-collapse.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/template.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/settings.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/todolist.js?v=<?php echo time(); ?>"></script>
        <script src="assets/js/dashboard.js?v=<?php echo time(); ?>"></script>
    </body>
</html>

