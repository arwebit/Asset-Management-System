<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="home.php">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Manual</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-manual" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi mdi-book"></i>
                <span class="menu-title">Installation manual</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-manual">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="view_manual.php">View</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item nav-category">Gatepass</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-gp" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-ticket-account"></i>
                <span class="menu-title">Gatepass</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-gp">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="generate_gatepass.php">Request gatepass</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_gatepass.php">View</a></li>
                </ul>
            </div>
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
                    <li class="nav-item"> <a class="nav-link" href="view_tasks.php">View</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>