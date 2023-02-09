<?php
$main_page = "View media";
$page = "View media";
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
                                            <h4 class="card-title">Media</h4>
                                            <div class="table-responsive">
                                                <?php
                                                $amed_data[] = array("session_token" => $login_token, "session_user" => $login_user);
                                                $amed_recv_data = json_encode($amed_data);
                                                $getausersData = json_decode(callAPI($amed_recv_data, $media_details_api));
                                                $ret_amed_error = $getausersData->error;
                                                $ret_amed_message = $getausersData->message;
                                                $ret_amed_data = $getausersData->data;
                                                if ($ret_amed_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Uploaded by</th>
                                                                <th style="font-weight: bolder;">Context</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="3" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Uploaded by</th>
                                                                <th style="font-weight: bolder;">Context</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_amed_data as $dataVal) {
                                                                $ret_amed_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_amed_detail as $ret_amed_details) {
                                                                $amedia_id = $ret_amed_details->media_id;
                                                                $auploaded_by = $ret_amed_details->member_first_name . " " . $ret_amed_details->member_middle_name . " " . $ret_amed_details->member_last_name;
                                                                $amedia_path = $ret_amed_details->media_path;
                                                                $amedia_ext = explode("/", $ret_amed_details->media_extension)[1];
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $auploaded_by; ?></td>
                                                                    <?php
                                                                    if ((strtolower($amedia_ext) == 'jpg') || (strtolower($amedia_ext) == 'jpeg') || (strtolower($amedia_ext) == 'png') || (strtolower($amedia_ext) == 'gif')) {
                                                                        ?>
                                                                        <td>
                                                                <center>
                                                                    <img src="../<?php echo $amedia_path; ?>?v=<?php echo time(); ?>"/></center>
                                                                </td>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <td>
                                                                <center>
                                                                    <embed src="../<?php echo $amedia_path; ?>?v=<?php echo time(); ?>">
                                                                </center>   </td>
                                                                <?php
                                                            }
                                                            ?>
                                                            <td align="center">
                                                                <a href="../<?php echo $amedia_path; ?>" target="_blank">
                                                                    <button type="button" class="btn btn-warning btn-rounded btn-sm">View</button>   
                                                                </a>    <br/><br />  
                                                                <button type="button" class="btn btn-danger btn-rounded btn-sm" id="remove"
                                                                        onclick="delete_media('<?php echo $amedia_id; ?>', '<?php echo $amedia_path; ?>');">
                                                                    Delete</button> <br/><br />
                                                                <input type="hidden" value="<?php echo site_url() . "/" . $amedia_path; ?>"
                                                                       id="copy_<?php echo $aslno; ?>" />
                                                                <button type="button" class="btn btn-info btn-rounded btn-sm" id="copy"
                                                                        onclick="copy_link('copy_<?php echo $aslno; ?>');">
                                                                    Copy</button>
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
            <script type="text/javascript">

                function delete_media(media_id, media_path) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";
                    var conf = confirm("Are you sure you want to delete?");
                    if (conf) {
                        var values = [{session_token: login_token, session_user: login_user, media_id: media_id, media_path: media_path}];

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>/api/common/media/delete.php",
                            dataType: "json",
                            data: JSON.stringify(values),
                            success: function (RetVal) {
                                if (RetVal.message === "Success") {
                                    $("#success_message").text(RetVal.data);
                                    window.location.href = "view_media.php";
                                } else {
                                    alert(RetVal.message);
                                }
                            }
                        });
                    }
                }
                function copy_link(divName) {
                    var copyText = document.getElementById(divName);
                    var input = document.createElement("textarea");
                    input.value = copyText.value;
                    document.body.appendChild(input);
                    input.select();
                    document.execCommand("Copy");
                    input.remove();
                    alert(input.value);
                }

            </script>
        </body>

    </html>
    <?php
} else {
    header("location:../index.html");
}
?>