<?php
$main_page = "Update profile";
$page = "Update profile";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    $updateuser_error = false;
    if (isset($_REQUEST['save_profile'])) {
        $mem_fname = trim($_REQUEST['mem_fname']);
        $mem_mname = trim($_REQUEST['mem_mname']);
        $mem_lname = trim($_REQUEST['mem_lname']);
        $address = trim($_REQUEST['address']);
        $mem_email = trim($_REQUEST['mem_email']);
        $mem_hemail = trim($_REQUEST['mem_hemail']);
        $mem_mobile = trim($_REQUEST['mem_mobile']);
        $mem_hmobile = trim($_REQUEST['mem_hmobile']);
        $mem_role_id = trim($_REQUEST['user_role_id']);
        $mem_category = trim($_REQUEST['mem_category']);
        $mem_type = trim($_REQUEST['mem_type']);
        $mem_emp_code = trim($_REQUEST['mem_emp_code']);
        $mem_hemp_code = trim($_REQUEST['mem_hemp_code']);

        $updateuser_data[] = array("session_token" => $login_token, "session_user" => $login_user, "update_user" => $login_user,
            "mem_first_name" => $mem_fname, "mem_middle_name" => $mem_mname, "mem_last_name" => $mem_lname, "mem_email" => $mem_email,
            "mem_hemail" => $mem_hemail, "mem_mobile" => $mem_mobile, "mem_hmobile" => $mem_hmobile, "mem_address" => $address,
            "mem_role" => $mem_role_id, "mem_category" => $mem_category, "mem_emp_code" => $mem_emp_code, "mem_hemp_code" => $mem_hemp_code,
            "mem_type" => $mem_type);
        $uu_data = json_encode($updateuser_data);
        $updateuser_getData = json_decode(callAPI($uu_data, $user_update_api));
        $updateuser_error = $updateuser_getData->error;
        $updateuser_message = $updateuser_getData->message;
        $updateuser_data = $updateuser_getData->data;


        if ($updateuser_error == true) {
            if ($updateuser_message == "Server error") {
                foreach ($updateuser_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($updateuser_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $fnameErr = $dataVal->FirstNameErr;
                    $lnameErr = $dataVal->LastNameErr;
                    $emailErr = $dataVal->EmailErr;
                    $mnameErr = $dataVal->MiddleNameErr;
                    $mobileErr = $dataVal->MobileErr;
                    $mem_typeErr = $dataVal->MemberTypeErr;
                    $emp_codeErr = $dataVal->EmployeeCodeErr;
                }
            }
        } else {
            $successMsg = "Successfully updated user";
        }
    }


    $ausers_data[] = array("session_token" => $login_token, "session_user" => $login_user, "username" => $login_user);
    $ausers_recv_data = json_encode($ausers_data);
    $getausersData = json_decode(callAPI($ausers_recv_data, $login_detail_api));
    $ret_ausers_error = $getausersData->error;
    $ret_ausers_message = $getausersData->message;
    $ret_ausers_data = $getausersData->data;
    if ($ret_ausers_error == true) {
        $errMsg = $ret_ausers_message;
    } else {
        foreach ($ret_ausers_data as $dataVal) {
            $userDetail = $dataVal->Details;
        }
        foreach ($userDetail as $userDetailVal) {
            $fmem_fname = $userDetailVal->member_first_name;
            $fmem_mname = $userDetailVal->member_middle_name;
            $fmem_lname = $userDetailVal->member_last_name;
            $fmem_address = $userDetailVal->member_address;
            $fmem_email = $userDetailVal->member_email;
            $fmem_hemail = $userDetailVal->member_email;
            $fmem_category = $userDetailVal->member_category;
            $fmem_mobile = $userDetailVal->member_mobile;
            $fmem_hmobile = $userDetailVal->member_mobile;
            $fmem_type = $userDetailVal->member_type;
            $fmem_emp_code = $userDetailVal->emp_code;
            $fmem_hemp_code = $userDetailVal->emp_code;
        }
    }
    if (isset($_REQUEST['pass_change'])) {
        $old_pass = trim($_REQUEST['old_pass']);
        $new_pass = trim($_REQUEST['new_pass']);
        $pass_chnge_data[] = array("session_token" => $login_token, "session_user" => $login_user, "old_pass" => $old_pass, "new_pass" => $new_pass);
        $pc_data = json_encode($pass_chnge_data);
        $pc_getData = json_decode(callAPI($pc_data, $user_password_change_api));
        $pc_error = $pc_getData->error;
        $pc_message = $pc_getData->message;
        $passchnge_data = $pc_getData->data;

        if ($pc_error == true) {
            if ($pc_message == "Server error") {
                foreach ($passchnge_data as $dataVal) {
                    $loginErr = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($passchnge_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $oldpassErr = $dataVal->OldPassErr;
                    $newpassErr = $dataVal->NewPassErr;
                }
            }
        } else {
            $passsuccessMsg = "Successfully changed";
            header("location:../logout.php");
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <!-- Required meta tags -->
            <?php
            include '../header_links.php';
            include '../text_editor_css.php';
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
                                                <input type="hidden" name="user_role_id" id="user_role_id" value="<?php echo $user_role_id; ?>" />
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="mem_fname">First name</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_fname" id="mem_fname" placeholder="ENTER FIRST NAME"
                                                                   value="<?php
                                                                   if ($updateuser_error == false) {
                                                                       echo $fmem_fname;
                                                                   } else {
                                                                       echo $mem_fname;
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $fnameErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mem_lname">Last name</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_lname" id="mem_lname" placeholder="ENTER LAST NAME"
                                                                   value="<?php
                                                                   if ($updateuser_error == false) {
                                                                       echo $fmem_lname;
                                                                   } else {
                                                                       echo $mem_lname;
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $lnameErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="member_type">Member Type</label> <b class="text-danger"> * </b>
                                                            <select required="required" class="js-example-basic-single w-100" id="mem_type" name="mem_type">
                                                                <option value="">SELECT MEMBER TYPE</option>
                                                                <?php
                                                                $memberType_arr = array("P" => "Permanent", "C" => "Contractual");
                                                                foreach ($memberType_arr as $key => $memberTypeVals) {
                                                                    $mem_type_name = $memberTypeVals;
                                                                    $mem_type_id = $key;
                                                                    ?>
                                                                    <option value="<?php echo $mem_type_id; ?>"
                                                                    <?php
                                                                    if ($createuser_error == false) {
                                                                        if ($mem_type_id == $fmem_type) {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        if ($mem_type_id == $mem_type) {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    }
                                                                    ?>><?php echo $mem_type_name; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                            </select>
                                                            <b class="text-danger"><?php echo $mem_typeErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email address</label> <b class="text-danger"> *</b>
                                                            <input type="email" required="required" class="form-control" name="mem_email" id="mem_email" placeholder="ENTER YOUR EMAIL"
                                                                   value="<?php
                                                                   if ($updateuser_error == false) {
                                                                       echo $fmem_email;
                                                                   } else {
                                                                       echo $mem_email;
                                                                   }
                                                                   ?>" />
                                                            <input type="hidden" class="form-control" name="mem_hemail" id="mem_hemail" placeholder="ENTER YOUR EMAIL" value="<?php echo $fmem_hemail; ?>" />
                                                            <b class="text-danger"><?php echo $emailErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mem_address">Address</label> <b class="text-danger"></b>
                                                            <textarea class="form-control" name="address" id="address">
                                                                <?php
                                                                if ($updateuser_error == false) {
                                                                    echo $fmem_address;
                                                                } else {
                                                                    echo $mem_address;
                                                                }
                                                                ?>
                                                            </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="mem_mname">Middle name</label> <b class="text-danger"> </b>
                                                            <input type="text" class="form-control" name="mem_mname" id="mem_mname" placeholder="ENTER MIDDLE NAME"
                                                                   value="<?php
                                                                   if ($updateuser_error == false) {
                                                                       echo $fmem_mname;
                                                                   } else {
                                                                       echo $mem_mname;
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $mnameErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="empcode">Employee code</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_emp_code" id="mem_emp_code" placeholder="ENTER EMPLOYEE CODE"
                                                                   value="<?php
                                                                   if ($updateuser_error == false) {
                                                                       echo $fmem_emp_code;
                                                                   } else {
                                                                       echo $mem_emp_code;
                                                                   }
                                                                   ?>" />
                                                            <input type="hidden" class="form-control" name="mem_hemp_code" id="mem_hemp_code" placeholder="ENTER EMPLOYEE CODE" value="<?php echo $fmem_hemp_code; ?>" />
                                                            <b class="text-danger"><?php echo $emp_codeErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="category">Category</label> <b class="text-danger"> </b>
                                                            <select class="js-example-basic-single w-100" id="mem_category" name="mem_category">
                                                                <option value="">SELECT CATEGORY</option>
                                                                <?php
                                                                $cat_data[] = array("session_token" => $login_token, "session_user" => $login_user,
                                                                    "status" => "Active");
                                                                $cat_recv_data = json_encode($cat_data);
                                                                $getcatData = json_decode(callAPI($cat_recv_data, $emp_category_details_api));
                                                                $ret_cat_error = $getcatData->error;
                                                                $ret_cat_message = $getcatData->message;
                                                                $ret_cat_data = $getcatData->data;

                                                                if ($ret_cat_error == true) {
                                                                    $catErr = $ret_cat_message;
                                                                } else {
                                                                    foreach ($ret_cat_data as $catdataVal) {
                                                                        $cat_detail = $catdataVal->Details;
                                                                    }
                                                                    foreach ($cat_detail as $cat_details) {
                                                                        $cat_name = $cat_details->category_name;
                                                                        $cat_id = $cat_details->category_id;
                                                                        ?>
                                                                        <option value="<?php echo $cat_id; ?>"
                                                                        <?php
                                                                        if ($createuser_error == true) {
                                                                            if ($cat_id == $fmem_category) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        } else {
                                                                            if ($cat_id == $mem_category) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        }
                                                                        ?>><?php echo $cat_name; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="mobile">Mobile</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" maxlength="10" minlength="10" class="form-control" name="mem_mobile" id="mem_mobile" placeholder="ENTER YOUR MOBILE NUMBER" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                                   value="<?php
                                                                   if ($updateuser_error == false) {
                                                                       echo $fmem_mobile;
                                                                   } else {
                                                                       echo $mem_mobile;
                                                                   }
                                                                   ?>"/>
                                                            <input type="hidden" class="form-control" name="mem_hmobile" id="mem_hmobile" placeholder="ENTER YOUR MOBILE" value="<?php echo $fmem_hmobile; ?>" />

                                                            <b class="text-danger"><?php echo $mobileErr; ?></b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" name="save_profile" id="save_profile" class="btn btn-primary mr-2">
                                                    Save</button>
                                                <b class="text-success"><?php echo $successMsg; ?></b>
                                                <b class="text-danger"><?php echo $errmsg; ?></b>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- col-12 -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Change password</h4>
                                            <form class="forms-sample" action="" id="password_change" method="post">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="old_pass">Old password</label> <b class="text-danger"> * </b>
                                                            <input type="password" class="form-control" name="old_pass" id="old_pass" placeholder="ENTER OLD PASSWORD" />
                                                            <b class="text-danger"><?php echo $oldpassErr; ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                        <div class="form-group">
                                                            <label for="new_pass">New password</label> <b class="text-danger"> * </b>
                                                            <input type="password" class="form-control" name="new_pass" id="new_pass" placeholder="ENTER NEW PASSWORD" />
                                                            <b class="text-danger"><?php echo $newpassErr; ?></b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" name="pass_change" id="pass_change" class="btn btn-primary mr-2">
                                                    Change password</button>
                                                <b class="text-success"><?php echo $passsuccessMsg; ?></b>
                                                <b class="text-danger"><?php echo $passerrmsg; ?></b>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- col-12 -->
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
            include '../text_editor_js.php';
            ?>
        </body>

    </html>
    <?php
} else {
    header("location:../index.html");
}
?>
