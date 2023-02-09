<?php
$main_page = "View user category";
$page = "View user category";
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
                                            <h4 class="card-title">Active user categories</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $acategorys_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Active");
                                                $acategorys_recv_data = json_encode($acategorys_data);
                                                $getacategorysData = json_decode(callAPI($acategorys_recv_data, $emp_category_details_api));
                                                $ret_acategorys_error = $getacategorysData->error;
                                                $ret_acategorys_message = $getacategorysData->message;
                                                $ret_acategorys_data = $getacategorysData->data;

                                                if ($ret_acategorys_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_acategorys_data as $dataVal) {
                                                                $ret_acategorys_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_acategorys_detail as $ret_acategorys_details) {
                                                                $acategorys_id = $ret_acategorys_details->category_id;
                                                                $acategorys_name = $ret_acategorys_details->category_name;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $acategorys_name; ?></td>
                                                                    <td align="center">
                                                                        <a href="edit_emp_category.php?category_id=<?php echo $acategorys_id; ?>">
                                                                            <button type="button" class="btn btn-info btn-rounded btn-sm" id="edit_category">
                                                                                <span>Edit</span></button><br/> <br/>
                                                                        </a>
                                                                        <button type="button" class="btn btn-danger btn-rounded btn-sm" id="inactive_active" value="Inactive"
                                                                                onclick="change_status(this.value, '<?php echo $acategorys_id; ?>');">
                                                                            <span>Inactive</span></button>
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

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Inactive user catgegories</h4>
                                            <div class="table-responsive">

                                                <?php
                                                $icategorys_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                    "status" => "Inactive");
                                                $icategorys_recv_data = json_encode($icategorys_data);
                                                $geticategorysData = json_decode(callAPI($icategorys_recv_data, $emp_category_details_api));
                                                $ret_icategorys_error = $geticategorysData->error;
                                                $ret_icategorys_message = $geticategorysData->message;
                                                $ret_icategorys_data = $geticategorysData->data;

                                                if ($ret_icategorys_error == true) {
                                                    ?>
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" align="center">No records found</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <table class="table table-hover sampleTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-weight: bolder;">Category</th>
                                                                <th style="font-weight: bolder;">Action</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($ret_icategorys_data as $dataVal) {
                                                                $ret_icategorys_detail = $dataVal->Details;
                                                            }
                                                            foreach ($ret_icategorys_detail as $ret_icategorys_details) {
                                                                $icategorys_id = $ret_icategorys_details->category_id;
                                                                $icategorys_name = $ret_icategorys_details->category_name;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $icategorys_name; ?></td>
                                                                    <td align="center">
                                                                        <button type="button" class="btn btn-info btn-rounded btn-sm" id="inactive_active" value="Active"
                                                                                onclick="change_status(this.value, '<?php echo $icategorys_id; ?>');">
                                                                            <span>Active</span></button>
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

                function change_status(category_status, category_id) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";

                    var values = [{session_token: login_token, session_user: login_user, category_id: category_id, status: category_status}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/common/emp_category/availability.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#success_message").text(RetVal.data);
                                window.location.href = "view_emp_category.php";
                            } else {
                                alert(RetVal.message);
                            }
                        }
                    });
                }
            </script>
        </body>

    </html>
    <?php
} else {
    header("location:../index.html");
}
?>