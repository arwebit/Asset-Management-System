<?php
$main_page = "Manual attendance";
$page = "Manual attendance";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <!-- Required meta tags -->
            <?php
            include '../header_links.php';
            ?>
            <style type="text/css">
                #reader__status_span{
                    display: none;
                }
                span a{
                    display: none;
                }
            </style>
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title"><?php echo $main_page; ?></h4>
                                            <center>
                                                <div style="width: 300px" id="reader" class="table-responsive"></div>
                                            </center><br/><br/>
                                            <input type="hidden" id="qr_reader"/>
                                            <div id="qr_data" style="display: none;">
                                                <div style="display: none;">
                                                    <b>Gatepass Link ID :</b> <span id="gatepass_link_id"></span>,
                                                    <b>Gatepass ID :</b> <span id="gatepass_id"></span>,
                                                    <b>Employee ID :</b> <span id="employee_id"></span>,
                                                    <b>Gatepass Date :</b> <span id="gatepass_date"></span>
                                                </div>
                                                <p id="confirmation_attendence">
                                                    <b>Do you want to give attendence ? </b>
                                                    <button class="btn btn-primary" id="attendence_yes" onclick="gatepass_attendence('Active');">
                                                        YES
                                                    </button>
                                                    <button class="btn btn-danger" id="attendence_no" onclick="attendence_declined();">
                                                        NO
                                                    </button>
                                                </p>
                                                <p id="confirmation_loginlogout">
                                                    <button class="btn btn-primary" id="login" onclick="gatepass_login_logout('Login');">
                                                        LOGIN
                                                    </button>
                                                    <button class="btn btn-danger" id="logout" onclick="gatepass_login_logout('Logout');">
                                                        LOGOUT
                                                    </button>
                                                </p>
                                                <p>
                                                    <span id="att_success_message" class="text-success text-bold"></span>
                                                    <span id="att_error_message" class="text-danger text-bold"></span>
                                                    <span id="log_success_message" class="text-success text-bold"></span>
                                                    <span id="log_error_message" class="text-danger text-bold"></span>
                                                </p>
                                            </div>

                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!-- /.col-xs-12 -->
                            </div>
                            <!-- /.row-->
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
            <script>
                var html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {fps: 10, qrbox: 250});
                function onScanSuccess(decodedText) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";
                    //console.log(`Scan result: ${decodedText}`, decodedResult);
                    var get_qr_data = `${decodedText}`;
                    $("#qr_reader").val(get_qr_data);

                    var arr = new Array();
                    arr = get_qr_data.split(", ");

                    var gatepass_id = arr[0].split(" : ")[1];
                    var employee_id = arr[1].split(" : ")[1];
                    var gatepass_date = arr[2].split(" : ")[1];
                    var current_date = "<?php echo date("Y-m-d", strtotime(curr_date_time())); ?>";
                    $("#gatepass_id").text(gatepass_id);
                    $("#employee_id").text(employee_id);
                    $("#gatepass_date").text(gatepass_date);
                    $("#attendence_yes").val(gatepass_id);
                    $("#qr_data").css("display", "block");
                    //html5QrcodeScanner.clear();
                    var values = [{session_token: login_token, session_user: login_user, gatepass_id: gatepass_id, gp_date: current_date}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/employee/gatepass/get_selected.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Successfully retrieved") {
                                var parsedJSON = RetVal.data;
                                for (var i = 0; i < parsedJSON.length; i++) {
                                    var details = parsedJSON[i].Details;
                                }
                                for (var j = 0; j < details.length; j++) {
                                    var gatepass_link_id = JSON.parse(details[j].gatepass_link_id);
                                    var attendence = JSON.parse(details[j].gatepass_attendence);
                                    var gatepass_status = JSON.parse(details[j].gatepass_status);
                                    var loginlogout_activiy = JSON.parse(details[j].loginlogout_activiy);
                                }
                                if (gatepass_status === 1) {
                                    $("#gatepass_link_id").text(gatepass_link_id);
                                    if (attendence === 1) {
                                        $("#att_error_message").text("");
                                        $("#log_error_message").text("");
                                        $("#att_success_message").text("");
                                        $("#log_success_message").text("");
                                        $("#confirmation_attendence").css("display", "none");
                                        $("#att_error_message").text("Already given attendence");
                                    } else {
                                        $("#confirmation_attendence").css("display", "block");
                                    }

                                    if (loginlogout_activiy === 0) {
                                        $("#confirmation_loginlogout").css("display", "block");
                                        $("#login").css("display", "block");
                                        $("#logout").css("display", "none");
                                    } else if (loginlogout_activiy === 2) {
                                        $("#log_error_message").text("");
                                        $("#log_success_message").text("");
                                        $("#log_error_message").text("You have been logged out for the day");
                                        $("#login").css("display", "none");
                                        $("#logout").css("display", "none");
                                        $("#confirmation_loginlogout").css("display", "none");
                                    } else {
                                        $("#log_error_message").text("");
                                        $("#log_success_message").text("");
                                        $("#log_error_message").text("You have logged in for the day");
                                        $("#confirmation_loginlogout").css("display", "block");
                                        $("#logout").css("display", "block");
                                        $("#login").css("display", "none");
                                    }
                                } else {
                                    $("#confirmation_attendence").css("display", "none");
                                    $("#confirmation_loginlogout").css("display", "none");
                                    $("#att_error_message").text("");
                                    $("#log_error_message").text("");
                                    $("#att_success_message").text("");
                                    $("#log_success_message").text("");
                                    $("#att_error_message").text("Cannot give attendence for this gatepass");
                                    $("#log_error_message").text("Cannot login/logout for this gatepass");
                                }
                            } else {
                                alert(RetVal.message);
                            }
                        }
                    });

                }
                html5QrcodeScanner.render(onScanSuccess);
            </script>

            <script type="text/javascript">
                function attendence_declined() {
                    $("#confirmation_attendence").css("display", "none");
                }
                function gatepass_attendence(gp_attendence) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";
                    var gatepass_link_id = $("#gatepass_link_id").text();
                    var values = [{session_token: login_token, session_user: login_user, gatepass_link_id: gatepass_link_id, status: gp_attendence}];

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/employee/gatepass/attendence.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#att_error_message").text("");
                                $("#att_success_message").text("");
                                $("#att_success_message").text("Successfully given attendence");
                                $("#confirmation_attendence").css("display", "none");
                            } else {
                                alert(RetVal.message);
                            }
                        }
                    });
                }



                function gatepass_login_logout(gp_login_logout) {
                    var login_user = "<?php echo $login_user; ?>";
                    var login_token = "<?php echo $login_token; ?>";
                    var gatepass_link_id = $("#gatepass_link_id").text();
                    var values = [{session_token: login_token, session_user: login_user, gatepass_link_id: gatepass_link_id, status: gp_login_logout}];
                    var login_status = "";
                    if (gp_login_logout === "Login") {
                        login_status = "logged in";
                    } else {
                        login_status = "logged out";
                    }
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>/api/employee/gatepass/login_logout.php",
                        dataType: "json",
                        data: JSON.stringify(values),
                        success: function (RetVal) {
                            if (RetVal.message === "Success") {
                                $("#log_error_message").text("");
                                $("#att_success_message").text("");
                                $("#att_error_message").text("");
                                $("#log_success_message").text("");
                                $("#log_success_message").text("Successfully " + login_status);
                                $("#confirmation_loginlogout").css("display", "none");
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
