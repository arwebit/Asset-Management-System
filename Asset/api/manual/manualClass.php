<?php

/**
 * Description of manualClass
 *
 * @author Soumyanjan
 */
class manualClass {

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

class manual extends manualClass {
    /*     * ******************************* PROJECT INSERT ******************************** */

    public function createManual($datas) {
        $manualSQL = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $id = $dataVal->Slno;
            $datetime = $dataVal->Current_date;
            $create_user = $dataVal->Createuser;
            $project_id = $dataVal->ProjectID;
            $manual_title = $dataVal->ManualTitle;
            $manual_descr = $dataVal->ManualDescr;
        }
        $status = 1;

        $manualSQL = "INSERT INTO " . $this->getTableName("manual") . " VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getConnection()->prepare($manualSQL);
        $stmt->bind_param("iississss", $id, $project_id, $manual_title, $manual_descr, $status, $create_user, $datetime, $create_user, $datetime);
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

    public function availManual($datas) {
        $sql = "";

        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Createuser;
            $manual_id = $dataVal->Manual_id;
            $status = $dataVal->Status;
        }
        $sql .= "UPDATE " . $this->getTableName("manual") . " SET manual_status=?, modify_user=?, modify_date=? ";
        $sql .= "WHERE manual_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("isss", $status, $modify_user, $datetime, $manual_id);
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

    public function updateManual($datas) {
        $sql = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $update_manual_id = $dataVal->Updatemanualid;
            $datetime = $dataVal->Current_date;
            $modify_user = $dataVal->Modifyuser;
            $project_id = $dataVal->ProjectID;
            $manual_title = $dataVal->ManualTitle;
            $manual_descr = $dataVal->ManualDescr;
        }
        $sql .= "UPDATE " . $this->getTableName("manual") . " SET project_id=?, manual_title=?, manual_descr=?, ";
        $sql .= "modify_user=?, modify_date=? WHERE manual_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("issssi", $project_id, $manual_title,$manual_descr, $modify_user, $datetime, $update_manual_id);
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

    public function getManualDetails($datas) {
        $sql = "";
        $where_clause = "";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $create_under = $dataVal->CreateUnder;
            $status = $dataVal->Status;
            $project_id = $dataVal->ProjectID;
            $manual_title = $dataVal->ManualTitle;
        }
        if ($status != "") {
            if ($status == "Inactive") {
                $where_clause .= "AND a.manual_status=0 ";
            } else {
                $where_clause .= "AND a.manual_status=1 ";
            }
        }
        if ($project_id != "") {
            $where_clause .= "AND UPPER(a.project_id) ='" . $project_id . "' ";
        }
        if ($manual_title != "") {
            $where_clause .= "AND UPPER(a.manual_title) LIKE '%" . strtoupper($manual_title) . "%' ";
        }

        $sql .= "SELECT * FROM " . $this->getTableName("manual") . " a INNER JOIN ". $this->getTableName("project_list"). " b ";
        $sql .= "ON a.project_id=b.project_id INNER JOIN ". $this->getTableName("member_login_access") ." c ON c.username=a.create_user ";
        $sql .= "WHERE c.create_under=? " . $where_clause." ORDER BY a.manual_title";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("s",$create_under);
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

    public function getSelectedManualDetails($datas) {
        $sql="";
        $getjsonData = json_decode($datas);
        foreach ($getjsonData as $dataVal) {
            $manual_id = $dataVal->ManualID;
        }
        $sql .= "SELECT a.manual_id, a.project_id, b.project_name, a.manual_title, a.manual_descr, a.manual_status ";
        $sql .= "FROM " . $this->getTableName("manual") . " a INNER JOIN " . $this->getTableName("project_list") ." ";
        $sql .= "b ON a.project_id=b.project_id WHERE a.manual_id=?";
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bind_param("i", $manual_id);
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





