<?php
$main_page = "View user";
$page = "View user";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ((isset($_REQUEST['admin_id'])) && (isset($_REQUEST['admin_name']))) {
        $admin_id = $_REQUEST['admin_id'];
        $admin_name = $_REQUEST['admin_name'];

        $member_count_data[] = array("session_token" => $login_token, "session_user" => $login_user,
            "create_under" => $admin_id, "detail_type" => "Count");
        $mem_count_data = json_encode($member_count_data);
        $getmemcnt = json_decode(callAPI($mem_count_data, $admin_wise_user_detail_api));
        $ret_memcnt_error = $getmemcnt->error;
        $ret_memcnt_message = $getmemcnt->message;
        $ret_memcnt_data = $getmemcnt->data;

        if ($ret_memcnt_error == true) {
            $data_err = $ret_memcnt_message;
        } else {
            foreach ($ret_memcnt_data as $ret_memcnt_dataVal) {
                $memcnt_detail = $ret_memcnt_dataVal->Details;
            }
            foreach ($memcnt_detail as $memcnt_details) {
                $user_role = $memcnt_details->role_id;
                if ($user_role == 1) {
                    $hr_inactive_record = round($memcnt_details->inactive_users);
                    $hr_active_record = round($memcnt_details->active_users);
                    $hr_total_record = $hr_active_record + $hr_inactive_record;
                }
                if ($user_role == 2) {
                    $supervisor_inactive_record = round($memcnt_details->inactive_users);
                    $supervisor_active_record = round($memcnt_details->active_users);
                    $supervisor_total_record = $supervisor_active_record + $supervisor_inactive_record;
                }
                if ($user_role == 3) {
                    $employee_inactive_record = round($memcnt_details->inactive_users);
                    $employee_active_record = round($memcnt_details->active_users);
                    $employee_total_record = $employee_inactive_record + $employee_active_record;
                }
            }
        }

        $proj_count_data[] = array("session_token" => $login_token, "session_user" => $login_user,
            "create_under" => $admin_id, "detail_type" => "Count");
        $project_count_data = json_encode($proj_count_data);
        $getprjcnt = json_decode(callAPI($project_count_data, $admin_wise_project_detail_api));
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
                                                <h4 class="card-title">Employees of <?php echo $admin_name; ?></h4>
                                                <h5>
                                                    <span class="badge badge-primary text-white">
                                                        HR - <?php echo $hr_total_record; ?> &nbsp;&nbsp;(Inactive - <?php echo $hr_inactive_record; ?>, Active - <?php echo $hr_active_record; ?>)
                                                    </span> 
                                                    <span class="badge badge-info text-white">
                                                        Supervisor - <?php echo $supervisor_total_record; ?> &nbsp;&nbsp;(Inactive - <?php echo $supervisor_inactive_record; ?>, Active - <?php echo $supervisor_active_record; ?>)
                                                    </span> 
                                                    <span class="badge badge-success text-white">
                                                        Employee - <?php echo $employee_total_record; ?> &nbsp;&nbsp;(Inactive - <?php echo $employee_inactive_record; ?>, Active - <?php echo $employee_active_record; ?>)
                                                    </span>
                                                </h5>
                                                <div class="table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <?php
                                                    $ausers_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                        "create_under" => $admin_id, "detail_type" => "Details");
                                                    $ausers_recv_data = json_encode($ausers_data);
                                                    $getausersData = json_decode(callAPI($ausers_recv_data, $admin_wise_user_detail_api));
                                                    $ret_ausers_error = $getausersData->error;
                                                    $ret_ausers_message = $getausersData->message;
                                                    $ret_ausers_data = $getausersData->data;

                                                    if ($ret_ausers_error == true) {
                                                        ?>
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th style="font-weight: bolder;">Name</th>
                                                                    <th style="font-weight: bolder;">Username</th>
                                                                    <th style="font-weight: bolder;">Password</th>
                                                                    <th style="font-weight: bolder;">Email</th>
                                                                    <th style="font-weight: bolder;">Mobile</th>
                                                                    <th style="font-weight: bolder;">Role</th>
                                                                    <th style="font-weight: bolder;">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="6" align="center">No records found</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <table class="table table-hover sampleTable">
                                                            <thead>
                                                                <tr>
                                                                    <th style="font-weight: bolder;">Name</th>
                                                                    <th style="font-weight: bolder;">Username</th>
                                                                    <th style="font-weight: bolder;">Password</th>
                                                                    <th style="font-weight: bolder;">Email</th>
                                                                    <th style="font-weight: bolder;">Mobile</th>
                                                                    <th style="font-weight: bolder;">Role</th>
                                                                    <th style="font-weight: bolder;">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($ret_ausers_data as $dataVal) {
                                                                    $ret_ausers_detail = $dataVal->Details;
                                                                }
                                                                foreach ($ret_ausers_detail as $ret_ausers_details) {
                                                                    $ausers_username = $ret_ausers_details->username;
                                                                    $ausers_name = $ret_ausers_details->username;
                                                                    $ausers_password = encrypt_decrypt('decrypt', $ret_ausers_details->password);
                                                                    $amem_name = $ret_ausers_details->member_first_name . " " . $ret_ausers_details->member_middle_name . " " . $ret_ausers_details->member_last_name;
                                                                    $aemail = $ret_ausers_details->member_email;
                                                                    $amobile = $ret_ausers_details->member_mobile;
                                                                    $arole_name = $ret_ausers_details->role_name;
                                                                    $astatus = $ret_ausers_details->user_status;
                                                                    if ($astatus == 1) {
                                                                        $badge = "success";
                                                                        $status = "Active";
                                                                    } else {
                                                                        $badge = "danger";
                                                                        $status = "Inactive";
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $amem_name; ?></td>
                                                                        <td><?php echo $ausers_name; ?></td>
                                                                        <td><?php echo $ausers_password; ?></td>
                                                                        <td><?php echo $aemail; ?></td>
                                                                        <td><?php echo $amobile; ?></td>
                                                                        <td><?php echo $arole_name; ?></td>
                                                                        <td><div class="text-white badge badge-<?php echo $badge; ?>">
                                                                                <?php echo $status; ?>
                                                                            </div></td>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Projects of <?php echo $admin_name; ?></h4>
                                                <h5>
                                                    <span class="badge badge-primary text-white">
                                                        Upcoming - <?php echo $upcoming_project; ?>
                                                    </span> 
                                                    <span class="badge badge-info text-white">
                                                        Ongoing - <?php echo $ongoing_project; ?> 
                                                    </span> 
                                                    <span class="badge badge-success text-white">
                                                        Completed - <?php echo $completed_project; ?>
                                                    </span>
                                                </h5>
                                                <div class="table-responsive table-responsive-lg table-responsive-md table-responsive-xl table-responsive-sm">
                                                    <?php
                                                    $proj_detail_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                        "detail_type" => "Details", "create_under" => $admin_id);
                                                    $project_detail_data = json_encode($proj_detail_data);
                                                    $getprjdetail = json_decode(callAPI($project_detail_data, $admin_wise_project_detail_api));
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
    }
} else {
    header("location:../index.html");
}
?>