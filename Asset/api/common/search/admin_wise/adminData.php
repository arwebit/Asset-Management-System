<?php

/**
 * Description of adminData
 *
 * @author HP
 */
class adminData {

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

class adminRecords extends adminData{
    
    /*     * ******************************* USER COUNT ******************************** */

    public function getUserCount($datas) {
        $sql = "";
        $role_id = 0;
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
        }
        $sql .= "SELECT a.role_id, a.role_name, COUNT(CASE WHEN b.user_status=0 THEN 1  END) inactive_users, COUNT(CASE WHEN b.user_status=1 THEN 1 END) ";
        $sql .= "active_users FROM " . $this->getTableName("mas_role") . " a LEFT JOIN ".$this->getTableName("member_login_access") . " b ";
        $sql .= "ON b.user_role=a.role_id WHERE a.role_id>? AND (b.create_under=? OR b.create_under IS NULL) GROUP BY a.role_id, a.role_name ORDER BY b.user_role ";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("is", $role_id,$create_under);
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

    /*     * ******************************* USER COUNT ******************************** */

    /*     * ******************************* MASTER USER DETAILS ******************************** */

    public function getUserDetails($datas) {
        $sql = "";
        $role_id = -1;
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
        }
        $sql .= "SELECT a.member_id, a.username, a.password, b.member_first_name, b.member_middle_name, b.member_last_name, b.member_mobile, b.member_address, ";
        $sql .= "b.member_email, c.role_name, a.user_previliges, c.role_id, a.user_status, b.create_user, b.modify_user, b.create_date, b.modify_date FROM " . $this->getTableName("member_login_access") . " ";
        $sql .= "a INNER JOIN " . $this->getTableName("member_profile") . " b ON a.username=b.username INNER JOIN " . $this->getTableName("mas_role") . " ";
        $sql .= "c ON a.user_role=c.role_id WHERE a.create_under=? AND a.user_role>? ORDER BY b.member_first_name, b.member_middle_name, b.member_last_name";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("si", $create_under, $role_id);
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

    /*     * ******************************* MASTER USER DETAILS ******************************** */
    
    /*     * ******************************* PROJECT COUNT ******************************** */

    public function getProjectCount($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $curr_date = $dataVal->CurrentDate;
        }
        $sql .= "SELECT COUNT(*) total_project, COUNT(CASE WHEN project_status = 0  THEN 1 END) AS completed_project,";
        $sql .= "COUNT(CASE WHEN project_status = 1 AND project_start_date<=? THEN 1 END) AS ongoing_project, ";
        $sql .= "COUNT(CASE WHEN project_status = 1 AND project_start_date>? THEN 1 END) AS upcoming_project ";
        $sql .= "FROM " . $this->getTableName("project_list") . " a INNER JOIN " . $this->getTableName("member_login_access") . " b ";
        $sql .= "ON a.create_user=b.username WHERE b.create_under=?";

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sss", $curr_date, $curr_date, $create_under);
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

    /*     * ******************************* PROJECT COUNT ******************************** */
    
    /*     * ******************************* SEARCH PROJECT DETAILS ******************************** */

    public function getProjectStatus($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $curr_date = $dataVal->CurrentDate;
            $create_under = $dataVal->CreateUnder;
        }
        
        $sql .= "SELECT project_id, project_name, project_location, project_start_date, project_start_date, project_end_date, ";
        $sql .= "DATEDIFF(project_end_date, ?) age, project_status FROM " . $this->getTableName("project_list") . " a INNER JOIN ";
        $sql .= $this->getTableName("member_login_access") . " b ON a.create_user=b.username WHERE b.create_under=? ORDER BY project_end_date DESC";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $curr_date, $create_under);
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

    /*     * ******************************* SEARCH PROJECT DETAILS ******************************** */
}
