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
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-users" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-account"></i>
                <span class="menu-title">Admins</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-users">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="add_user.php">Add</a></li>
                    <li class="nav-item"> <a class="nav-link" href="view_user.php">View</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>