<?php
$main_page = "View task details";
$page = "View task details";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['task_detail_id']) {
        $task_detail_id = $_REQUEST['task_detail_id'];
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
                        <?php
                        include './side_menu.php';
                        $atasks_data[] = array("session_token" => $login_token, "session_user" => $login_user, "task_detail_id" => $task_detail_id);
                        $atasks_recv_data = json_encode($atasks_data);
                        $getatasksData = json_decode(callAPI($atasks_recv_data, $sel_task_detail_api));
                        $ret_atasks_error = $getatasksData->error;
                        $ret_atasks_message = $getatasksData->message;
                        $ret_atasks_data = $getatasksData->data;

                        if ($ret_atasks_error == true) {
                            $atasksErr = $ret_atasks_status;
                        } else {
                            foreach ($ret_atasks_data as $dataVal) {
                                $ret_atasks_detail = $dataVal->Details;
                            }
                            foreach ($ret_atasks_detail as $ret_atasks_details) {
                                $fproject_name = $ret_atasks_details->project_name;
                                $fproject_location = $ret_atasks_details->project_location;
                                $fcategory_name = $ret_atasks_details->category_name;
                                $ftask_description = $ret_atasks_details->task_description;
                                $ftask_standard_value = $ret_atasks_details->standard_value;
                                $ftask_unit = $ret_atasks_details->task_unit;
                                $fdeviation_min_range = $ret_atasks_details->min_deviation;
                                $fdeviation_max_range = $ret_atasks_details->max_deviation;
                                $ftask_actual_value = $ret_atasks_details->actual_value;
                                $femp_remarks = nl2br($ret_atasks_details->emp_remarks);
                                $fcheck_supervisor = $ret_atasks_details->checked_by;
                                $femp_name = $ret_atasks_details->emp_first_name . " " . $ret_atasks_details->emp_middle_name . " " . $ret_atasks_details->emp_last_name;
                                $fcheck_supervisor_name = $ret_atasks_details->supervisor_first_name . " " . $ret_atasks_details->supervisor_middle_name . " " . $ret_atasks_details->supervisor_last_name;
                                $fsupervisor_name = $ret_atasks_details->supervisor_approval_first_name . " " . $ret_atasks_details->supervisor_approval_middle_name . " " . $ret_atasks_details->supervisor_approval_last_name;
                                $fadmin_name = $ret_atasks_details->admin_first_name . " " . $ret_atasks_details->admin_middle_name . " " . $ret_atasks_details->admin_last_name;
                                $fhr_name = $ret_atasks_details->hr_first_name . " " . $ret_atasks_details->hr_middle_name . " " . $ret_atasks_details->hr_last_name;
                                $deviation_value = $ftask_standard_value - $ftask_actual_value;
                                if (($deviation_value <= $fdeviation_max_range) && ($deviation_value >= $fdeviation_min_range)) {
                                    $fdeviation_status = "Ok";
                                } else {
                                    $fdeviation_status = "Not ok";
                                }
                                $fsup_approval = $ret_atasks_details->supervisor_approval;
                                $fadmin_approval = $ret_atasks_details->admin_approval;
                                $fadmin_remarks = nl2br($ret_atasks_details->admin_remarks);
                                $fsupervisor_remarks = nl2br($ret_atasks_details->supervisor_remarks);
                            }
                        }
                        ?>
                        <!-- partial -->
                        <div class="main-panel">
                            <div class="content-wrapper">
                                <div class="card">
                                    <div class="card-body">
                                        <button class="btn btn-info" onclick="print_area('printableArea');">
                                            Print
                                        </button>
                                        <div id="printableArea">
                                         <div id="report_header" style="text-align:center;display:none;"> 
                                         <span style="font-weight:bolder; font-size:20px;">BIG COMPANY NAME FOR TEST</span><br/><br/>		
                                         <span style="font-weight:700; font-size:16px; font-style:italic;"> Company address 1 <br/>
										 Company address 2<br/><br/>
										 Mobile : +91-1010101010 , Email : Email@email.com</span>	<br/>									 
										 <hr /> </div><br />
										 
										 <table class="table table-bordered">
										 <tr>
										 <th>Project :</th><td><?php echo $fproject_name; ?> ( <?php echo $fproject_location; ?> )</td>
										 <th>Category : </th><td><?php echo $fcategory_name; ?></td>
										 </tr>
										 <tr>
										 <th>Task description :</th><td><?php echo $ftask_description; ?></td>
										 <th>Task unit :</th><td><?php echo $ftask_unit; ?></td>
										 </tr>
										 <tr>
										 <th>Deviation maximum range : </th><td><?php echo $fdeviation_max_range; ?></td>										 
										 <th>Deviation minimum range :</th><td><?php echo $fdeviation_min_range; ?></td>
										 </tr>
										 <tr>
										 <th>Task standard value :</th><td><?php echo $ftask_standard_value; ?></td>
										 <th>Task actual value :</th><td><?php echo $ftask_actual_value; ?></td>
										 </tr>
										 <tr>
										 <th>Checked by (Supervisor name) : </th><td><?php echo $fcheck_supervisor_name; ?></td>										 
										 <th>Employee remarks :</th><td><?php echo $femp_remarks; ?></td>
										 </tr>
										 <tr>
										 <th>Report submitted by :</th><td> <?php echo $femp_name; ?></td>
										 <th>Deviation status :</th><td><?php echo $fdeviation_status; ?></td>
										 </tr>
										 <tr>
										 <th>Supervisor approval :</th><td><?php echo $fsupervisor_name; ?> (<?php echo $fsup_approval == "1" ? "Approved" : "Not approved"; ?>)</td>
										 <th>Supervisor remarks :</th><td><?php echo $fsupervisor_remarks; ?></td>
										 </tr>
										 <tr>
										 <th>Admin approval :</th><td><?php echo $fadmin_name; ?> (<?php echo $fadmin_approval == "1" ? "Approved" : "Not approved"; ?>)</td>
										 <th>Admin remarks :</th><td><?php echo $fadmin_remarks; ?></td>
										 </tr>
										 <tr>
										 <th colspan="4">
										 <?php
                                        if ($fadmin_approval != 1) {
                                            ?>
                                            <center>Task is pending</center>
                                            <?php
                                        } else {
                                            ?>
                                            <center>Task is fully approved</center>
                                            <?php
                                        }
                                        ?>
										 </th>
										 </tr>
										 </table>
                                        
                                        <div id="report_footer" style="text-align:center;display:none;margin-top:760px;">
										<hr /> <span style="font-weight:bolder; font-size:16px;"> Big text description</span><br/>
                                               <span style="font-size:13px;"> Small text description</span>
											   </div> 
                                      </div>                                                                               
                                    </div>
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
       
        </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>