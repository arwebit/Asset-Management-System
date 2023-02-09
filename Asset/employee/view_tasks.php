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
                                            <h4 class="card-title"><?php echo $main_page; ?></h4>
                                            <div class="table-responsive">

                                                <?php
                                                $atasks_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "task_master_id" => "", "user_role_id" => $user_role_id);
                                                $atasks_recv_data = json_encode($atasks_data);
                                                $getatasksData = json_decode(callAPI($atasks_recv_data, $task_detail_api));
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
                                                                $atask_detail_id = $ret_atasks_details->task_detail_id;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $aproject_name; ?></td>
                                                                    <td><?php echo $acategory_name; ?></td>
                                                                    <td><?php echo $atask_description; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        if ($atask_detail_id == "" || $atask_detail_id == null) {
                                                                            ?>
                                                                            <a href="add_task_details.php?task_master_id=<?php echo $atask_master_id; ?>">
                                                                                <button type="button" class="btn btn-info btn-rounded btn-sm" id="entry_task_detail">
                                                                                    <span>Entry</span></button>
                                                                            </a>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <a href="edit_task_details.php?task_detail_id=<?php echo $atask_detail_id; ?>">
                                                                                <button type="button" class="btn btn-info btn-rounded btn-sm" id="edit_task_detail">
                                                                                    <span>Edit</span></button>
                                                                            </a><br /><br />
                                                                            <a href="view_task_details.php?task_detail_id=<?php echo $atask_detail_id; ?>">
                                                                                <button type="button" class="btn btn-warning btn-rounded btn-sm" id="view_task_detail">
                                                                                    <span>View details</span></button>
                                                                            </a>
                                                                            <?php
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

        </body>

    </html>
    <?php
} else {
    header("location:../index.html");
}
?>