<?php
$main_page = "Add user";
$page = "Add user";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if (isset($_REQUEST['create_user'])) {
        $mem_fname = trim($_REQUEST['mem_fname']);
        $mem_mname = trim($_REQUEST['mem_mname']);
        $mem_lname = trim($_REQUEST['mem_lname']);
        $mem_address = trim($_REQUEST['mem_address']);
        $mem_user_name = trim($_REQUEST['mem_user_name']);
        $mem_email = trim($_REQUEST['mem_email']);
        $mem_role = trim($_REQUEST['mem_role']);
        $mem_mobile = trim($_REQUEST['mem_mobile']);
        $mem_type = trim($_REQUEST['mem_type']);
        $mem_emp_code = trim($_REQUEST['mem_emp_code']);
        $mem_category = "";

        $createuser_data[] = array("session_token" => $login_token, "session_user" => $login_user, "mem_first_name" => $mem_fname,
            "mem_middle_name" => $mem_mname, "mem_last_name" => $mem_lname, "mem_user_name" => $mem_user_name, "mem_category" => $mem_category,
            "mem_email" => $mem_email, "mem_role" => $mem_role, "mem_mobile" => $mem_mobile, "mem_address" => $mem_address,
            "mem_emp_code" => $mem_emp_code, "mem_type" => $mem_type);
        $cu_data = json_encode($createuser_data);
        $createuser_getData = json_decode(callAPI($cu_data, $user_create_api));
        $createuser_error = $createuser_getData->error;
        $createuser_message = $createuser_getData->message;
        $createuser_data = $createuser_getData->data;

        if ($createuser_error == true) {
            if ($createuser_message == "Server error") {
                foreach ($createuser_data as $dataVal) {
                    $errMsg = $dataVal->ErrorMessage;
                }
            } else {
                foreach ($createuser_data as $dataVal) {
                    $tokenErr = $dataVal->TokenErr;
                    $fnameErr = $dataVal->FirstNameErr;
                    $lnameErr = $dataVal->LastNameErr;
                    $emailErr = $dataVal->EmailErr;
                    $mnameErr = $dataVal->MiddleNameErr;
                    $usernameErr = $dataVal->UsernameErr;
                    $mobileErr = $dataVal->MobileErr;
                    $roleErr = $dataVal->RoleErr;
                    $mem_typeErr = $dataVal->MemberTypeErr;
                    $emp_codeErr = $dataVal->EmployeeCodeErr;
                }
            }
        } else {
            $successMsg = "Successfully inserted";
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
                                            <form class="forms-sample" action="" method="post">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="mem_fname">First name</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_fname" id="mem_fname" placeholder="ENTER FIRST NAME"
                                                                   value="<?php
                                                                   if ($createuser_error == true) {
                                                                       echo $mem_fname;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $fnameErr; ?></b>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="mem_mname">Middle name</label> <b class="text-danger"> </b>
                                                            <input type="text" class="form-control" name="mem_mname" id="mem_mname" placeholder="ENTER MIDDLE NAME"
                                                                   value="<?php
                                                                   if ($createuser_error == true) {
                                                                       echo $mem_mname;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $mnameErr; ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="mem_lname">Last name</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_lname" id="mem_lname" placeholder="ENTER LAST NAME"
                                                                   value="<?php
                                                                   if ($createuser_error == true) {
                                                                       echo $mem_lname;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $lnameErr; ?></b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="role">Role</label> <b class="text-danger"> *</b>
                                                            <select class="js-example-basic-single w-100" id="mem_role" name="mem_role" required="required">
                                                                <option value="">SELECT ROLE</option>
                                                                <?php
                                                                $role_data[] = array("session_token" => $login_token, "session_user" => $login_user, "status" => "", "role_name" => "",
                                                                    "role_id_from" => "3", "role_id_to" => "3");
                                                                $role_recv_data = json_encode($role_data);
                                                                $getroleData = json_decode(callAPI($role_recv_data, $all_role_details_api));
                                                                $ret_role_error = $getroleData->error;
                                                                $ret_role_message = $getroleData->message;
                                                                $ret_role_data = $getroleData->data;

                                                                if ($ret_role_error == true) {
                                                                    $roleErr = $role_message;
                                                                } else {
                                                                    foreach ($ret_role_data as $dataVal) {
                                                                        $role_detail = $dataVal->Details;
                                                                    }
                                                                    foreach ($role_detail as $role_details) {
                                                                        $role_name = $role_details->role_name;
                                                                        $role_id = $role_details->role_id;
                                                                        ?>
                                                                        <option value="<?php echo $role_id; ?>"
                                                                        <?php
                                                                        if ($createuser_error == true) {
                                                                            if ($role_id == $mem_role) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        } else {
                                                                            echo "";
                                                                        }
                                                                        ?>><?php echo $role_name; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                            </select>
                                                            <b class="text-danger"><?php echo $roleErr; ?></b>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="email">Email address</label> <b class="text-danger"> *</b>
                                                            <input type="email" required="required" class="form-control" name="mem_email" id="mem_email" placeholder="ENTER YOUR EMAIL"
                                                                   value="<?php
                                                                   if ($createuser_error == true) {
                                                                       echo $mem_email;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $emailErr; ?></b>
                                                        </div>
                                                               <div class="form-group">
                                                            <label for="empcode">Employee code</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_emp_code" id="mem_emp_code" placeholder="ENTER EMPLOYEE CODE"
                                                                   value="<?php
                                                                   if ($updateuser_error == true) {
                                                                       echo $mem_emp_code;
                                                                   } else {
                                                                     echo "";
                                                                   }
                                                                   ?>" />
                                                            <b class="text-danger"><?php echo $emp_codeErr; ?></b>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="address">Address</label> <b class="text-danger"> </b>
                                                            <textarea class="form-control" name="mem_address" id="address">
                                                                <?php
                                                                if ($createuser_error == true) {
                                                                    echo $mem_address;
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
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
                                                                    if ($createuser_error == true) {
                                                                        if ($mem_type_id == $mem_type) {
                                                                            echo "selected='selected'";
                                                                        }
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>><?php echo $mem_type_name; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                            </select>
                                                            <b class="text-danger"><?php echo $mem_typeErr; ?></b>
                                                        </div>                                                      
                                                        <div class="form-group">
                                                            <label for="mobile">Mobile</label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" maxlength="10" minlength="10" class="form-control" name="mem_mobile" id="mem_mobile" placeholder="ENTER YOUR MOBILE NUMBER" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                                   value="<?php
                                                                   if ($createuser_error == true) {
                                                                       echo $mem_mobile;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $mobileErr; ?></b>
                                                        </div> 
                                                          <div class="form-group">
                                                            <label for="uname">Username <small>(Unique and cant change)</small></label> <b class="text-danger"> *</b>
                                                            <input type="text" required="required" class="form-control" name="mem_user_name" id="mem_user_name" placeholder="ENTER YOUR USERNAME / EMPLOYEE CODE"
                                                                   value="<?php
                                                                   if ($createuser_error == true) {
                                                                       echo $mem_user_name;
                                                                   } else {
                                                                       echo "";
                                                                   }
                                                                   ?>"/>
                                                            <b class="text-danger"><?php echo $usernameErr; ?></b>
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
                                                                            if ($cat_id == $mem_category) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                        } else {
                                                                            echo "";
                                                                        }
                                                                        ?>><?php echo $cat_name; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <button type="submit" name="create_user" id="create_user" class="btn btn-primary mr-2">Save</button>
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
            include '../text_editor_js.php';
            ?>
        </body>

    </html>
    <?php
} else {
    header("location:../index.html");
}
?>
