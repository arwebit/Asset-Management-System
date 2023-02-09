<?php

/**
 * Description of taskGenerate
 *
 * @author HP
 */
class taskGenerate {

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

class taskMaster extends taskGenerate {
    /*     * ******************************* TASK MASTER DETAILS ******************************** */

    public function getTaskMasterDetails($datas) {
        $sql = "";
        $where_clause = "";
        $status = 1;
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $assign_name = $dataVal->UserName;
            $user_role = $dataVal->UserRole;
            $task_master_id = $dataVal->TaskMasterID;
        }
        if ($task_master_id != "") {
            $where_clause .= "AND b.task_master_id='$task_master_id'";
        }
        if ($user_role < 3) {
            if ($user_role == 2) {
                $where_clause .= "AND d.project_supervisor='$assign_name'";
            } else {
                $where_clause .= "";
            }
        } else {
            $where_clause .= "";
        }

        $sql .= "SELECT b.project_id, a.project_name, b.category_id, c.category_name, d.project_supervisor, b.task_master_id, b.task_description, b.task_unit, ";
        $sql .= "b.standard_value, b.min_deviation, b.max_deviation, b.task_status, b.create_user FROM " . $this->getTableName("project_list") . " a INNER JOIN ";
        $sql .= $this->getTableName("task_master") . " b ON a.project_id=b.project_id INNER JOIN " . $this->getTableName("mas_category") . " c ";
        $sql .= "ON b.category_id=c.category_id INNER JOIN  ". $this->getTableName("project_info") ." d ON a.project_id=d.project_id INNER JOIN " . $this->getTableName("member_login_access") . " e ";
        $sql .= "ON e.username=b.create_user WHERE e.create_under=? AND a.project_status=? AND b.task_status=? " . $where_clause;
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sii", $create_under,$status, $status);

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

    /*     * ******************************* TASK MASTER DETAILS ******************************** */

    /*     * ******************************* TASK MASTER INSERT ******************************** */

    public function createTaskMaster($datas) {
        $taskMasterSQL = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $project_id = $dataVal->ProjectID;
            $category_id = $dataVal->CategoryID;
            $task_description = $dataVal->TaskDescription;
            $task_unit = $dataVal->TaskUnit;
            $standard_value = $dataVal->StandardValue;
            $min_deviation = $dataVal->MinDeviation;
            $max_deviation = $dataVal->MaxDeviation;
        }
        $status = 1;
        $taskMasterSQL = "INSERT INTO " . $this->getTableName("task_master") . " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($taskMasterSQL);
        $stmt->bind_param("iiissdddissss", $id, $project_id, $category_id, $task_description, $task_unit, $standard_value, $min_deviation, $max_deviation, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* TASK MASTER INSERT ******************************** */

    /*     * ******************************* TASK MASTER UPDATE ******************************** */

    public function updateTaskMaster($datas) {
        $taskMasterSQL = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $project_id = $dataVal->ProjectID;
            $category_id = $dataVal->CategoryID;
            $task_description = $dataVal->TaskDescription;
            $task_unit = $dataVal->TaskUnit;
            $standard_value = $dataVal->StandardValue;
            $min_deviation = $dataVal->MinDeviation;
            $max_deviation = $dataVal->MaxDeviation;
        }
        $taskMasterSQL .= "UPDATE " . $this->getTableName("task_master") . " SET project_id=?, category_id=?, task_unit=?, ";
        $taskMasterSQL .= "task_description=?, standard_value=?, min_deviation=?, max_deviation=?, modify_user=?, ";
        $taskMasterSQL .= "modify_date=? WHERE task_master_id=?";
        $stmt = $this->getConnection()->prepare($taskMasterSQL);
        $stmt->bind_param("iissdddssi", $project_id, $category_id, $task_unit, $task_description, $standard_value, $min_deviation, $max_deviation, $create_user, $datetime, $id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* TASK MASTER UPDATE ******************************** */


    /*     * ******************************* TASK MASTER EMPLOYEE DETAILS ******************************** */

    public function getTaskMasterEmp($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $emp_user = $dataVal->Empuser;
            $task_master_id = $dataVal->TaskMasterID;
            $user_role = $dataVal->UserRole;
        }
        if ($task_master_id != "") {
            $where_clause .= "AND b.task_master_id='$task_master_id' ";
        }
        if ($user_role > 2) {
            if ($emp_user != "") {
                $where_clause .= "AND d.username='$emp_user' ";
            }
        }
        $status = 1;
        $sql .= "SELECT b.project_id, a.project_name, b.category_id, c.category_name, b.task_master_id, b.task_description, b.task_unit, ";
        $sql .= "b.standard_value, b.min_deviation, b.max_deviation, f.admin_approval, f.create_user task_emp, f.supervisor_approval, b.task_status, ";
        $sql .= "g.member_first_name emp_first_name,g.member_middle_name emp_middle_name,g.member_last_name emp_last_name, GROUP_CONCAT(e.project_supervisor) AS project_supervisor, f.task_detail_id FROM " . $this->getTableName("project_list") . " a INNER JOIN ";
        $sql .= $this->getTableName("task_master") . " b ON a.project_id=b.project_id INNER JOIN " . $this->getTableName("mas_category") . " c ";
        $sql .= "ON b.category_id=c.category_id INNER JOIN " . $this->getTableName("gatepass") . " d ON a.project_id=d.project_id INNER JOIN ";
        $sql .= $this->getTableName("project_info") . " e ON a.project_id=e.project_id LEFT JOIN " . $this->getTableName("task_details") . " f ON b.task_master_id=f.task_master_id ";
        $sql .= "LEFT JOIN " . $this->getTableName("member_profile") . " g ON g.username=f.create_user LEFT JOIN " . $this->getTableName("member_login_access") . " h ON h.username=b.create_user WHERE h.create_under=? AND a.project_status=? AND b.task_status=? " . $where_clause . " ";
        $sql .= "GROUP BY b.project_id, a.project_name, b.category_id, c.category_name, b.task_master_id, b.task_description, b.task_unit, f.create_user, ";
        $sql .= "b.standard_value, b.min_deviation, b.max_deviation, b.task_status, g.member_first_name, g.member_middle_name, g.member_last_name";
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

    /*     * ******************************* TASK MASTER EMPLOYEE DETAILS ******************************** */


    /*     * ******************************* TASK DETAILS INSERT ******************************** */

    public function createTaskDetails($datas) {
        $taskDetailSQL = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $task_master_id = $dataVal->TaskMasterID;
            $task_actual_value = $dataVal->TaskActualValue;
            $supervisor_name = $dataVal->Supervisor;
            $emp_remarks = $dataVal->EmpRemarks;
        }
        $taskDetailSQL .= "INSERT INTO " . $this->getTableName("task_details") . "(task_detail_id, task_master_id, actual_value, ";
        $taskDetailSQL .= "checked_by, emp_remarks, create_user, create_date, modify_user, modify_date) VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($taskDetailSQL);
        $stmt->bind_param("iidssssss", $id, $task_master_id, $task_actual_value, $supervisor_name, $emp_remarks, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* TASK DETAILS INSERT ******************************** */

    /*     * ******************************* TASK DETAILS UPDATE ******************************** */

    public function updateTaskDetails($datas) {
        $taskDetailSQL = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $task_detail_id = $dataVal->TaskDetailID;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $task_actual_value = $dataVal->TaskActualValue;
            $supervisor_name = $dataVal->Supervisor;
            $emp_remarks = $dataVal->EmpRemarks;
        }
        $taskDetailSQL .= "UPDATE " . $this->getTableName("task_details") . " SET actual_value=?, checked_by=?, emp_remarks=?, ";
        $taskDetailSQL .= "modify_user=?, modify_date=? WHERE task_detail_id=?";
        $stmt = $this->getConnection()->prepare($taskDetailSQL);
        $stmt->bind_param("dssssi", $task_actual_value, $supervisor_name, $emp_remarks, $create_user, $datetime, $task_detail_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* TASK DETAILS UPDATE ******************************** */



    /*     * ******************************* SELECTED TASK DETAILS ******************************** */

    public function getSelectedTaskDetails($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $task_detail_id = $dataVal->TaskDetailID;
        }

        $sql .= "SELECT a.project_id, g.project_name, g.project_location, a.category_id, h.category_name, a.task_description, a.task_unit, a.standard_value, a.min_deviation, ";
        $sql .= "a.max_deviation, b.actual_value, b.checked_by, b.admin_approval, b.admin_approval_by, b.hr_approval, ";
        $sql .= "b.hr_approval_by, b.supervisor_approval, b.supervisor_approval_by, b.admin_remarks, b.hr_remarks, ";
        $sql .= "b.supervisor_remarks, b.emp_remarks, c.member_first_name emp_first_name,c.member_middle_name emp_middle_name,c.member_last_name emp_last_name, ";
        $sql .= "d.member_first_name admin_first_name, d.member_middle_name admin_middle_name,d.member_last_name admin_last_name, ";
        $sql .= "e.member_first_name hr_first_name, e.member_middle_name hr_middle_name, e.member_last_name hr_last_name, ";
        $sql .= "f.member_first_name supervisor_first_name, f.member_middle_name supervisor_middle_name, f.member_last_name supervisor_last_name, ";
        $sql .= "i.member_first_name supervisor_approval_first_name, i.member_middle_name supervisor_approval_middle_name, i.member_last_name supervisor_approval_last_name ";
        $sql .= "FROM " . $this->getTableName("task_master") . " a INNER JOIN " . $this->getTableName("task_details") . " b ON a.task_master_id=b.task_master_id ";
        $sql .= "INNER JOIN " . $this->getTableName("member_profile") . " c ON c.username=b.create_user LEFT JOIN " . $this->getTableName("member_profile") . " d ";
        $sql .= "ON d.username=b.admin_approval_by LEFT JOIN " . $this->getTableName("member_profile") . " e ON e.username=b.hr_approval_by LEFT JOIN ";
        $sql .= $this->getTableName("member_profile") . " f ON f.username=b.checked_by INNER JOIN " . $this->getTableName("project_list") . " g ";
        $sql .= "ON a.project_id=g.project_id INNER JOIN " . $this->getTableName("mas_category") . " h ON a.category_id=h.category_id LEFT JOIN ";
        $sql .= $this->getTableName("member_profile") . " i ON i.username= b.supervisor_approval_by WHERE b.task_detail_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $task_detail_id);
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

    /*     * ******************************* SELECTED TASK DETAILS ******************************** */


    /*     * ******************************* TASK DETAILS REMARKS UPDATE ******************************** */

    public function updateTaskDetailsRemarks($datas) {
        $taskDetailSQL = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $action_by = $dataVal->LoginUser;
            $task_detail_id = $dataVal->TaskDetailID;
            $action = $dataVal->Action;
            $remarks = $dataVal->Remarks;
            $user_role = $dataVal->UserRoleID;
        }

        if ($user_role == 2) {
            $taskDetailSQL .= "UPDATE " . $this->getTableName("task_details") . " SET supervisor_approval=?, supervisor_approval_by=?, ";
            $taskDetailSQL .= "supervisor_remarks=? WHERE task_detail_id=?";
            $stmt = $this->getConnection()->prepare($taskDetailSQL);
            $stmt->bind_param("issi", $action, $action_by, $remarks, $task_detail_id);
            $taskDetailSQL = "";
        } else {
            if ($action == 1) {
                $taskDetailSQL .= "UPDATE " . $this->getTableName("task_details") . " SET supervisor_approval=?, admin_approval_by=?, ";
                $taskDetailSQL .= "admin_approval=?, admin_remarks=? WHERE task_detail_id=?";
                $stmt = $this->getConnection()->prepare($taskDetailSQL);
                $stmt->bind_param("isisi", $action, $action_by, $action, $remarks, $task_detail_id);
                $taskDetailSQL = "";
            } else {
                $taskDetailSQL .= "UPDATE " . $this->getTableName("task_details") . " SET admin_approval_by=?, ";
                $taskDetailSQL .= "admin_approval=?, admin_remarks=? WHERE task_detail_id=?";
                $stmt = $this->getConnection()->prepare($taskDetailSQL);
                $stmt->bind_param("sisi", $action_by, $action, $remarks, $task_detail_id);
                $taskDetailSQL = "";
            }
        }
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* TASK DETAILS REMARKS UPDATE ******************************** */
}
