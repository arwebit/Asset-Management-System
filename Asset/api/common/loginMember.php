<?php

/**
 * Description of loginMember
 *
 * @author Soumyanjan
 */
class loginMember {

    protected $connection = null;
    protected $tbl_member_login_access = "";
    protected $tbl_member_registration = "";
    protected $tbl_mas_role = "";
    protected $tbl_gatepass = "";
    protected $username = "";
    protected $password = "";
    protected $role_id = "";
    protected $token = "";

    /*     * ******************************* CONNECTION CREATED ******************************** */

    public function __construct($host_ip, $user, $pass, $db) {
        $this->host = $host_ip;
        $this->username = $user;
        $this->password = $pass;
        $this->database = $db;
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        $this->tbl_member_login_access = "member_login_access";
        $this->tbl_member_registration = "member_profile";
        $this->tbl_mas_role = "mas_role";
        $this->tbl_gatepass = "gatepass";
        $this->tbl_project_info = "project_info";
    }

    /*     * ******************************* CONNECTION CREATED ******************************** */

    /*     * ******************************* LOGIN ACCESS ******************************** */

    public function getLoginAccess($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $this->username = $dataVal->username;
            $this->password = $dataVal->password;
        }
        $sql = "SELECT * FROM " . $this->tbl_member_login_access . " WHERE username=? AND password=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $this->username, $this->password);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows;
    }

    /*     * ******************************* LOGIN ACCESS ******************************** */

    /*     * ******************************* LOGIN ACTIVE ******************************** */

    public function getLoginActive($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $this->username = $dataVal->username;
        }
        $status = 1;
        $sql = "SELECT * FROM " . $this->tbl_member_login_access . " WHERE username=? AND user_status=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $this->username, $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows;
    }

    /*     * ******************************* LOGIN ACTIVE ******************************** */

    /*     * ******************************* LOGIN ACTIVE ******************************** */

    public function getLoginRoleMatch($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $this->username = $dataVal->username;
            $this->role_id = $dataVal->user_role_id;
        }
        $sql = "SELECT * FROM " . $this->tbl_member_login_access . " WHERE username=? AND user_role=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $this->username, $this->role_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        return $num_rows;
    }

    /*     * ******************************* LOGIN ACTIVE ******************************** */

    /*     * ******************************* TOKEN SAVE ******************************** */

    public function saveToken($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $this->username = $dataVal->Username;
            $this->token = $dataVal->TokenVal;
        }
        $sql = "UPDATE " . $this->tbl_member_login_access . " SET login_token_value=? WHERE username=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $this->token, $this->username);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* TOKEN SAVE ******************************** */

    /*     * ******************************* LOGIN TOKEN ******************************** */

    public function getLoginToken($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $this->username = $dataVal->username;
            $this->token = $dataVal->tokenval;
        }
        $sql = "SELECT * FROM " . $this->tbl_member_login_access . " WHERE username=? AND login_token_value=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $this->username, $this->token);
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

    /*     * ******************************* LOGIN TOKEN ******************************** */

    /*     * ******************************* LOGIN DETAILS ******************************** */

    public function getLoginDetails($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $this->username = $dataVal->username;
        }
        $sql = "";
        $sql .= "SELECT a.member_first_name, a.member_middle_name, a.member_last_name, b.user_role role_id, c.role_name, a.create_user, ";
        $sql .= "a.create_date, a.modify_user, a.modify_date FROM " . $this->tbl_member_registration . " a INNER JOIN " . $this->tbl_member_login_access;
        $sql .= " b ON a.username=b.username INNER JOIN " . $this->tbl_mas_role . " c ON b.user_role=c.role_id WHERE a.username=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $this->username);
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

    /*     * ******************************* LOGIN DETAILS ******************************** */

    /*     * ******************************* GATEPASS AND PROJECT ASSIGN INACTIVE ******************************** */

    public function projectAssignGPInactive($datas) {
        $gp_sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $user_name = $dataVal->Username;
            $status = $dataVal->Status;
        }
        $gp_sql .= "UPDATE " . $this->tbl_gatepass . " SET gatepass_status=?, modify_user=?, modify_date=? ";
        $gp_sql .= "WHERE username=? AND gatepass_end_date<?";
        $gp_stmt = $this->connection->prepare($gp_sql);
        $gp_stmt->bind_param("issss", $status, $modify_user, $datetime, $user_name, date("Y-m-d", strtotime($datetime)));
        $gp_retVal = $gp_stmt->execute();

        if ($gp_retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* GATEPASS AND PROJECT ASSIGN INACTIVE ******************************** */


    /*     * ******************************* PROJECT INFO INACTIVE ******************************** */

    public function projectInfoInactive($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $user_name = $dataVal->Username;
            $status = $dataVal->Status;
        }
        $sql .= "UPDATE " . $this->tbl_project_info . " SET project_info_status=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE project_supervisor=? AND project_supervisor_end_date<?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("issss", $status, $modify_user, $datetime, $user_name, date("Y-m-d", strtotime($datetime)));
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* PROJECT INFO INACTIVE ******************************** */
}
