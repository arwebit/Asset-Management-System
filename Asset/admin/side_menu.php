<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="home.php">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Masters</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-user_category" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-animation"></i>
                <span class="menu-title">User category</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-user_category">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_emp_category.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_emp_category.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-users" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-account"></i>
                <span class="menu-title">Users</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-users">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_user.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_user.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-task_category" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-animation"></i>
                <span class="menu-title">Task category</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-task_category">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_task_category.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_task_category.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-media" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-play"></i>
                <span class="menu-title">Media</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-media">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_media.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_media.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item nav-category">Projects and Manual</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-project" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Projects</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-project">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_project.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_project.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-manual" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi mdi-book"></i>
                <span class="menu-title">Installation manual</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-manual">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_manual.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_manual.php">View</a></li>
                </ul>
            </div>
        </li>
         <li class="nav-item nav-category">Gatepass and attendance</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-gp" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-ticket-account"></i>
                <span class="menu-title">Gatepass</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-gp">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="generate_gatepass.php">Generate</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_all_gatepass.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="manual_attendance.php">
                <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                <span class="menu-title">Automatic attendance</span>
            </a>
        </li>
        
          <li class="nav-item nav-category">Project reports</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-dr" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-file-outline"></i>
                <span class="menu-title">Daily reports</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-dr">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="view_daily_reports.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-tasks" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-clipboard-text"></i>
                <span class="menu-title">Tasks</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-tasks">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_tasks.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_tasks.php">View</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>