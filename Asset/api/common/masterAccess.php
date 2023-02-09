<?php

/**
 * Description of masterAccess
 *
 * @author Soumyanjan
 */
class masterAccess {

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

/* * ******************************* MASTER ROLE ******************************** */

class role extends masterAccess {
    /*     * ******************************* MASTER ROLE DETAILS ******************************** */

    public function getRoleDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $status = $dataVal->Status;
            $role_name = $dataVal->RoleName;
            $role_id_from = $dataVal->RoleIDFrom;
            $role_id_to = $dataVal->RoleIDTo;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND role_status=0 ";
            } else {
                $where_clause .= "AND role_status=1 ";
            }
        }
        if ($role_name != "") {
            $where_clause .= "AND UPPER(role_name) LIKE '%" . strtoupper($role_name) . "%'";
        }
        if (($role_id_from != "") && ($role_id_to == "")) {
            $where_clause .= "AND role_id BETWEEN '$role_id_from' AND '$role_id_from' ";
        }
        if (($role_id_to != "") && ($role_id_from == "")) {
            $where_clause .= "AND role_id BETWEEN '$role_id_to' AND '$role_id_to' ";
        }
        if (($role_id_from != "") && ($role_id_to != "")) {
            $where_clause .= "AND role_id BETWEEN '$role_id_from' AND '$role_id_to' ";
        }
        $sql = "SELECT * FROM " . $this->getTableName("mas_role") . " WHERE role_id>-3 " . $where_clause . " ORDER BY role_id";
        $stmt = $this->getConnection()->prepare($sql);
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

    /*     * ******************************* MASTER ROLE DETAILS ******************************** */
}

/* * ******************************* MASTER ROLE ******************************** */



/* * ******************************* MASTER USER ******************************** */

class user extends masterAccess {
    /*     * ******************************* DUPLICATE MOBILE NUMBER ******************************** */

    public function getDupMobile($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $mobileno = $dataVal->mobile_no;
            $hmobileno = $dataVal->hmobile_no;
        }
        $sql = "SELECT * FROM " . $this->getTableName("member_profile") . " WHERE member_mobile=? AND member_mobile!=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ii", $mobileno, $hmobileno);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows);
        return $retData;
    }

    /*     * ******************************* DUPLICATE MOBILE NUMBER ******************************** */

    /*     * ******************************* DUPLICATE EMAIL ID ******************************** */

    public function getDupEmail($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $email = $dataVal->email_id;
            $hemail = $dataVal->hemail_id;
        }
        $sql = "SELECT * FROM " . $this->getTableName("member_profile") . " WHERE member_email=? AND member_email!=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $email, $hemail);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows);
        return $retData;
    }

    /*     * ******************************* DUPLICATE EMAIL ID ******************************** */

    /*     * ******************************* DUPLICATE USERNAME******************************** */

    public function getDupUser($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $username = $dataVal->username;
        }
        $sql = "SELECT * FROM " . $this->getTableName("member_profile") . " WHERE username=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows);
        return $retData;
    }

    /*     * ******************************* DUPLICATE USERNAME ******************************** */


    /*     * ******************************* DUPLICATE EMPLOYEE CODE ******************************** */

    public function getDupEmpCode($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $emp_code = $dataVal->emp_code;
            $hemp_code = $dataVal->hemp_code;
        }
        $sql = "SELECT * FROM " . $this->getTableName("member_profile") . " WHERE emp_code=? AND emp_code!=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $emp_code, $hemp_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows);
        return $retData;
    }

    /*     * ******************************* DUPLICATE EMPLOYEE CODE ******************************** */




    /*     * ******************************* PASSWORD CHECK ******************************** */

    public function passwordCheck($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $username = $dataVal->username;
            $password = $dataVal->old_pass;
        }
        $sql = "SELECT * FROM " . $this->getTableName("member_login_access") . " WHERE username=? AND password=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $stmt->free_result();
        $retData[] = array("Record" => $num_rows);
        return $retData;
    }

    /*     * ******************************* PASSWORD CHECK ******************************** */


    /*     * ******************************* MASTER USER INSERT ******************************** */

    public function createUser($datas) {
        $member_profileSQL = "";
        $member_login_accessSQL = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $create_under = $dataVal->CreateUnder;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $first_name = $dataVal->FirstName;
            $middle_name = $dataVal->MiddleName == "" ? null : $dataVal->MiddleName;
            $last_name = $dataVal->LastName;
            $address = $dataVal->Address == "" ? null : $dataVal->Address;
            $user_name = $dataVal->Username;
            $role = $dataVal->Role;
            $email = $dataVal->Email == "" ? null : $dataVal->Email;
            $mobile = $dataVal->Mobile == "" ? null : $dataVal->Mobile;
            $password = $dataVal->Password;
            $employee_code = $dataVal->EmployeeCode;
            $member_type = $dataVal->MemberType;
            $category = $dataVal->Category == "" ? null : $dataVal->Category;
        }
        $status = 1;
        $user_is_pass_change = 0;
        $user_previliges = "0";
        $token_value = null;
        $member_profileSQL = "INSERT INTO " . $this->getTableName("member_profile") . " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt_mr = $this->getConnection()->prepare($member_profileSQL);
        $stmt_mr->bind_param("issssssisssssss", $id, $employee_code, $user_name, $first_name, $middle_name, $last_name, $email, $mobile, $address, $category, $member_type, $create_user, $datetime, $create_user, $datetime);
        $retVal_mr = $stmt_mr->execute();

        $member_login_accessSQL = "INSERT INTO " . $this->getTableName("member_login_access") . " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt_mla = $this->getConnection()->prepare($member_login_accessSQL);
        $stmt_mla->bind_param("issiississsss", $id, $user_name, $password, $role, $user_is_pass_change, $user_previliges, $token_value, $status, $create_under, $create_user, $datetime, $create_user, $datetime);
        $retVal_mla = $stmt_mla->execute();

        if (($retVal_mr == true) && ($retVal_mla == true)) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER USER INSERT ******************************** */

    /*     * ******************************* MASTER USER AVAILABILITY ******************************** */

    public function availUser($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $user_name = $dataVal->Username;
            $status = $dataVal->Status;
        }
        $sql = "UPDATE " . $this->getTableName("member_login_access") . " SET user_status=?, modify_user=?, modify_date=? WHERE username=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isss", $status, $modify_user, $datetime, $user_name);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER USER AVAILABILITY ******************************** */


    /*     * ******************************* MASTER USER PREVILIGES ******************************** */

    public function setUserRules($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $user_name = $dataVal->Username;
            $rule_previlige = $dataVal->RulePrevilige;
        }
        $sql .= "UPDATE " . $this->getTableName("member_login_access") . " SET user_previliges=?, ";
        $sql .= "modify_user=?, modify_date=? WHERE username=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ssss", $rule_previlige, $modify_user, $datetime, $user_name);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER USER PREVILIGES ******************************** */

    /*     * ******************************* MASTER USER PASSWORD CHANGE ******************************** */

    public function passwordChange($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $login_user = $dataVal->Loginuser;
            $password = $dataVal->NewPass;
        }
        $user_is_pass_change = 1;
        $sql .= "UPDATE " . $this->getTableName("member_login_access") . " SET password=?, user_is_pass_change=? ";
        $sql .= "WHERE username=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sis", $password, $user_is_pass_change, $login_user);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER USER PASSWORD CHANGE ******************************** */

    /*     * ******************************* MASTER USER UPDATE ******************************** */

    public function updateUser($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_user = $dataVal->Updateuser;
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Modifyuser;
            $first_name = $dataVal->FirstName;
            $middle_name = $dataVal->MiddleName == "" ? null : $dataVal->MiddleName;
            $last_name = $dataVal->LastName;
            $address = $dataVal->Address == "" ? null : $dataVal->Address;
            $role = $dataVal->Role;
            $employee_code = $dataVal->EmployeeCode;
            $member_type = $dataVal->MemberType;
            $member_category = $dataVal->Category == "" ? null : $dataVal->Category;
            $email = $dataVal->Email == "" ? null : $dataVal->Email;
            $mobile = $dataVal->Mobile == "" ? null : $dataVal->Mobile;
        }
        $sql .= "UPDATE " . $this->getTableName("member_profile") . " a INNER JOIN " . $this->getTableName("member_login_access") . " b ";
        $sql .= "ON a.username=b.username SET a.member_first_name=?, a.member_middle_name=?, a.member_last_name=?, a.member_email=?, a.member_category=?, ";
        $sql .= "a.member_mobile=?, a.member_address=?, b.user_role=?, a.emp_code=?, a.member_type=?, a.modify_user=?, a.modify_date=?, ";
        $sql .= "b.modify_user=?, b.modify_date=? WHERE a.username=? AND b.username=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ssssiisissssssss", $first_name, $middle_name, $last_name, $email, $member_category, $mobile, $address, $role, $employee_code, $member_type, $modify_user, $datetime, $modify_user, $datetime, $update_user, $update_user);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER USER UPDATE ******************************** */

    /*     * ******************************* MASTER USER DETAILS ******************************** */

    public function getUserDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
            $first_name = $dataVal->FirstName;
            $mobile = $dataVal->Mobile;
            $email = $dataVal->Email;
            $role_id_from = $dataVal->RoleIDFrom;
            $role_id_to = $dataVal->RoleIDTo;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND a.user_status=0 ";
            } else {
                $where_clause .= "AND a.user_status=1 ";
            }
        }
        if ($create_under != "") {
            $where_clause .= "AND a.create_under='$create_under' ";
        }
        if ($first_name != "") {
            $where_clause .= "AND UPPER(b.member_first_name) LIKE '%" . strtoupper($first_name) . "%' ";
        }
        if ($email != "") {
            $where_clause .= "AND UPPER(b.member_email) LIKE '%" . strtoupper($email) . "%' ";
        }
        if ($mobile != "") {
            $where_clause .= "AND b.member_mobile='$mobile' ";
        }
        if (($role_id_from != "") && ($role_id_to == "")) {
            $where_clause .= "AND c.role_id BETWEEN '$role_id_from' AND '$role_id_from' ";
        }
        if (($role_id_to != "") && ($role_id_from == "")) {
            $where_clause .= "AND c.role_id BETWEEN '$role_id_to' AND '$role_id_to' ";
        }
        if (($role_id_from != "") && ($role_id_to != "")) {
            $where_clause .= "AND c.role_id BETWEEN '$role_id_from' AND '$role_id_to' ";
        }
        $sql .= "SELECT a.member_id, a.username, a.password, b.emp_code, b.member_type, b.member_first_name, b.member_middle_name, b.member_last_name, b.member_mobile, b.member_address, ";
        $sql .= "b.member_email, c.role_name, a.user_previliges, c.role_id, a.user_status, b.create_user, b.modify_user, b.member_category, d.category_name, b.create_date, b.modify_date FROM " . $this->getTableName("member_login_access") . " ";
        $sql .= "a INNER JOIN " . $this->getTableName("member_profile") . " b ON a.username=b.username INNER JOIN " . $this->getTableName("mas_role") . " ";
        $sql .= "c ON a.user_role=c.role_id LEFT JOIN " . $this->getTableName("mas_emp_category") . " d ON b.member_category=d.category_id WHERE a.member_id>0 " . $where_clause . "ORDER BY b.member_first_name, b.member_middle_name, b.member_last_name";
        $stmt = $this->getConnection()->prepare($sql);
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


    /*     * ******************************* MASTER SELECTED USER ******************************** */

    public function getSelectedUserDetails($datas) {
        $sql ="";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $username = $dataVal->Username;
        }
        $sql .= "SELECT a.member_id, a.username, a.password, b.emp_code, b.member_type, b.member_first_name, b.member_middle_name, b.member_last_name, ";
        $sql .= "b.member_mobile, b.member_address, b.member_email, c.role_name, a.user_previliges, c.role_id, a.user_status, ";
        $sql .= "b.create_user, b.modify_user, b.create_date, b.modify_date, b.member_category, d.category_name FROM ";
        $sql .= $this->getTableName("member_login_access") . " a INNER JOIN " . $this->getTableName("member_profile") . " b ON a.username=b.username INNER JOIN ";
        $sql .= $this->getTableName("mas_role") . " c ON a.user_role=c.role_id LEFT JOIN " . $this->getTableName("mas_emp_category") . " d ";
        $sql .= "ON b.member_category=d.category_id WHERE a.username=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("s", $username);
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

    /*     * ******************************* MASTER SELECTED USER ******************************** */
}

/* * ******************************* MASTER USER ******************************** */

/* * ******************************* MASTER RULE ******************************** */

class rule extends masterAccess {
    /*     * ******************************* MASTER RULE INSERT ******************************** */

    public function createRule($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $rule_name = $dataVal->RuleName;
        }
        $sql .= "INSERT INTO " . $this->getTableName("mas_rule") . "(rule_name,create_user,create_date,modify_user,";
        $sql .= "modify_date) VALUES(?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sssss", $rule_name, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER RULE INSERT ******************************** */

    /*     * ******************************* MASTER RULE UPDATE ******************************** */

    public function updateRule($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_rule_id = $dataVal->UpdateruleID;
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Modifyuser;
            $rule_name = $dataVal->RuleName;
        }
        $sql .= "UPDATE " . $this->getTableName("mas_rule") . " SET rule_name=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE rule_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sssi", $rule_name, $modify_user, $datetime, $update_rule_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER RULE UPDATE ******************************** */

    /*     * ******************************* MASTER RULE DETAILS ******************************** */

    public function getRuleDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $rule_name = $dataVal->RuleName;
        }
        if ($rule_name != "") {
            $where_clause .= "AND UPPER(rule_name) LIKE '%" . strtoupper($rule_name) . "%' ";
        }

        $sql = "SELECT * FROM " . $this->getTableName("mas_rule") . " WHERE rule_id>0 " . $where_clause . " ORDER BY rule_name";
        $stmt = $this->getConnection()->prepare($sql);
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

    /*     * ******************************* MASTER RULE DETAILS ******************************** */

    /*     * ******************************* MASTER SELECTED RULE DETAILS ******************************** */

    public function getSelectedRuleDetails($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $rule_id = $dataVal->RuleId;
        }
        $sql = "";
        $sql = "SELECT * FROM " . $this->getTableName("mas_rule") . " WHERE rule_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $rule_id);
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

    /*     * ******************************* MASTER SELECTED RULE DETAILS ******************************** */
}

/* * ******************************* MASTER RULE ******************************** */

/* * ******************************* MASTER CATEGORY ******************************** */

class category extends masterAccess {
    /*     * ******************************* MASTER CATEGORY INSERT ******************************** */

    public function createCategory($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $category_name = $dataVal->CategoryName;
        }
        $status = 1;
        $sql .= "INSERT INTO " . $this->getTableName("mas_category") . " VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isissss", $id, $category_name, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER CATEGORY INSERT ******************************** */
    /*     * ******************************* MASTER CATEGORY AVAILABILITY ******************************** */

    public function availCategory($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $category_id = $dataVal->Category_id;
            $status = $dataVal->Status;
        }
        $sql .= "UPDATE " . $this->getTableName("mas_category") . " SET category_status=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE category_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("issi", $status, $modify_user, $datetime, $category_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER CATEGORY AVAILABILITY ******************************** */

    /*     * ******************************* MASTER CATEGORY UPDATE ******************************** */

    public function updateCategory($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_category_id = $dataVal->CategoryID;
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $category_name = $dataVal->CategoryName;
        }
        $sql .= "UPDATE " . $this->getTableName("mas_category") . " SET category_name=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE category_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sssi", $category_name, $modify_user, $datetime, $update_category_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER CATEGORY UPDATE ******************************** */

    /*     * ******************************* MASTER CATEGORY DETAILS ******************************** */

    public function getCategoryDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND category_status=0 ";
            } else {
                $where_clause .= "AND category_status=1 ";
            }
        }

        $sql .= "SELECT * FROM " . $this->getTableName("mas_category") . " a INNER JOIN " . $this->getTableName("member_login_access") . " b ";
        $sql .= "ON a.create_user=b.username WHERE b.create_under=? " . $where_clause . " ORDER BY a.category_name";
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

    /*     * ******************************* MASTER CATEGORY DETAILS ******************************** */

    /*     * ******************************* MASTER SELECTED CATEGORY DETAILS ******************************** */

    public function getSelectedCategoryDetails($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $category_id = $dataVal->CategoryId;
        }
        $sql = "";
        $sql = "SELECT * FROM " . $this->getTableName("mas_category") . " WHERE category_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $category_id);
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

    /*     * ******************************* MASTER SELECTED CATEGORY DETAILS ******************************** */
}

/* * ******************************* MASTER CATEGORY ******************************** */

/* * ******************************* MASTER EMPLOYEE CATEGORY ******************************** */

class empcategory extends masterAccess {
    /*     * ******************************* MASTER CATEGORY INSERT ******************************** */

    public function createCategory($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $category_name = $dataVal->CategoryName;
        }
        $status = 1;
        $sql .= "INSERT INTO " . $this->getTableName("mas_emp_category") . " VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isissss", $id, $category_name, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER CATEGORY INSERT ******************************** */
    /*     * ******************************* MASTER CATEGORY AVAILABILITY ******************************** */

    public function availCategory($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $category_id = $dataVal->Category_id;
            $status = $dataVal->Status;
        }
        $sql .= "UPDATE " . $this->getTableName("mas_emp_category") . " SET category_status=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE category_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("issi", $status, $modify_user, $datetime, $category_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER CATEGORY AVAILABILITY ******************************** */

    /*     * ******************************* MASTER CATEGORY UPDATE ******************************** */

    public function updateCategory($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_category_id = $dataVal->CategoryID;
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $category_name = $dataVal->CategoryName;
        }
        $sql .= "UPDATE " . $this->getTableName("mas_emp_category") . " SET category_name=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE category_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("sssi", $category_name, $modify_user, $datetime, $update_category_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER CATEGORY UPDATE ******************************** */

    /*     * ******************************* MASTER CATEGORY DETAILS ******************************** */

    public function getCategoryDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND a.category_status=0 ";
            } else {
                $where_clause .= "AND a.category_status=1 ";
            }
        }

        $sql .= "SELECT * FROM " . $this->getTableName("mas_emp_category") . " a INNER JOIN " . $this->getTableName("member_login_access") . " b ";
        $sql .= "ON a.create_user=b.username WHERE b.create_under=? " . $where_clause . " ORDER BY a.category_name";
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

    /*     * ******************************* MASTER CATEGORY DETAILS ******************************** */

    /*     * ******************************* MASTER SELECTED CATEGORY DETAILS ******************************** */

    public function getSelectedCategoryDetails($datas) {
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $category_id = $dataVal->CategoryId;
        }
        $sql = "";
        $sql = "SELECT * FROM " . $this->getTableName("mas_emp_category") . " WHERE category_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $category_id);
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

    /*     * ******************************* MASTER SELECTED CATEGORY DETAILS ******************************** */
}

/* * ******************************* MASTER EMPLOYEE CATEGORY ******************************** */

/* * ******************************* MASTER MEDIA ******************************** */

class media extends masterAccess {
    /*     * ******************************* MASTER MEDIA INSERT ******************************** */

    public function createMedia($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $media_path = $dataVal->MediaPath;
            $media_extension = $dataVal->MediaExtension;
        }
        $status = 1;
        $sql = "INSERT INTO " . $this->getTableName("mas_media") . " VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ississss", $id, $media_path, $media_extension, $status, $create_user, $datetime, $create_user, $datetime);
        $retVal = $stmt->execute();

        if ($retVal == true) {
            $retValue = 1;
        } else {
            $retValue = 0;
        }
        return $retValue;
    }

    /*     * ******************************* MASTER MEDIA INSERT ******************************** */

    /*     * ******************************* MASTER MEDIA DELETE ******************************** */

    public function deleteMedia($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_media_id = $dataVal->DeleteMediaID;
        }
        $sql = "DELETE FROM " . $this->getTableName("mas_media") . " WHERE media_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $update_media_id);
        $retVal = $stmt->execute();

        if ($retVal == true) {
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
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
        }
        $sql .= "SELECT a.media_id, b.member_first_name, b.member_middle_name, b.member_last_name, a.media_extension, a.media_path FROM ";
        $sql .= $this->getTableName("mas_media") . " a INNER JOIN " . $this->getTableName("member_profile") . " b INNER JOIN " . $this->getTableName("member_login_access") . " c ";
        $sql .= "ON c.username=b.username WHERE a.create_user=b.username AND c.create_under=?";
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

    /*     * ******************************* MASTER MEDIA DETAILS ******************************** */
}

/* * ******************************* MASTER MEDIA ******************************** */


/* * ******************************* MASTER DASHBOARD ******************************** */

class search extends masterAccess {
    /*     * ******************************* SEARCH PROFILE DETAILS ******************************** */

    public function getProfile($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $emp_type = $dataVal->EmpType;
            $login_user = $dataVal->LoginUser;
            $curr_date = $dataVal->CurrentDate;
            $user_role = $dataVal->UserRole;
        }
        if ($user_role > 1) {
            if ($user_role == 2) {
                if ($emp_type == "Employee") {
                    $where_clause = "";
                } else {
                    $where_clause = "WHERE b.project_supervisor='$login_user' ";
                }
            } else {
                $where_clause = "WHERE a.username='$login_user' ";
            }
        } else {
            $where_clause = "";
        }

        if ($emp_type == "Employee") {
            $sql .= "SELECT a.username, a.member_first_name, a.member_middle_name, a.member_last_name, a.member_email, a.member_mobile, a.member_address, ";
            $sql .= "b.gatepass_start_date, b.gatepass_end_date, COUNT(b.project_id) total_project, COUNT(CASE WHEN b.gatepass_status = 0  THEN 1 END) AS completed_project, ";
            $sql .= "COUNT(CASE WHEN b.gatepass_status = 1 AND b.gatepass_start_date<=? THEN 1 END) AS ongoing_project, COUNT(CASE WHEN b.gatepass_status = 1 ";
            $sql .= "AND b.gatepass_start_date>? THEN 1 END) AS upcoming_project FROM " . $this->getTableName("member_profile") . " a ";
            $sql .= "LEFT JOIN " . $this->getTableName("gatepass") . " b ON a.username=b.username " . $where_clause . " GROUP BY a.username, ";
            $sql .= "a.member_first_name, a.member_middle_name, a.member_last_name, a.member_email, a.member_mobile, a.member_address";
        } else {
            $sql .= "SELECT a.username, a.member_first_name, a.member_middle_name, a.member_last_name, a.member_email, a.member_mobile, a.member_address, b.project_supervisor_start_date, ";
            $sql .= "b.project_supervisor_end_date, COUNT(b.project_id) total_project, COUNT(CASE WHEN b.project_info_status = 0  THEN 1 END ) AS completed_project, ";
            $sql .= "COUNT(CASE WHEN b.project_info_status = 1 AND b.project_supervisor_start_date<=? THEN 1 END ) AS ongoing_project, COUNT(CASE WHEN b.project_info_status = 1 ";
            $sql .= "AND b.project_supervisor_start_date>? THEN 1 END ) AS upcoming_project FROM " . $this->getTableName("member_profile") . " a ";
            $sql .= "LEFT JOIN " . $this->getTableName("project_info") . " b ON a.username=b.project_supervisor " . $where_clause . " GROUP BY a.username, ";
            $sql .= "a.member_first_name, a.member_middle_name, a.member_last_name, a.member_email, a.member_mobile, a.member_address";
        }
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $curr_date, $curr_date);
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

    /*     * ******************************* SEARCH PROFILE DETAILS ******************************** */

    /*     * ******************************* SEARCH ONGOING PROJECT DETAILS ******************************** */

    public function getOngoingProjectDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $emp_sup = $dataVal->Employee_Supervisor;
            $curr_date = $dataVal->CurrentDate;
            $login_user = $dataVal->LoginUser;
            $user_role = $dataVal->UserRole;
            $create_under = $dataVal->CreateUnder;
        }
        $status = 1;
        if ($user_role < -1) {
            $where_clause .= "AND d.user_role>'$user_role' ";
        } else {
            $where_clause .= "AND d.user_role>='$user_role' ";
        }
        if ($user_role > 1) {
            if ($user_role == 2) {
                if ($emp_sup == "Employee") {
                    $where_clause .= "";
                } else {
                    $where_clause .= "AND b.project_supervisor='$login_user' ";
                }
            } else {
                $where_clause .= "AND b.username='$login_user' ";
            }
        } else {
            $where_clause .= "";
        }
        if ($create_under != "") {
            $where_clause .= "AND d.create_under='$create_under'";
        }
        if ($emp_sup == "Employee") {
            $sql .= "SELECT a.project_id, a.project_name, a.project_location, c.member_first_name, c.member_last_name, c.member_middle_name, DATEDIFF(b.gatepass_end_date, ?) age FROM ";
            $sql .= $this->getTableName("project_list") . " a INNER JOIN " . $this->getTableName("gatepass") . " b ON a.project_id=b.project_id ";
            $sql .= "INNER JOIN " . $this->getTableName("member_profile") . " c ON c.username=b.username INNER JOIN " . $this->getTableName("member_login_access") . " d ";
            $sql .= "ON d.username=c.username WHERE a.project_status=? AND b.gatepass_status=? " . $where_clause . " ORDER BY b.gatepass_end_date DESC";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bind_param("sii", $curr_date, $status, $status);
        } else {
            $sql .= "SELECT a.project_id, a.project_name, a.project_location, c.member_first_name, c.member_last_name, c.member_middle_name, a.project_start_date, ";
            $sql .= "a.project_end_date, b.project_supervisor_start_date, b.project_supervisor_end_date, DATEDIFF(b.project_supervisor_end_date, ?) age FROM " . $this->getTableName("project_list") . " a ";
            $sql .= "INNER JOIN " . $this->getTableName("project_info") . " b ON a.project_id=b.project_id INNER JOIN " . $this->getTableName("member_profile") . " c ";
            $sql .= "ON b.project_supervisor=c.username INNER JOIN " . $this->getTableName("member_login_access") . " d ON d.username=c.username ";
            $sql .= "WHERE a.project_status=? AND b.project_info_status=? " . $where_clause . " ORDER BY b.project_supervisor_end_date DESC";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bind_param("sii", $curr_date, $status, $status);
        }

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

    /*     * ******************************* SEARCH ONGOING PROJECT DETAILS ******************************** */

    /*     * ******************************* SEARCH EMPLOYEE AND SUPERVISOR STATUS ******************************** */

    public function getEmployeeSupervisorStatus($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $emp_sup = $dataVal->Employee_Supervisor;
            $curr_date = $dataVal->CurrentDate;
            $login_user = $dataVal->LoginUser;
            $user_role = $dataVal->UserRole;
            $create_under = $dataVal->CreateUnder;
        }
        $project_status = 1;
        $gatepass_status = 3;
        if ($user_role < -1) {
            $where_clause .= "AND d.user_role>'$user_role' ";
        } else {
            $where_clause .= "AND d.user_role>='$user_role' ";
        }
        if ($user_role > 1) {
            if ($user_role == 2) {
                if ($emp_sup == "Employee") {
                    $where_clause .= "";
                } else {
                    $where_clause .= "AND b.project_supervisor='$login_user' ";
                }
            } else {
                $where_clause .= "AND b.username='$login_user' ";
            }
        } else {
            
        }
        if ($create_under != "") {
            $where_clause .= "AND d.create_under='$create_under' ";
        }
        if ($emp_sup == "Employee") {
            $sql .= "SELECT a.project_id, a.project_name, a.project_location, c.member_first_name, c.member_last_name, c.member_middle_name, a.project_start_date, ";
            $sql .= "a.project_end_date, b.gatepass_start_date, b.gatepass_end_date, DATEDIFF(b.gatepass_end_date, ?) age, b.gatepass_status FROM " . $this->getTableName("project_list") . " a ";
            $sql .= "INNER JOIN " . $this->getTableName("gatepass") . " b ON a.project_id=b.project_id INNER JOIN " . $this->getTableName("member_profile") . " c ";
            $sql .= "ON b.username=c.username INNER JOIN " . $this->getTableName("member_login_access") . " d ON c.username=d.username WHERE a.project_status=? AND b.gatepass_status!=? " . $where_clause . " ";
            $sql .= "GROUP BY a.project_id, a.project_name, a.project_location, c.member_first_name, c.member_last_name, c.member_middle_name, a.project_start_date, ";
            $sql .= "a.project_end_date, b.gatepass_start_date, b.gatepass_end_date ORDER BY b.gatepass_end_date DESC";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bind_param("sii", $curr_date, $project_status, $gatepass_status);
        } else {
            $sql .= "SELECT a.project_id, a.project_name, a.project_location, c.member_first_name, c.member_last_name, c.member_middle_name, a.project_start_date, ";
            $sql .= "a.project_end_date, b.project_supervisor_start_date, b.project_supervisor_end_date, DATEDIFF(b.project_supervisor_end_date, ?) age FROM " . $this->getTableName("project_list") . " a ";
            $sql .= "INNER JOIN " . $this->getTableName("project_info") . " b ON a.project_id=b.project_id INNER JOIN " . $this->getTableName("member_profile") . " c ";
            $sql .= "ON b.project_supervisor=c.username INNER JOIN " . $this->getTableName("member_login_access") . " d ON c.username=d.username WHERE a.project_status=? " . $where_clause . " ";
            $sql .= "ORDER BY b.project_supervisor_end_date DESC";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bind_param("si", $curr_date, $project_status);
        }
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

    /*     * ******************************* SEARCH EMPLOYEE AND SUPERVISOR STATUS  ******************************** */

    /*     * ******************************* SEARCH PROJECT DETAILS ******************************** */

    public function getProjectStatus($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $curr_date = $dataVal->CurrentDate;
            $create_under = $dataVal->CreateUnder;
            $user_role = $dataVal->UserRole;
        }
        if ($user_role < -1) {
            $where_clause .= "WHERE b.user_role>'$user_role' ";
        } else {
            $where_clause .= "WHERE b.user_role>='$user_role' ";
        }
        if ($create_under != "") {
            $where_clause .= "AND b.create_under='$create_under'";
        }
        $sql .= "SELECT project_id, project_name, project_location, project_start_date, project_start_date, project_end_date, ";
        $sql .= "DATEDIFF(project_end_date, ?) age, project_status FROM " . $this->getTableName("project_list") . " a INNER JOIN ";
        $sql .= $this->getTableName("member_login_access") . " b ON a.create_user=b.username " . $where_clause . " ORDER BY project_end_date DESC";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("s", $curr_date);
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

    /*     * ******************************* USER COUNT ******************************** */

    public function getUserCount($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $user_role = $dataVal->UserRole;
        }
        if ($user_role < -1) {
            $where_clause .= "WHERE a.user_role>='$user_role' ";
        } else {
            $where_clause .= "WHERE a.user_role>'$user_role' ";
        }
        if ($create_under != "") {
            $where_clause .= "AND a.create_under='$create_under'";
        }
        $sql .= "SELECT a.user_role, b.role_name, COUNT(*) record FROM " . $this->getTableName("member_login_access") . " a INNER ";
        $sql .= "JOIN " . $this->getTableName("mas_role") . " b ON a.user_role=b.role_id " . $where_clause . " GROUP BY a.user_role, b.role_name ";
        $sql .= "ORDER BY a.user_role";
        $stmt = $this->getConnection()->prepare($sql);
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

    /*     * ******************************* PROJECT COUNT ******************************** */

    public function getProjectCount($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $curr_date = $dataVal->CurrentDate;
            $create_under = $dataVal->CreateUnder;
            $user_role = $dataVal->UserRole;
        }
        if ($user_role < -1) {
            $where_clause .= "WHERE b.user_role>'$user_role' ";
        } else {
            $where_clause .= "WHERE b.user_role>='$user_role' ";
        }
        if ($create_under != "") {
            $where_clause .= "AND b.create_under='$create_under'";
        }

        $sql .= "SELECT COUNT(*) total_project, COUNT(CASE WHEN project_status = 0  THEN 1 END) AS completed_project,";
        $sql .= "COUNT(CASE WHEN project_status = 1 AND project_start_date<=? THEN 1 END) AS ongoing_project, ";
        $sql .= "COUNT(CASE WHEN project_status = 1 AND project_start_date>? THEN 1 END) AS upcoming_project ";
        $sql .= "FROM " . $this->getTableName("project_list") . " a INNER JOIN " . $this->getTableName("member_login_access") . " b ";
        $sql .= "ON a.create_user=b.username " . $where_clause;

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("ss", $curr_date, $curr_date);
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
}

/* * ******************************* MASTER DASHBOARD ******************************** */