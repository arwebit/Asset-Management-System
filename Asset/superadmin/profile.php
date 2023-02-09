<?php
$main_page = "Profile";
$page = "Profile";
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
                <?php
                include './top_menu.php';

                $searchdata[] = array("session_token" => $login_token, "session_user" => $login_user, "username" => $login_user);
                $srch_recv_data = json_encode($searchdata);
                $getsrchData = json_decode(callAPI($srch_recv_data, $login_detail_api));
                $ret_srch_error = $getsrchData->error;
                $ret_srch_message = $getsrchData->message;
                $ret_srch_data = $getsrchData->data;

                if ($ret_srch_error == true) {
                    $srchErr = $ret_srch_message;
                } else {
                    foreach ($ret_srch_data as $dataVal) {
                        $ret_srch_detail = $dataVal->Details;
                    }
                    foreach ($ret_srch_detail as $ret_srch_details) {
                        $profile_name= $ret_srch_details->member_first_name." ".$ret_srch_details->member_middle_name." ".$ret_srch_details->member_last_name;
                        $emp_code= $ret_srch_details->emp_code;
                        $mobile = $ret_srch_details->member_mobile;
                        $email = $ret_srch_details->member_email;
                        $address = $ret_srch_details->member_address;
                    }
                }
                ?>
                <!-- partial -->
                <div class="container-fluid page-body-wrapper">
                    <?php include './side_menu.php'; ?>
                    <!-- partial -->
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row">
                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h4>Information</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                    <img src="../assets/images/faces/face8.jpg?v=<?php echo time();?>" class="img-circle" alt="User Image" width="220"/><br /><br />
                                                    <center>
                                                        <a href="update_profile.php">
                                                            <button class="btn btn-primary">
                                                                UPDATE PROFILE
                                                            </button>
                                                        </a>
                                                    </center>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                    <label for="name">Name :</label> <?php echo $profile_name; ?><br /><br />
                                                     <label for="code">Employee code :</label> <?php echo $emp_code; ?><br /><br />
                                                    <label for="username">Username :</label> <?php echo $login_user; ?><br /><br />
                                                    <label for="email">Email :</label> <?php echo $email; ?><br /><br />
                                                    <label for="mobile">Mobile :</label> <?php echo $mobile; ?><br /><br />
                                                    <label for="adddress">Address :</label> <?php echo nl2br($address); ?><br /><br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-7-->
                            </div>
                            <!--./row-->
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