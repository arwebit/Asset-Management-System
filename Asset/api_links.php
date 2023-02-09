<?php

/* ********************************** LOGIN, LOGOUT AND USER DETAILS *********************************** */

$login_access_api = site_url() . "/api/common/loginAccess.php";
$logout_access_api = site_url() . "/api/common/logoutAccess.php";
$login_detail_api = site_url() . "/api/common/user/get_selected.php";
$user_create_api = site_url() . "/api/common/user/create.php";
$user_update_api = site_url() . "/api/common/user/update.php";
$user_password_change_api = site_url() . "/api/common/user/password_change.php";
$all_user_details_api = site_url() . "/api/common/user/get_details.php";

/* ********************************** LOGIN, LOGOUT AND USER DETAILS *********************************** */

/* ********************************** ROLE DETAILS *********************************** */

$all_role_details_api = site_url() . "/api/common/role/get_details.php";

/* ********************************** ROLE DETAILS *********************************** */

/* ********************************** CATEGORY *********************************** */

$category_create_api = site_url() . "/api/common/task_category/create.php";
$category_details_api= site_url() . "/api/common/task_category/get_details.php";
$selected_category_detail_api = site_url() . "/api/common/task_category/get_selected.php";
$category_update_api = site_url() . "/api/common/task_category/update.php";

$emp_category_create_api = site_url() . "/api/common/emp_category/create.php";
$emp_category_details_api= site_url() . "/api/common/emp_category/get_details.php";
$emp_selected_category_detail_api = site_url() . "/api/common/emp_category/get_selected.php";
$emp_category_update_api = site_url() . "/api/common/emp_category/update.php";

/* ********************************** CATEGORY *********************************** */

/* ********************************** MEDIA *********************************** */

$media_details_api = site_url() . "/api/common/media/get_details.php";
$media_create_api = site_url() . "/api/common/media/create.php";

/* ********************************** MEDIA *********************************** */

/* ********************************** PROJECT *********************************** */

$project_create_api = site_url() . "/api/project/create.php";
$project_details_api= site_url() . "/api/project/get_details.php";
$selected_project_detail_api = site_url() . "/api/project/get_selected.php";
$project_update_api = site_url() . "/api/project/update.php";

/* ********************************** PROJECT *********************************** */

/* ********************************** PROJECT INFO AND ASSIGN *********************************** */

$project_info_detail_api = site_url() . "/api/project/project_info/get_details.php";
$project_info_create_api = site_url() . "/api/project/project_info/create.php";
$project_info_update_api = site_url() . "/api/project/project_info/update.php";
$selected_project_info_detail_api = site_url() . "/api/project/project_info/get_selected.php";
$project_assign_create_path = site_url() . "/api/project/project_info/assign.php";

/* ********************************** PROJECT INFO AND ASSIGN *********************************** */

/* ********************************** MANUAL *********************************** */

$manual_create_api = site_url() . "/api/manual/create.php";
$manual_details_api = site_url() . "/api/manual/get_details.php";
$selected_manual_detail_api = site_url() . "/api/manual/get_selected.php";
$manual_update_api = site_url() . "/api/manual/update.php";
 
/* ********************************** MANUAL *********************************** */

/* ********************************** GATEPASS *********************************** */

$gatepass_create_api = site_url() . "/api/employee/gatepass/gatepass_generate.php";;
$all_gatepass_details_user_api = site_url() . "/api/employee/gatepass/get_details.php";
$all_gatepass_details_api = site_url() . "/api/employee/gatepass/get_all_details.php";
$selected_gatepass_api = site_url() . "/api/employee/gatepass/get_selected.php";
 
/* ********************************** GATEPASS *********************************** */

/* ********************************** DAILY REPORTS *********************************** */

$all_daily_report_api = site_url() . "/api/employee/reports/get_details.php";
$selected_daily_report_api = site_url() . "/api/employee/reports/get_selected.php";
$report_create_api= site_url() . "/api/employee/reports/create.php";
$report_update_api = site_url() . "/api/employee/reports/update.php";
$sup_remark_daily_report_api = site_url() . "/api/employee/reports/supervisor_remark.php";
$report_media_create_api = site_url() . "/api/employee/reports/create_media.php";
$report_media_detail_api = site_url() . "/api/employee/reports/get_media_details.php";
 
/* ********************************** DAILY REPORTS *********************************** */

/* ********************************** TASKS REPORTS *********************************** */

$task_create_api = site_url() . "/api/employee/tasks/create.php";
$all_task_detail_api = site_url() . "/api/employee/tasks/get_details.php";
$task_update_api = site_url() . "/api/employee/tasks/update.php";
$task_detail_create_api = site_url() . "/api/employee/tasks/create_detail.php";
$task_detail_api = site_url() . "/api/employee/tasks/get_task_details.php";
$task_update_remark_api = site_url() . "/api/employee/tasks/update_remarks.php";
$sel_task_detail_api = site_url() . "/api/employee/tasks/get_selected_task_details.php";
$task_detail_update_api = site_url() . "/api/employee/tasks/update_detail.php";

/* ********************************** TASKS REPORTS *********************************** */

/* ********************************** LOGIN DASHBOARD REPORTS *********************************** */

$ongoing_project_search_api = site_url() . "/api/common/search/ongoing_project.php";
$emp_sup_details_api = site_url() . "/api/common/search/emp_supervisor_details.php";
$profile_search_api = site_url() . "/api/common/search/profile.php";
$project_detail_api = site_url() . "/api/common/search/project_details.php";
$user_count_api = site_url() . "/api/common/search/user_count.php";

/* ********************************** LOGIN DASHBOARD REPORTS *********************************** */

/* ********************************** SELECTED ADMIN REPORTS *********************************** */

$admin_wise_user_detail_api = site_url() . "/api/common/search/admin_wise/user_details.php";
$admin_wise_project_detail_api = site_url() . "/api/common/search/admin_wise/project_details.php";

/* ********************************** SELECTED ADMIN REPORTS *********************************** */

/* ********************************** OVERALL DASHBOARD REPORTS *********************************** */

$over_project_count_api = site_url() . "/api/common/dashboard/project_count.php";
$over_user_count_api = site_url() . "/api/common/dashboard/user_count.php";

/* ********************************** OVERALL DASHBOARD REPORTS *********************************** */
?>

