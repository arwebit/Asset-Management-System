<?php
$main_page = "View tasks";
$page = "View tasks";
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
                                            <h4 class="card-title">Tasks</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $atasks_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "user_role" => $user_role_id);
                                                $atasks_recv_data = json_encode($atasks_data);
                                                $getatasksData = json_decode(callAPI($atasks_recv_data, $all_task_detail_api));
                                                $ret_atasks_error = $getatasksData->error;
                                                $ret_atasks_message = $getatasksData->message;
                                                $ret_atasks_data = $getatasksData->data;

                                                if ($ret_atasks_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Category name</th>
                                                                <th style="font-weight: bolder;">Task description</th>
                                                                <th style="font-weight: bolder;">Action</th>
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
                                                                <th style="font-weight: bolder;">Project name</th>
                                                                <th style="font-weight: bolder;">Category name</th>
                                                                <th style="font-weight: bolder;">Task description</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_atasks_data as $dataVal) {
                                                                $ret_atasks_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_atasks_detail as $ret_atasks_details) {
                                                                $atask_master_id = $ret_atasks_details->task_master_id;
                                                                $aproject_name = $ret_atasks_details->project_name;
                                                                $acategory_name = $ret_atasks_details->category_name;
                                                                $atask_description = $ret_atasks_details->task_description;
                                                                $acreate_user = $ret_atasks_details->create_user;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $aproject_name; ?></td>
                                                                    <td><?php echo $acategory_name; ?></td>
                                                                    <td><?php echo $atask_description; ?></td>
                                                                    <td align="center">
                                                                      <a href="edit_tasks.php?task_master_id=<?php echo $atask_master_id; ?>">
                                                                                <button type="button" class="btn btn-info btn-rounded btn-sm" id="edit_task_master">
                                                                                    <span>Edit</span></button>
                                                                            </a><br /><br />
                                                                        <a href="view_task_emp_details.php?task_master_id=<?php echo $atask_master_id; ?>">
                                                                            <button type="button" class="btn btn-warning btn-rounded btn-sm" id="view_task_detail">
                                                                                <span>View details</span></button>
                                                                        </a>
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
} else {
    header("location:../index.html");
}
?>