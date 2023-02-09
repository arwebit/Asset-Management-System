<?php
$main_page = "Add media";
$page = "Add media";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if (isset($_REQUEST['upload_files'])) {

        if ($_FILES["upfile"]["size"] > 0) {
            $cfile = new CurlFile($_FILES["upfile"]["tmp_name"], $_FILES["upfile"]["type"], $_FILES["upfile"]["name"]);
        } else {
            $cfile = "";
        }
        $session_data[] = array("session_token" => $login_token, "session_user" => $login_user);
        $data_cred = json_encode($session_data);
        $media_data = array("data_cred" => $data_cred, "file_upload" => $cfile);
        $med_getData = json_decode(callMediaAPI($media_data, $media_create_api));
        $med_error = $med_getData->error;
        $med_message = $med_getData->message;
        $med_data = $med_getData->data;

        if ($med_error == true) {
            if ($med_message == "Server error") {
                foreach ($med_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($med_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $mediaErr = $dataVal->MediaErr;
                }
            }
        } else {
            $successMsg = "Successfully uploaded";
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
                                            <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="up_file">Upload file</label> <b class="text-danger"> *</b>
                                                            <input type="file" class="form-control file-upload-info" name="upfile" id="upfile" required="required"/>
                                                            <b class="text-danger"><?php echo $mediaErr; ?></b>
                                                        </div>

                                                        <button type="submit" name="upload_files" id="upload_files" class="btn btn-primary mr-2">
                                                            Upload</button>
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
