<?php
$main_page = "Add media for daily report";
$page = "Add media for daily report";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    $rep_error = false;
    if ($_REQUEST['daily_report_id']) {
        $daily_report_id = $_REQUEST['daily_report_id'];

        if (isset($_REQUEST['upload_files'])) {

            if ($_FILES["upfile"]["size"] > 0) {
                $cfile = new CurlFile($_FILES["upfile"]["tmp_name"], $_FILES["upfile"]["type"], $_FILES["upfile"]["name"]);
            } else {
                $cfile = "";
            }
            $session_data[] = array("session_token" => $login_token, "session_user" => $login_user, "daily_report_id" => $daily_report_id);
            $data_cred = json_encode($session_data);
            $media_data = array("data_cred" => $data_cred, "file_upload" => $cfile);
            $med_getData = json_decode(callMediaAPI($media_data, $report_media_create_api));
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
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="up_file">Upload file</label> <b class="text-danger"> *</b>
                                                                <input type="file" class="form-control" name="upfile" id="upfile" required="required"/>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Daily report media</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Sl no</th>
                                                                <th style="font-weight: bolder;">Media</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $amed_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                "report_id" => $daily_report_id, "status" => "Active");
                                                            $amed_recv_data = json_encode($amed_data);
                                                            $getausersData = json_decode(callAPI($amed_recv_data, $report_media_detail_api));
                                                            $ret_amed_error = $getausersData->error;
                                                            $ret_amed_message = $getausersData->message;
                                                            $ret_amed_data = $getausersData->data;
                                                            if ($ret_amed_error == true) {
                                                                $ausersErr = $ret_amed_status;
                                                            } else {
                                                                foreach ($ret_amed_data as $dataVal) {
                                                                    $ret_amed_detail = $dataVal->Details;
                                                                }
                                                                $aslno = 0;
                                                                foreach ($ret_amed_detail as $ret_amed_details) {
                                                                    $aslno++;
                                                                    $amedia_id = $ret_amed_details->report_media_id;
                                                                    $amedia_path = $ret_amed_details->report_media_path;
                                                                    $amedia_ext = explode("/", $ret_amed_details->report_media_type)[1];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $aslno; ?></td>
                                                                        <?php
                                                                        if ((strtolower($amedia_ext) == 'jpg') || (strtolower($amedia_ext) == 'jpeg') || (strtolower($amedia_ext) == 'png') || (strtolower($amedia_ext) == 'gif')) {
                                                                            ?>
                                                                            <td>
                                                                    <center><img src="../api/employee/reports/<?php echo $amedia_path; ?>?v=<?php echo time(); ?>" ></center>
                                                                    </td>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <td>
                                                                    <center>  <embed src="../api/employee/reports/<?php echo $amedia_path; ?>?v=<?php echo time(); ?>" width="100" height="100">
                                                                    </center>   </td>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-rounded" id="remove"
                                                                            onclick="delete_media('<?php echo $daily_report_id; ?>', '<?php echo $amedia_id; ?>', '<?php echo $amedia_path; ?>');">
                                                                        <i class="fa fa-close"></i></button></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
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
                <script type="text/javascript">

                    function delete_media(daily_report_id, media_id, media_path) {
                        var login_user = "<?php echo $login_user; ?>";
                        var login_token = "<?php echo $login_token; ?>";
                        var conf = confirm("Are you sure you want to delete?");
                        if (conf) {
                            var values = [{session_token: login_token, session_user: login_user, daily_report_id: daily_report_id, report_media_id: media_id, report_media_path: media_path}];

                            $.ajax({
                                type: "POST",
                                url: "<?php echo site_url(); ?>/api/employee/reports/delete_media.php ",
                                dataType: "json",
                                data: JSON.stringify(values),
                                success: function (RetVal) {
                                    if (RetVal.message === "Success") {
                                        $("#success_message").text(RetVal.data);
                                        window.location.href = "report_media.php?daily_report_id=<?php echo $daily_report_id; ?>";
                                    } else {
                                        alert(RetVal.message);
                                    }
                                }
                            });
                        }
                    }
                </script>
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>
