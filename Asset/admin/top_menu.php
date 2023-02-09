<?php
$input_data[] = array("session_token" => $login_token, "session_user" => $login_user, "username" => $login_user);
$recv_data = json_encode($input_data);
$getData = json_decode(callAPI($recv_data, $login_detail_api));
$ret_login_data = $getData->data;
foreach ($ret_login_data as $dataVal) {
    $log_det = $dataVal->Details;
}
foreach ($log_det as $log_details) {
    $profile_name = $log_details->member_first_name . " " . $log_details->member_middle_name . " " . $log_details->member_last_name;
    $profile_email = $log_details->member_email;
    $user_role_name = $log_details->role_name;
    $user_role_id = $log_details->role_id;
}
?>
<!-- partial:partials/_navbar.html -->
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="home.php">
                <img src="../assets/images/logo.png?v=<?php echo time();?>" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="home.php">
                <img src="../assets/images/logo-mini.png?v=<?php echo time();?>" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text"><span id="greetings"></span>, <span class="text-black fw-bold"><?php echo $profile_name; ?></span></h1>
                <h3 class="welcome-sub-text">Role name : <?php echo $user_role_name; ?> </h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <!-- <li class="nav-item dropdown d-none d-lg-block">
                 <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Select Category </a>
                 <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
                     <a class="dropdown-item py-3" >
                         <p class="mb-0 font-weight-medium float-left">Select category</p>
                     </a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item preview-item">
                         <div class="preview-item-content flex-grow py-2">
                             <p class="preview-subject ellipsis font-weight-medium text-dark">Bootstrap Bundle </p>
                             <p class="fw-light small-text mb-0">This is a Bundle featuring 16 unique dashboards</p>
                         </div>
                     </a>
                     <a class="dropdown-item preview-item">
                         <div class="preview-item-content flex-grow py-2">
                             <p class="preview-subject ellipsis font-weight-medium text-dark">Angular Bundle</p>
                             <p class="fw-light small-text mb-0">Everything you’ll ever need for your Angular projects</p>
                         </div>
                     </a>
                     <a class="dropdown-item preview-item">
                         <div class="preview-item-content flex-grow py-2">
                             <p class="preview-subject ellipsis font-weight-medium text-dark">VUE Bundle</p>
                             <p class="fw-light small-text mb-0">Bundle of 6 Premium Vue Admin Dashboard</p>
                         </div>
                     </a>
                     <a class="dropdown-item preview-item">
                         <div class="preview-item-content flex-grow py-2">
                             <p class="preview-subject ellipsis font-weight-medium text-dark">React Bundle</p>
                             <p class="fw-light small-text mb-0">Bundle of 8 Premium React Admin Dashboard</p>
                         </div>
                     </a>
                 </div>
             </li>
             <li class="nav-item d-none d-lg-block">
                 <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                     <span class="input-group-addon input-group-prepend border-right">
                         <span class="icon-calendar input-group-text calendar-icon"></span>
                     </span>
                     <input type="text" class="form-control">
                 </div>
             </li>-->
            <li class="nav-item">
                <span id="date"></span> &nbsp;&nbsp; <span id="time"></span>
            </li>
            <!--<li class="nav-item">
                <form class="search-form" action="#">
                    <i class="icon-search"></i>
                    <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                </form>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="icon-mail icon-lg"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                    <a class="dropdown-item py-3 border-bottom">
                        <p class="mb-0 font-weight-medium float-left">You have 4 new notifications </p>
                        <span class="badge badge-pill badge-primary float-right">View all</span>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-alert m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">Application Error</h6>
                            <p class="fw-light small-text mb-0"> Just now </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-settings m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6>
                            <p class="fw-light small-text mb-0"> Private message </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-airballoon m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">New user registration</h6>
                            <p class="fw-light small-text mb-0"> 2 days ago </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="icon-bell"></i>
                    <span class="count"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="countDropdown">
                    <a class="dropdown-item py-3">
                        <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
                        <span class="badge badge-pill badge-primary float-right">View all</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="../assets/img/faces/face10.jpg" alt="image" class="img-sm profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                            <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="../assets/img/faces/face12.jpg" alt="image" class="img-sm profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                            <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="../assets/img/faces/face1.jpg" alt="image" class="img-sm profile-pic">
                        </div>
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
                            <p class="fw-light small-text mb-0"> The meeting is cancelled </p>
                        </div>
                    </a>
                </div>
            </li>-->
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="../assets/images/faces/face8.jpg?v=<?php echo time();?>" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                  <a class="dropdown-item" href="./profile.php"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
                    <a class="dropdown-item" href="../logout.php"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>