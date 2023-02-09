<?php
$main_page = "Update employee category";
$page = "Update employee category";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    $updatecategory_error = false;
    if ($_REQUEST['category_id']) {
        $category_id = $_REQUEST['category_id'];
        if (isset($_REQUEST['save_category'])) {
            $category_name = trim($_REQUEST['category_name']);
            $updatecategory_data[] = array("session_token" => $login_token, "session_user" => $login_user, "category_id" => $category_id,
                "category_name" => $category_name);
            $rp_data = json_encode($updatecategory_data);
            $updatecategory_getData = json_decode(callAPI($rp_data, $emp_category_update_api));
            $updatecategory_error = $updatecategory_getData->error;
            $updatecategory_message = $updatecategory_getData->message;
            $updatecategory_data = $updatecategory_getData->data;


            if ($updatecategory_error == true) {
                if ($updatecategory_message == "Server error") {
                    foreach ($updatecategory_data as $dataVal) {
                        $errMsg = $dataVal->ErrorMessage;
                    }
                } else {
                    foreach ($updatecategory_data as $dataVal) {
                        $tokenErr = $dataVal->TokenErr;
                        $category_nameErr = $dataVal->CategoryNameErr;
                        $category_locationErr = $dataVal->CategoryLocationErr;
                        $category_start_dateErr = $dataVal->CategoryStartDateErr;
                    }
                }
            } else {
                $successMsg = "Successfully updated category";
            }
        }
        $acategorys_data[] = array("session_token" => $login_token, "session_user" => $login_user, "category_id" => $category_id);
        $acategorys_recv_data = json_encode($acategorys_data);
        $getacategorysData = json_decode(callAPI($acategorys_recv_data, $emp_selected_category_detail_api));
        $ret_acategorys_error = $getacategorysData->error;
        $ret_acategorys_message = $getacategorysData->message;
        $ret_acategorys_data = $getacategorysData->data;
        if ($ret_acategorys_error == true) {
            $errMsg = $ret_acategorys_message;
        } else {
            foreach ($ret_acategorys_data as $dataVal) {
                $categoryDetail = $dataVal->Details;
            }
            foreach ($categoryDetail as $categoryDetailVal) {
                $fcategory_name = $categoryDetailVal->category_name;
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
                                                <form class="forms-sample" action="" method="post" id="profile_update">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="category_name">Category name</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="category_name" id="category_name" placeholder="ENTER CATEGORY NAME"
                                                                   value="<?php
                                                                   if ($updatecategory_error == false) {
                                                                       echo $fcategory_name;
                                                                   } else {
                                                                       echo $category_name;
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $category_nameErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit" name="save_category" id="save_category" class="btn btn-primary mr-2">
                                                                Save</button>
                                                        </div>

                                                        <b class="text-success"><?php echo $successMsg; ?></b>
                                                        <b class="text-danger"><?php echo $errmsg; ?></b>
                                                    </div>
                                                </div>
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
                ?>
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>
