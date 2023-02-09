<?php

/**
 * Description of reportClass
 *
 * @author Soumyanjan
 */
class reportClass {

    protected $connection = null;
    protected $tbl_name = "";

    /*     * ******************************* CONNECTION CREATED ******************************** */

    public function __construct($host_ip, $user, $pass, $db) {
        $this->host = $host_ip;
        $this->username = $user;
        $this->password = $pass;
        $this->database = $db;
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);
    }

    public function getConnection() {
        return $this->connection;
    }

    /*     * ******************************* CONNECTION CREATED ******************************** */

    public function getTableName($table_name) {
        $this->tbl_name = $table_name;
        return $this->tbl_name;
    }

}

class report extends reportClass {
    /*     * ******************************* REPORT DETAILS ******************************** */

    public function getReportDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $user_name = $dataVal->LoginUser;
            $role_id = $dataVal->RoleID;
        }
        if ($role_id == 3) {
            $where_clause .= "AND b.username='$user_name' ";
        } else if ($role_id == 2) {
            $where_clause .= "AND c.supervisor_name='$user_name'";
        } else {
            $where_clause .= "";
        }
        $status = 1;
        $sql .= "SELECT e.gatepass_link_id, e.gatepass_date, a.project_id, a.project_name, a.project_location, a.project_start_date, a.project_end_date, a.project_status, c.report_status, ";
        $sql .= "b.username emp_user, d.member_first_name, d.member_middle_name, d.member_last_name, e.gatepass_attendence, e.gatepass_date, c.daily_report_id, c.supervisor_name, ";
        $sql .= "b.gatepass_id FROM " . $this->getTableName("project_list") . " a LEFT JOIN " . $this->getTableName("gatepass") . " b ON a.project_id=b.project_id ";
        $sql .= "INNER JOIN " . $this->getTableName("gatepass_link") . " e ON b.gatepass_id=e.gatepass_id LEFT JOIN " . $this->getTableName("daily_report") . " c ON e.gatepass_link_id=c.gatepass_link_id ";
        $sql .= "INNER JOIN " . $this->getTableName("member_profile") . " d ON b.username=d.username INNER JOIN " . $this->getTableName("member_login_access") . " f ON f.username=d.username ";
        $sql .= "WHERE f.create_under=? AND a.project_status=? AND b.gatepass_status=? " . $where_clause . " ORDER BY e.gatepass_date";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sii", $create_under, $status, $status);

        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows, "Data" => $data);
        return $retData;
    }

    /*     * ******************************* REPORT DETAILS ******************************** */

    /*     * ******************************* REPORT INSERT ******************************** */

    public function createReport($datas) {
        $reportSQL = "";
        $media_count = 0;
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $gatepass_link_id = $dataVal->GatepassLinkID;
            $safety_clearence = $dataVal->SafetyCL;
            $hr_clearence = $dataVal->HrCL;
            $reportdt = $dataVal->ReportDateTime;
            $supervisor_name = $dataVal->SupervisorName;
            $work_done = $dataVal->WorkDone;
            $work_status = $dataVal->WorkStatus;
            $work_done_by = $dataVal->WorkDoneBy;
            $details = $dataVal->Details;
            $emp_remark = $dataVal->EmpRemark;
            $material_shortage = $dataVal->MatShortage;
            $reference = $dataVal->Reference;
            $pending_work = $dataVal->PendingWork;
            $status = $dataVal->Status;
        }
        $supervisor_remark = "";
        $reportSQL = "INSERT INTO " . $this->getTableName("daily_report") . " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($reportSQL);
        $stmt->bind_param("iisssssssssssssiissss", $id, $gatepass_link_id, $safety_clearence, $hr_clearence, $reportdt, $supervisor_name, $work_done, $work_status, $work_done_by, $reference, $details, $pending_work, $material_shortage, $emp_remark, $supervisor_remark, $media_count, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* REPORT INSERT ******************************** */

    /*     * ******************************* SELECTED REPORT DETAILS ******************************** */

    public function getSelectedReportDetails($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $report_id = $dataVal->ReportID;
        }
        $media_status = 1;
        $sql .= "SELECT a.daily_report_id, a.safety_clearence, a.hr_clearence, a.report_date_time, a.supervisor_name, f.member_first_name, ";
        $sql .= "f.member_middle_name, f.member_last_name, a.work_done, a.work_status, a.work_done_by, a.reference, a.details, ";
        $sql .= "a.pending_work, a.material_shortage, a.emp_remark, a.supervisor_remark, a.report_media_count, b.gatepass_attendence, ";
        $sql .= "b.gatepass_date, d.project_name, d.project_location, d.project_start_date, d.project_end_date, g.report_media_path, ";
        $sql .= "g.report_media_type, g.report_media_status FROM " . $this->getTableName("daily_report") . " a INNER JOIN " . $this->getTableName("gatepass_link") . " b ON a.gatepass_link_id=b.gatepass_link_id ";
        $sql .= "INNER JOIN " . $this->getTableName("gatepass") . " c ON c.gatepass_id=b.gatepass_id INNER JOIN " . $this->getTableName("project_list") . " d ON d.project_id=c.project_id INNER JOIN ";
        $sql .= $this->getTableName("project_info") . " e ON e.project_id=d.project_id INNER JOIN " . $this->getTableName("member_profile") . " ";
        $sql .= "f ON f.username=a.supervisor_name LEFT JOIN " . $this->getTableName("daily_report_media") . " g ON a.daily_report_id=g.report_id ";
        $sql .= "WHERE a.daily_report_id=? AND (g.report_media_status=? OR g.report_media_status IS NULL) ORDER BY a.report_status";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ii", $report_id, $media_status);

        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows, "Data" => $data);
        return $retData;
    }

    /*     * ******************************* SELECTED REPORT DETAILS ******************************** */

    /*     * ******************************* SUPERVISOR REMARKS ******************************** */

    public function supRemark($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $report_id = $dataVal->Slno;
            $remark = $dataVal->SupervisorRemark;
            $report_status = $dataVal->ReportStatus;
        }
        $sql .= "UPDATE " . $this->getTableName("daily_report") . " SET supervisor_remark=?, report_status=? ";
        $sql .= "WHERE daily_report_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sii", $remark, $report_status, $report_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* SUPERVISOR REMARKS ******************************** */

    /*     * ******************************* REPORT UPDATE ******************************** */

    public function updateReport($datas) {
        $reportSQL = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $safety_clearence = $dataVal->SafetyCL;
            $hr_clearence = $dataVal->HrCL;
            $reportdt = $dataVal->ReportDateTime;
            $supervisor_name = $dataVal->SupervisorName;
            $work_done = $dataVal->WorkDone;
            $work_status = $dataVal->WorkStatus;
            $work_done_by = $dataVal->WorkDoneBy;
            $details = $dataVal->Details;
            $emp_remark = $dataVal->EmpRemark;
            $material_shortage = $dataVal->MatShortage;
            $reference = $dataVal->Reference;
            $pending_work = $dataVal->PendingWork;
            $status = $dataVal->Status;
        }
        $reportSQL .= "UPDATE " . $this->getTableName("daily_report") . " SET safety_clearence=?, hr_clearence=?, ";
        $reportSQL .= "report_date_time=?, supervisor_name=?, work_done=?, work_status=?, work_done_by=?, reference=?, ";
        $reportSQL .= "details=?, material_shortage=?, pending_work=?, report_status=?, emp_remark=?, modify_user=?, modify_date=? ";
        $reportSQL .= "WHERE daily_report_id=?";
        $stmt = $this->getConnection()->prepare($reportSQL);
        $stmt->bind_param("sssssssssssisssi", $safety_clearence, $hr_clearence, $reportdt, $supervisor_name, $work_done, $work_status, $work_done_by, $reference, $details, $material_shortage, $pending_work, $status, $emp_remark, $create_user, $datetime, $id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* REPORT UPDATE ******************************** */
}

/* * ******************************* MASTER MEDIA ******************************** */

class media extends reportClass {
    /*     * ******************************* MASTER MEDIA INSERT ******************************** */

    public function createMedia($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $report_id = $dataVal->ReportID;
            $media_path = $dataVal->MediaPath;
            $media_extension = $dataVal->MediaExtension;
        }
        $status = 1;
        $sql = "INSERT INTO " . $this->getTableName("daily_report_media") . " VALUES(?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("iissi", $id, $report_id, $media_path, $media_extension, $status);
        $retVal = $stmt->execute();

        $updt_sql = "UPDATE " . $this->getTableName("daily_report") . " SET report_media_count=report_media_count+1 WHERE daily_report_id=?";
        $updt_stmt = $this->getConnection()->prepare($updt_sql);
        $updt_stmt->bind_param("i", $report_id);
        $updt_retVal = $updt_stmt->execute();

        if (($retVal == true) && ($updt_retVal == true)) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER MEDIA INSERT ******************************** */

    /*     * ******************************* MASTER MEDIA DELETE ******************************** */

    public function deleteMadia($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $delete_media_id = $dataVal->DeleteMediaID;
            $report_id = $dataVal->ReportID;
        }
        $status = 0;
        $sql = "UPDATE " . $this->getTableName("daily_report_media") . " SET report_media_status=? WHERE report_media_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ii", $status, $delete_media_id);
        $retVal = $stmt->execute();

        $updt_sql = "UPDATE " . $this->getTableName("daily_report") . " SET report_media_count=report_media_count-1 WHERE daily_report_id=?";
        $updt_stmt = $this->getConnection()->prepare($updt_sql);
        $updt_stmt->bind_param("i", $report_id);
        $updt_retVal = $updt_stmt->execute();

        if (($retVal == true) && ($updt_retVal == true)) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER MEDIA DELETE ******************************** */

    /*     * ******************************* MASTER MEDIA DETAILS ******************************** */

    public function getMediaDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $report_id = $dataVal->ReportID;
            $status = $dataVal->ReportMediaStatus;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause = "AND a.report_media_status=0 ";
            } else {
                $where_clause = "AND a.report_media_status=1 ";
            }
        }
        $sql .= "SELECT a.report_media_id, b.daily_report_id, a.report_media_path, a.report_media_type, a.report_media_status ";
        $sql .= "FROM " . $this->getTableName("daily_report_media") . " a INNER JOIN " . $this->getTableName("daily_report") . " b ";
        $sql .= "ON a.report_id=b.daily_report_id INNER JOIN " . $this->getTableName("member_login_access") . " c ON c.username=b.create_user ";
        $sql .= "WHERE c.create_under=? AND b.daily_report_id=? " . $where_clause;
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("si", $create_under, $report_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows, "Data" => $data);
        return $retData;
    }

    /*     * ******************************* MASTER MEDIA DETAILS ******************************** */
}

/* * ******************************* MASTER MEDIA ******************************** */