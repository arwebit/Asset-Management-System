<?php
$main_page = "Add employee category";
$page = "Add employee category";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if (isset($_REQUEST['create_category'])) {
        $category_name = trim($_REQUEST['category_name']);

        $createcategory_data[] = array("session_token" => $login_token, "session_user" => $login_user, "category_name" => $category_name);
        $cr_data = json_encode($createcategory_data);
        $createcategory_getData = json_decode(callAPI($cr_data, $emp_category_create_api));
        $createcategory_error = $createcategory_getData->error;
        $createcategory_message = $createcategory_getData->message;
        $createcategory_data = $createcategory_getData->data;

        if ($createcategory_error == true) {
            if ($createcategory_message == "Server error") {
                foreach ($createcategory_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($createcategory_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $category_nameErr = $dataVal->CategoryNameErr;
                }
            }
        } else {
            $successMsg = "Successfully inserted category";
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
                                            <form class="forms-sample" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="category_name">Category name</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="category_name" id="category_name" placeholder="ENTER CATEGORY NAME"
                                                                   value="<?php
                                                                   if ($createcategory_error == true) {
                                                                       echo $category_name;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $category_nameErr; ?></b>
                                                        </div>
                                                        <button type="submit" name="create_category" id="create_category" class="btn btn-primary mr-2">Save</button>
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
} else {
    header("location:../index.html");
}
?>
