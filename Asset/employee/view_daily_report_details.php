<?php
$main_page = "View daily report details";
$page = "View daily report details";
include '../api/common/global_functions.php';
include '../api_links.php';
if ($_SESSION['asset_member'] && $_SESSION['asset_token']) {
    $login_user = $_SESSION['asset_member'];
    $login_token = $_SESSION['asset_token'];
    if ($_REQUEST['daily_report_id']) {
        $daily_report_id = $_REQUEST['daily_report_id'];
        $agp_data[] = array("session_token" => $login_token, "session_user" => $login_user, "report_id" => $daily_report_id);
        $agp_recv_data = json_encode($agp_data);
        $getareportData = json_decode(callAPI($agp_recv_data, $selected_daily_report_api));
        $ret_agp_error = $getareportData->error;
        $ret_agp_message = $getareportData->message;
        $ret_agp_data = $getareportData->data;

        if ($ret_agp_error == true) {
            $ausersErr = $ret_agp_status;
        } else {
            foreach ($ret_agp_data as $dataVal) {
                $ret_agp_detail = $dataVal->Details;
            }
            foreach ($ret_agp_detail as $ret_agp_details) {
                $project_name = $ret_agp_details->project_name;
                $project_date = date("d/m/Y", strtotime($ret_agp_details->project_start_date)) . " to " . date("d/m/Y", strtotime($ret_agp_details->project_end_date));
                $safety_clearence = $ret_agp_details->safety_clearence;
                $report_date_time = date("d/m/Y H:i:s", strtotime($ret_agp_details->report_date_time));
                $work_done = $ret_agp_details->work_done;
                $work_done_by = $ret_agp_details->work_done_by;
                $details = $ret_agp_details->details;
                $work_status = $ret_agp_details->work_status;
                $material_shortage = $ret_agp_details->material_shortage;
                $project_location = $ret_agp_details->project_location;
                $gatepass_date = date("d/m/Y", strtotime($ret_agp_details->gatepass_date));
                $hr_cl = $ret_agp_details->hr_clearence;
                $supervisor_name = $ret_agp_details->member_first_name . " " . $ret_agp_details->member_middle_name . " " . $ret_agp_details->member_last_name;
                $reference = $ret_agp_details->reference;
                $emp_remark = $ret_agp_details->emp_remark;
                $sup_remark = $ret_agp_details->supervisor_remark;
                $report_status = $ret_agp_details->report_status;
                $pending_work = $ret_agp_details->pending_work;
                $report_media_count = $ret_agp_details->report_media_count;
                $report_media_data[] = $ret_agp_details->report_media_path . "~" . $ret_agp_details->report_media_type;
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">

            <head>
                <?php include '../header_links.php'; ?>
                <style>
                    #slider {
                        position: relative;
                        overflow: hidden;
                        margin: 20px auto 0 auto;
                        border-radius: 4px;
                    }

                    #slider ul {
                        position: relative;
                        margin: 0;
                        padding: 0;
                        height: 200px;
                        list-style: none;
                    }

                    #slider ul li {
                        position: relative;
                        display: block;
                        float: left;
                        margin: 0;
                        padding: 0;
                        width: 500px;
                        height: 300px;
                        background: #ccc;
                        text-align: center;
                        line-height: 300px;
                    }

                    a.control_prev, a.control_next {
                        position: absolute;
                        top: 40%;
                        z-index: 999;
                        display: block;
                        padding: 4% 3%;
                        width: auto;
                        height: auto;
                        background: #2a2a2a;
                        color: #fff;
                        text-decoration: none;
                        font-weight: 600;
                        font-size: 18px;
                        opacity: 0.8;
                        cursor: pointer;
                    }

                    a.control_prev:hover, a.control_next:hover {
                        opacity: 1;
                        -webkit-transition: all 0.2s ease;
                    }

                    a.control_prev {
                        border-radius: 0 2px 2px 0;
                    }

                    a.control_next {
                        right: 0;
                        border-radius: 2px 0 0 2px;
                    }

                    .slider_option {
                        position: relative;
                        margin: 10px auto;
                        width: 160px;
                        font-size: 18px;
                    }
                </style>
            </head>

            <body onload="startTime()">
                <div class="container-scroller">
                    <?php include './top_menu.php'; ?>
                    <!-- partial -->
                    <div class="container-fluid page-body-wrapper">
                        <?php include './side_menu.php'; ?>
                        <!-- partial -->
                        <div class="main-panel">
                            <div class="content-wrapper">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
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
										 <th>Project :</th><td><?php echo $project_name; ?> ( <?php echo $project_location; ?> )</td>
										 <th>Project period : </th><td><?php echo $project_date; ?></td>
										 </tr>
										 <tr>
										 <th>Safety clearence :</th><td><?php echo $safety_clearence=="Y"?"Yes":"No"; ?></td>
										 <th>HR clearence :</th><td><?php echo $hr_cl=="Y"?"Yes":"No"; ?></td>
										 </tr>
										 <tr>
										 <th>Report date-time : </th><td><?php echo $report_date_time; ?></td>										 
										 <th>Gatepass date :</th><td><?php echo $gatepass_date; ?></td>
										 </tr>
										 <tr>
										 <th>Work done :</th><td><?php echo $work_done; ?></td>
										 <th>Work done by :</th><td><?php echo $work_done_by; ?></td>
										 </tr>
										 <tr>
										 <th>Details : </th><td><?php echo $details; ?></td>										 
										 <th>Work status :</th><td><?php echo $work_status; ?></td>
										 </tr>
										 <tr>
										 <th>Material shortage :</th><td><?php echo $material_shortage; ?></td>
										 <th>Pending Work :</th><td> <?php echo $pending_work; ?></td>
										 </tr>
									     <tr>
										 <th>Reference :</th><td><?php echo $reference; ?></td>
										 <th>Employee remark :</th><td><?php echo $emp_remark; ?></td>
										 </tr>
										  <tr>
										 <th>Supervisor :</th><td><?php echo $supervisor_name; ?></td>
										 <th>Supervisor remark :</th><td><?php echo $sup_remark; ?></td>
										 </tr>										 
										 </table>                                        
                                        <div id="report_footer" style="text-align:center;display:none;margin-top:760px;">
										<hr /> <span style="font-weight:bolder; font-size:16px;"> Big text description</span><br/>
                                               <span style="font-size:13px;"> Small text description</span>
											   </div> 
                                      </div>                                                                               
                                    </div>
										<?php
                                                if ($report_media_count > 0) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <center>
                                                                <div id="slider">
                                                                    <a href="#" class="control_next">></a>
                                                                    <a href="#" class="control_prev"><</a>
                                                                    <ul>
                                                                        <?php
                                                                        foreach ($report_media_data as $report_media_dataVal) {
                                                                            $media_path = explode("~", $report_media_dataVal)[0];
                                                                            $media_type = explode("~", $report_media_dataVal)[1];
                                                                            if (($media_type == "image/jpg") || ($media_type == "image/jpeg") || ($media_type == "image/png")) {
                                                                                ?>
                                                                                <li><img src="../api/employee/reports/<?php echo $media_path; ?>?v=<?php echo time(); ?>"/></li>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <li>
                                                                                    <video controls width="500" height="300">
                                                                                        <source src="../api/employee/reports/<?php echo $media_path; ?>?v=<?php echo time(); ?>" type="<?php echo $media_type; ?>">
                                                                                    </video>
                                                                                </li>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </center>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <!-- /.row -->    
                                                
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
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {


                        var slideCount = $('#slider ul li').length;
                        var slideWidth = $('#slider ul li').width();
                        var slideHeight = $('#slider ul li').height();
                        var sliderUlWidth = slideCount * slideWidth;

                        $('#slider').css({width: slideWidth, height: slideHeight});

                        $('#slider ul').css({width: sliderUlWidth, marginLeft: -slideWidth});

                        $('#slider ul li:last-child').prependTo('#slider ul');

                        function moveLeft() {
                            $('#slider ul').animate({
                                left: +slideWidth
                            }, 200, function () {
                                $('#slider ul li:last-child').prependTo('#slider ul');
                                $('#slider ul').css('left', '');
                            });
                        }
                        ;

                        function moveRight() {
                            $('#slider ul').animate({
                                left: -slideWidth
                            }, 200, function () {
                                $('#slider ul li:first-child').appendTo('#slider ul');
                                $('#slider ul').css('left', '');
                            });
                        }
                        ;

                        $('a.control_prev').click(function () {
                            moveLeft();
                        });

                        $('a.control_next').click(function () {
                            moveRight();
                        });

                    });

                </script>
            </body>

        </html>
        <?php
    }
} else {
    header("location:../index.html");
}
?>