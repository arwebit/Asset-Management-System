<?php
$main_page = "Login";
$page = "Login";
include './api/common/global_functions.php';
include './api_links.php';
if (isset($_REQUEST['role_value'])) {
    $role_val = $_REQUEST['role_value'];
}
if (isset($_REQUEST['loginbtn'])) {
    $user_name = trim($_REQUEST['user_name']);
    $user_pass = trim($_REQUEST['user_pass']);
    $user_role_id = trim($_REQUEST['user_role_id']);
    $input_data[] = array("username" => $user_name, "password" => $user_pass, "user_role_id" => $user_role_id);
    $recv_data = json_encode($input_data);
    $getData = json_decode(callAPI($recv_data, $login_access_api));
    $error = $getData->error;
    $message = $getData->message;
    $data = $getData->data;
    if ($error == true) {
        if (($message == "Token problem") || ($message == "User and role mismatch") || ($message == "In-active user") || ($message == "Record not exist")) {
            foreach ($data as $dataVal) {
                $loginErr = $dataVal->ErrorMessage;
            }
        } else {
            foreach ($data as $dataVal) {
                $user_nameErr = $dataVal->UserNameErr;
                $passwordErr = $dataVal->PasswordErr;
                $roleErr = $dataVal->RoleErr;
            }
        }
    } else {
        $_SESSION['asset_member'] = $user_name;
        $_SESSION['asset_token'] = generate_token($user_name, $user_pass);
        switch ($user_role_id) {
            case "-2":
                $redirect_link = "superadmin/home.php";
                break;
            case "-1":
                $redirect_link = "admin/home.php";
                break;
            case "1":
                $redirect_link = "hr/home.php";
                break;
            case "2":
                $redirect_link = "supervisor/home.php";
                break;
            case "3":
                $redirect_link = "employee/home.php";
                break;
            default :
                break;
        }
        $location= site_url()."/".$redirect_link;
        header("location:$location");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
        <meta name="author" content="Creative Tim">
        <title>Asset Management :: Login</title>
        <link rel="stylesheet" href="assets/css/font-awesome/css/font-awesome.min.css?v=<?php echo time(); ?>">
        <!-- Favicon -->
        <link rel="icon" href="assets/images/favicon.png?v=<?php echo time(); ?>" type="image/png">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <!-- Icons -->
        <link rel="stylesheet" href="assets/css/nucleo.css?v=<?php echo time(); ?>" type="text/css">

        <link rel="stylesheet" href="assets/css/argon.css?v=<?php echo time(); ?>" type="text/css">
    </head>

    <body class="bg-default">
        <!-- Navbar -->
        <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="./dashboard.php">
                    <img src="assets/images/logo-mini.png?v=<?php echo time(); ?>">
                </a>
                <!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>-->
                <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
                    <div class="navbar-collapse-header">
                        <div class="row">
                            <div class="col-6 collapse-brand">
                                <a href="./dashboard.php">
                                    <img src="assets/img/brand/logo-mini.png?v=<?php echo time(); ?>">
                                </a>
                            </div>
                            <div class="col-6 collapse-close">
                                <!--<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                                  <span></span>
                                  <span></span>
                                </button>-->
                            </div>
                        </div>
                    </div>
                    <!--<ul class="navbar-nav mr-auto">
                      <li class="nav-item">
                        <a href="dashboard.html" class="nav-link">
                          <span class="nav-link-inner--text">Dashboard</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="login.html" class="nav-link">
                          <span class="nav-link-inner--text">Login</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a href="register.html" class="nav-link">
                          <span class="nav-link-inner--text">Register</span>
                        </a>
                      </li>
                    </ul>-->
                    <hr class="d-lg-none" />
                    <!-- <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                      <li class="nav-item">
                        <a class="nav-link nav-link-icon" href="https://www.facebook.com/creativetim" target="_blank" data-toggle="tooltip" data-original-title="Like us on Facebook">
                          <i class="fab fa-facebook-square"></i>
                          <span class="nav-link-inner--text d-lg-none">Facebook</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link nav-link-icon" href="https://www.instagram.com/creativetimofficial" target="_blank" data-toggle="tooltip" data-original-title="Follow us on Instagram">
                          <i class="fab fa-instagram"></i>
                          <span class="nav-link-inner--text d-lg-none">Instagram</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link nav-link-icon" href="https://twitter.com/creativetim" target="_blank" data-toggle="tooltip" data-original-title="Follow us on Twitter">
                          <i class="fab fa-twitter-square"></i>
                          <span class="nav-link-inner--text d-lg-none">Twitter</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link nav-link-icon" href="https://github.com/creativetimofficial" target="_blank" data-toggle="tooltip" data-original-title="Star us on Github">
                          <i class="fab fa-github"></i>
                          <span class="nav-link-inner--text d-lg-none">Github</span>
                        </a>
                      </li>
                      <li class="nav-item d-none d-lg-block ml-lg-4">
                        <a href="https://www.creative-tim.com/product/argon-dashboard-pro?ref=ad_upgrade_pro" target="_blank" class="btn btn-neutral btn-icon">
                          <span class="btn-inner--icon">
                            <i class="fas fa-shopping-cart mr-2"></i>
                          </span>
                          <span class="nav-link-inner--text">Upgrade to PRO</span>
                        </a>
                      </li>
                    </ul>-->
                </div>
            </div>
        </nav>
        <!-- Main content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
                <div class="container">
                    <div class="header-body text-center mb-7">
                        <div class="row justify-content-center">
                            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                                <h1 class="text-white">Welcome!</h1>
                                <p class="text-lead text-white">Use these awesome forms to
                                    login or create new account in your project for free.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator separator-bottom separator-skew zindex-100">
                    <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                    </svg>
                </div>
            </div>
            <!-- Page content -->
            <div class="container mt--8 pb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div class="card bg-secondary border-0 mb-0">
                            <div class="card-body px-lg-5 py-lg-5">
                                <div class="text-center text-muted mb-4">
                                    <small>Sign in with credentials</small>
                                </div>
                                <form role="form" action="" method="post">
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select class="form-control" id="user_role_id" name="user_role_id">
                                                <option value="" <?php if ($role_val == "0") {
    echo "selected='selected'";
} ?>>SELECT ROLE</option>
                                                <option value="-2" <?php if ($role_val == "-2") {
    echo "selected='selected'";
} ?>>Superadmin</option>
                                                <option value="-1" <?php if ($role_val == "-1") {
    echo "selected='selected'";
} ?>>Admin</option>
                                                <!-- <option value="1" <?php if ($role_val == "1") {
    echo "selected='selected'";
} ?>>HR</option>-->
                                                <option value="2" <?php if ($role_val == "2") {
    echo "selected='selected'";
} ?>>Supervisor</option>
                                                <option value="3" <?php if ($role_val == "3") {
    echo "selected='selected'";
} ?>>Employee</option>
                                            </select>
                                        </div>
                                        <b class="text-danger"><?php echo $roleErr; ?></b>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter username">
                                        </div>
                                        <b class="text-danger"><?php echo $user_nameErr; ?></b>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control" id="user_pass" name="user_pass" placeholder="Enter password">
                                        </div>
                                        <b class="text-danger"><?php echo $passwordErr; ?></b>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="loginbtn" id="loginbtn" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                                <b class="text-danger"><?php echo $loginErr; ?>
                                </b>
                            </div>
                        </div>
                        <!--<div class="row mt-3">
                            <div class="col-6">
                                <a href="#" class="text-light"><small>Forgot password?</small></a>
                            </div>
                            <div class="col-6 text-right">
                                                <a href="#" class="text-light"><small>Create new account</small></a>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="py-5" id="footer-main">
            <div class="container">
                <div class="row align-items-center justify-content-xl-between">
                    <div class="col-xl-6">
                        <div class="copyright text-center text-xl-left text-muted">
                            &copy; 2021 <a href="#" class="font-weight-bold ml-1">Asset Management</a>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="copyright text-center text-xl-right text-muted">
                            Design & Developed <a href="https://www.krtech.in" class="font-weight-bold ml-1" target="_blank">KR Tech</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </body>

</html>
