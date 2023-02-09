<?php
$main_page = "Manual details";
$page = "Manual details";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_GET['manual_id']) {
        $manual_id = $_GET['manual_id'];

        $amanuals_data[] = array("session_token" => $login_token, "session_user" => $login_user, "manual_id" => $manual_id);
        $amanuals_recv_data = json_encode($amanuals_data);
        $getamanualsData = json_decode(callAPI($amanuals_recv_data, $selected_manual_detail_api));
        $ret_amanuals_error = $getamanualsData->error;
        $ret_amanuals_message = $getamanualsData->message;
        $ret_amanuals_data = $getamanualsData->data;
        if ($ret_amanuals_error == true) {
            $errMsg = $ret_amanuals_message;
        } else {
            foreach ($ret_amanuals_data as $dataVal) {
                $manualDetail = $dataVal->Details;
            }
            foreach ($manualDetail as $manualDetailVal) {
                $fproject_id = $manualDetailVal->project_id;
                $fproject_name = $manualDetailVal->project_name;
                $fmanual_title = $manualDetailVal->manual_title;
                $fmanual_descr = $manualDetailVal->manual_descr;
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">

            <head>
                <!-- Required meta tags -->
                <?php
                include '../header_links.php';
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
                                                <center>
                                                    <span class="text-primary" style="font-weight: bold; font-size: 25px; text-decoration: underline;">
                                                        <?php echo $fmanual_title; ?>
                                                    </span>
                                                </center><br /><br/>
                                                <?php echo $fmanual_descr; ?>
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
                ?>
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>
