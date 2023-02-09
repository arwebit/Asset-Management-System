<?php

/**
 * Description of projectClass
 *
 * @author Soumyanjan
 */
class projectClass {

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

/* * ******************************* PROJECT ******************************** */

class project extends projectClass {
    /*     * ******************************* PROJECT INSERT ******************************** */

    public function createProject($datas) {
        $projectSQL = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $project_name = $dataVal->ProjectName;
            $project_location = $dataVal->ProjectLocation;
            $project_address = $dataVal->ProjectAddress;
            $project_scope = $dataVal->ProjectScope;
            $project_start_date = $dataVal->ProjectStartDate;
            $project_end_date = $dataVal->ProjectEndDate;
        }
        $status = 1;
        $projectSQL = "INSERT INTO " . $this->getTableName("project_list") . " VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($projectSQL);
        $stmt->bind_param("issssssissss", $id, $project_name, $project_location, $project_address, $project_scope, $project_start_date, $project_end_date, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT INSERT ******************************** */

    /*     * ******************************* PROJECT AVAILABILITY ******************************** */

    public function availProject($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $project_id = $dataVal->Project_id;
            $status = $dataVal->Status;
        }
        $sql .= "UPDATE " . $this->getTableName("project_list") . " SET project_status=?, modify_user=?, ";
        $sql .= "modify_date=? WHERE project_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isss", $status, $modify_user, $datetime, $project_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT AVAILABILITY ******************************** */


    /*     * ******************************* PROJECT UPDATE ******************************** */

    public function updateProject($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_project_id = $dataVal->Updateprojectid;
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Modifyuser;
            $project_name = $dataVal->ProjectName;
            $project_location = $dataVal->ProjectLocation;
            $project_address = $dataVal->ProjectAddress;
            $project_scope = $dataVal->ProjectScope;
            $project_start_date = $dataVal->ProjectStartDate;
            $project_end_date = $dataVal->ProjectEndDate;
        }
        $sql .= "UPDATE " . $this->getTableName("project_list") . " SET project_name=?, project_location=?, project_scope=?,";
        $sql .= "project_address=?, project_start_date=?, project_end_date=?, modify_user=?, modify_date=? WHERE project_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ssssssssi", $project_name, $project_location, $project_scope, $project_address, $project_start_date, $project_end_date, $modify_user, $datetime, $update_project_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT UPDATE ******************************** */

    /*     * ******************************* PROJECT DETAILS ******************************** */

    public function getProjectDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
            $user = $dataVal->LoginUser;
            $user_role = $dataVal->UserRole;
            $project_name = $dataVal->ProjectName;
            $project_location = $dataVal->ProjectLocation;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND a.project_status=0 ";
            } else {
                $where_clause .= "AND a.project_status=1 ";
            }
        }
        if ($project_name != "") {
            $where_clause .= "AND UPPER(a.project_name) LIKE '%" . strtoupper($project_name) . "%' ";
        }
        if ($project_location != "") {
            $where_clause .= "AND UPPER(a.project_location) LIKE '%" . strtoupper($project_location) . "%' ";
        }
        if ($user_role > 2) {
            
        } else {
            if ($user != "") {
                $where_clause .= "AND b.project_supervisor ='$user'";
            }
        }
        $sql .= "SELECT a.project_id, a.project_name, a.project_location, a.project_scope, a.project_address, a.project_start_date, a.project_end_date FROM ";
        $sql .= $this->getTableName("project_list") . " a LEFT JOIN " . $this->getTableName("project_info") . " b ON ";
        $sql .= "a.project_id=b.project_id INNER JOIN ".$this->getTableName("member_login_access") ." c ON a.create_user=c.username ";
        $sql .= "WHERE c.create_under=? " . $where_clause . " GROUP BY a.project_id, a.project_name, a.project_location, a.project_start_date, a.project_end_date  ORDER BY a.project_name";
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

    /*     * ******************************* PROJECT DETAILS ******************************** */


    /*     * ******************************* SELECTED PROJECT ******************************** */

    public function getSelectedProjectDetails($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $project_id = $dataVal->ProjectID;
        }
        $sql .= "SELECT a.project_id, a.project_name, a.project_location, a.project_scope, a.project_address, a.project_start_date, a.project_end_date FROM ";
        $sql .= $this->getTableName("project_list") . " a LEFT JOIN " . $this->getTableName("project_info") . " b ON ";
        $sql .= "a.project_id=b.project_id WHERE a.project_id=? GROUP BY a.project_id, a.project_name, ";
        $sql .= "a.project_location, a.project_start_date, a.project_end_date  ORDER BY a.project_name";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $project_id);
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

    /*     * ******************************* SELECTED PROJECT ******************************** */
}

/* * ******************************* PROJECT ******************************** */

/* * ******************************* PROJECT INFO ******************************** */

class projectInfo extends projectClass {
    /*     * ******************************* SELECTED PROJECT INFO ******************************** */

    public function getProjectInfo($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        $where_clause = "";
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $project_id = $dataVal->ProjectID;
            $status = $dataVal->Status;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND project_status=0 ";
            } else {
                $where_clause .= "AND project_status=1 ";
            }
        }
        $sql .= "SELECT a.project_info_id, a.project_supervisor_start_date, a.project_supervisor_end_date, b.member_first_name, member_middle_name, ";
        $sql .= "b.member_last_name, a.project_info_status, c.create_under FROM " . $this->getTableName("project_info") . " a INNER JOIN ";
        $sql .= $this->getTableName("member_profile") . " b ON a.project_supervisor=b.username INNER JOIN ". $this->getTableName("member_login_access") ." c ";
        $sql .= "ON c.username=b.username WHERE c.create_under=? AND a.project_id=? " . $where_clause . " ORDER BY project_info_status DESC";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("si", $create_under, $project_id);
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

    /*     * ******************************* SELECTED PROJECT INFO ******************************** */

    /*     * ******************************* PROJECT INFO INSERT ******************************** */

    public function createProjectInfo($datas) {
        $projectSQL = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $project_id = $dataVal->ProjectId;
            $project_super_name = $dataVal->ProjectSuperName;
            $project_start_date = $dataVal->ProjectStartDate;
            $project_end_date = $dataVal->ProjectEndDate;
        }
        $status = 1;
        $projectSQL = "INSERT INTO " . $this->getTableName("project_info") . " VALUES(?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($projectSQL);
        $stmt->bind_param("iisssissss", $id, $project_id, $project_super_name, $project_start_date, $project_end_date, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT INFO INSERT ******************************** */

    /*     * ******************************* PROJECT INFO AVAILABILITY ******************************** */

    public function availProjectInfo($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $project_info_id = $dataVal->Project_info_id;
            $status = $dataVal->Status;
        }
        $sql .= "UPDATE " . $this->getTableName("project_info") . " SET project_info_status=?, modify_user=?, ";
        $sql .= "modify_date=? WHERE project_info_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isss", $status, $modify_user, $datetime, $project_info_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT INFO AVAILABILITY ******************************** */

    /*     * ******************************* SELECTED PROJECT INFO******************************** */

    public function getSelectedProjectInfoDetails($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $project_info_id = $dataVal->ProjectInfoID;
        }
        $sql = "SELECT * FROM " . $this->getTableName("project_info") . " WHERE project_info_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $project_info_id);
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

    /*     * ******************************* SELECTED PROJECT INFO ******************************** */

    /*     * ******************************* PROJECT INFO UPDATE ******************************** */

    public function updateProjectInfo($datas) {
        $projectSQL = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $project_info_id = $dataVal->ProjectInfoId;
            $project_super_name = $dataVal->ProjectSuperName;
            $project_start_date = $dataVal->ProjectStartDate;
            $project_end_date = $dataVal->ProjectEndDate;
            $status = $dataVal->Status;
        }

        $projectSQL .= "UPDATE " . $this->getTableName("project_info") . " SET project_supervisor=?, project_supervisor_start_date=?, ";
        $projectSQL .= "project_supervisor_end_date=?, project_info_status=?, modify_user=?, modify_date=? WHERE project_info_id=?";
        $stmt = $this->getConnection()->prepare($projectSQL);
        $stmt->bind_param("sssissi", $project_super_name, $project_start_date, $project_end_date, $status, $create_user, $datetime, $project_info_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT INFO UPDATE ******************************** */
    
}

/* * ******************************* PROJECT INFO******************************** */





