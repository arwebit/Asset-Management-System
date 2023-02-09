<?php

/**
 * Description of gatepassGeneration
 *
 * @author Soumyanjan
 */
class gatepassGeneration {

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

class gatepass extends gatepassGeneration {
    /*     * ******************************* GATEPASS INSERT ******************************** */

    public function createGatepass($datas) {
        $gatepassSQL = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $user_name = $dataVal->Username;
            $project_id = $dataVal->ProjectID;
            $pdf_str = $dataVal->PDFStr;
            $gatepass_start_date = $dataVal->GatepassStartDate;
            $gatepass_end_date = $dataVal->GatepassEndDate;
            $gp_status = $dataVal->GatepassStatus;
        }
        $gatepassSQL = "INSERT INTO " . $this->getTableName("gatepass") . " VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($gatepassSQL);
        $stmt->bind_param("isisssissss", $id, $user_name, $project_id, $gatepass_start_date, $gatepass_end_date, $pdf_str, $gp_status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS INSERT ******************************** */

    /*     * ******************************* GATEPASS LINK INSERT ******************************** */

    public function createGatepassLink($datas) {
        $gatepassLinkSQL = "";
        $gp_attendence = 0;
        $loginTime = null;
        $logoutTime = null;
        $loginStatus = 0;
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $gatepassid = $dataVal->GatepassID;
            $gatepass_date = $dataVal->GatepassDate;
        }

        $gatepassLinkSQL .= "INSERT INTO " . $this->getTableName("gatepass_link") . "(gatepass_id,gatepass_attendence,";
        $gatepassLinkSQL .= "gatepass_date,login_logout_status,login_time,logout_time) VALUES(?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($gatepassLinkSQL);
        $stmt->bind_param("iisiss", $gatepassid, $gp_attendence, $gatepass_date, $loginStatus, $loginTime, $logoutTime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS LINK INSERT ******************************** */

    /*     * ******************************* GATEPASS DETAILS ******************************** */

    public function getGatepassDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
            $user = $dataVal->LoginUser;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND a.gatepass_status=0 ";
                if ($user != "") {
                    $where_clause .= "AND UPPER(b.username) = '$user' ";
                }
            } else if (($status == "Active") || ($status == "Request")) {
                $where_clause .= "AND (a.gatepass_status=2 OR a.gatepass_status=1) ";
                if ($user != "") {
                    $where_clause .= "AND UPPER(b.username) = '$user' ";
                }
            } else if ($status == "Request_all") {
                $where_clause .= "AND a.gatepass_status=2 ";
            } else {
                $where_clause .= "AND a.gatepass_status=3 ";
            }
        }

        $sql .= "SELECT a.gatepass_id, b.member_id, b.username, b.member_first_name, b.member_middle_name, b.member_last_name, c.project_id, a.gatepass_start_date, a.gatepass_end_date, ";
        $sql .= "c.project_name, c.project_location, a.gatepass_string, a.gatepass_status FROM " . $this->getTableName("gatepass") . " a INNER JOIN " . $this->getTableName("member_profile") . " b ";
        $sql .= "ON a.username=b.username INNER JOIN " . $this->getTableName("project_list") . " c ON a.project_id=c.project_id INNER JOIN ". $this->getTableName("member_login_access") ." d ";
        $sql .= "ON d.username=b.username WHERE d.create_under=? ".$where_clause . "ORDER BY a.gatepass_end_date DESC";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("s", $create_under);
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

    /*     * ******************************* GATEPASS DETAILS ******************************** */


    /*     * ******************************* ALL GATEPASS DETAILS ******************************** */

    public function getAllGatepassDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND a.gatepass_status=0 ";
            } else if ($status == "Active") {
                $where_clause .= "AND a.gatepass_status=1 ";
            } else {
                $where_clause .= "AND a.gatepass_status=3 ";
            }
        }
        $sql .= "SELECT a.gatepass_id, b.member_id, b.username, b.member_first_name, b.member_middle_name, b.member_last_name, c.project_id, c.project_name, a.gatepass_start_date, ";
        $sql .= "a.gatepass_end_date, c.project_location, a.gatepass_string, a.gatepass_status FROM " . $this->getTableName("gatepass") . " a INNER JOIN " . $this->getTableName("member_profile") . " b ";
        $sql .= "ON a.username=b.username INNER JOIN " . $this->getTableName("project_list") . " c ON a.project_id=c.project_id INNER JOIN ". $this->getTableName("member_login_access") ." d ";
        $sql .= "ON d.username=a.username WHERE d.create_under=? " . $where_clause . " GROUP BY a.gatepass_id, b.member_id, b.username, b.member_first_name, ";
        $sql .= "b.member_middle_name, b.member_last_name, c.project_id, c.project_name, a.gatepass_start_date, a.gatepass_end_date, c.project_location, a.gatepass_string,";
        $sql .= "a.gatepass_status ORDER BY a.gatepass_end_date DESC";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("s", $create_under);
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

    /*     * ******************************* ALL GATEPASS DETAILS ******************************** */

    /*     * ******************************* SELECTED GATEPASS DETAILS ******************************** */

    public function getSelectedGatepass($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $gatepass_id = $dataVal->GatepassID;
            $gp_date = $dataVal->GatepassDate;
        }
        if ($gp_date != "") {
            $where_clause = "AND d.gatepass_date='$gp_date'";
        }
        $sql .= "SELECT a.gatepass_id, d.gatepass_link_id, b.member_id, b.username, b.member_first_name, b.member_middle_name, ";
        $sql .= "b.member_last_name, c.project_id, d.gatepass_date, c.project_name, c.project_location, a.gatepass_string, ";
        $sql .= "d.gatepass_attendence, a.gatepass_status, d.login_logout_status, d.login_time, d.logout_time, CASE WHEN ";
        $sql .= "d.login_logout_status=0 AND d.logout_time IS NULL THEN '0' WHEN d.login_logout_status=0 AND ";
        $sql .="d.logout_time IS NOT NULL THEN '2' WHEN d.login_logout_status=1 THEN '1' END AS loginlogout_activiy ";
        $sql .= "FROM ".$this->getTableName("gatepass") . " a INNER JOIN " . $this->getTableName("member_profile") . " b ";
        $sql .= "ON a.username=b.username INNER JOIN " . $this->getTableName("project_list") . " c ON a.project_id=c.project_id ";
        $sql .= "INNER JOIN " . $this->getTableName("gatepass_link") . " d ON d.gatepass_id=a.gatepass_id WHERE d.gatepass_id=? " . $where_clause;
       $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $gatepass_id);
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

    /*     * ******************************* SELECTED GATEPASS DETAILS ******************************** */


    /*     * ******************************* GATEPASS ACCEPT/REJECT ******************************** */

    public function availGatepass($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $gatepass_id = $dataVal->GatepassID;
            $status = $dataVal->Status;
        }

        $sql = "UPDATE " . $this->getTableName("gatepass") . " SET gatepass_status=?, modify_user=?, modify_date=? WHERE gatepass_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isss", $status, $modify_user, $datetime, $gatepass_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS ACCEPT/REJECT ******************************** */

    /*     * ******************************* GATEPASS DELETE ******************************** */

    public function deleteGatepass($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $gatepass_id = $dataVal->GatepassID;
        }
        $gatepassSQL = "DELETE FROM " . $this->getTableName("gatepass") . " WHERE gatepass_id=?";
        $gatepass_stmt = $this->getConnection()->prepare($gatepassSQL);
        $gatepass_stmt->bind_param("i", $gatepass_id);
        $gatepass_retVal = $gatepass_stmt->execute();

        $gatepass_linkSQL = "DELETE FROM " . $this->getTableName("gatepass_link")." WHERE gatepass_id=?";
        $gatepass_link_stmt = $this->getConnection()->prepare($gatepass_linkSQL);
        $gatepass_link_stmt->bind_param("i", $gatepass_id);
        $gatepassLink_retVal = $gatepass_link_stmt->execute();
        
        if (($gatepass_retVal == true) && ($gatepassLink_retVal == true)){
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS DELETE ******************************** */

    /*     * ******************************* GATEPASS ATTENDENCE ******************************** */

    public function gatepassAttendence($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $gatepass_link_id = $dataVal->GatepassLinkID;
            $attendence = $dataVal->Status;
        }
        $sql = "UPDATE " . $this->getTableName("gatepass_link") . " SET gatepass_attendence=? WHERE gatepass_link_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ii", $attendence, $gatepass_link_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS ATTENDENCE ******************************** */

    /*     * ******************************* GATEPASS LOGIN LOGOUT ******************************** */

    public function login_logoutGatepass($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $current_time = $dataVal->Current_time;
            $gatepass_link_id = $dataVal->GatepassLinkID;
            $login_logout_status = $dataVal->LoginLogoutStatus;
        }
        if ($login_logout_status == 1) {
            $sql .= "UPDATE " . $this->getTableName("gatepass_link") . " SET login_logout_status=?, login_time=? ";
            $sql .= "WHERE gatepass_link_id=?";
        } else {
            $sql .= "UPDATE " . $this->getTableName("gatepass_link") . " SET login_logout_status=?, logout_time=? ";
            $sql .= "WHERE gatepass_link_id=?";
        }

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isi", $login_logout_status, $current_time, $gatepass_link_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS LOGIN LOGOUT ******************************** */
}
