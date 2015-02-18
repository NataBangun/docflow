<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mm_inbox extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     *  process = 1-4 [ draft, edit, review, publication ]
     */

    public function get_inbox($users_id) {
        $sql = "SELECT DISTINCT
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,						
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR,						
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.UDT,
                    DBDOC.H_DOCUMENTS_APPROVAL.PK_DOCUMENTS_APPROVAL_ID,
                    DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER
		FROM
                    DBDOC.H_DOCUMENTS_PROCESS
                    INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID
                    INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.H_DOCUMENTS_APPROVAL ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID = DBDOC.P_CATEGORIES.PK_CATEGORIES_ID
                    INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.T_DOCUMENTS.DOCUMENTS_CBY = DBDOC.V_EMPLOYEE.EMPLOYEE_NO
		WHERE 
                    DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = ?
                    AND
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ?
		ORDER BY DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID DESC";

        $query = $this->db->query($sql, array($users_id, DOC_DRAFT));
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_inbox_monitoring($users_id) {
        $as = "CURRENT_LAYER";
        $step = "STEP_LAYER";
        $sql = "SELECT DISTINCT
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR,
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.UDT,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    Min(DBDOC.H_DOCUMENTS_STEP.STEP_LAYER) AS $step,
                    Max(DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER) AS $as,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS
		FROM
                    DBDOC.H_DOCUMENTS_PROCESS
                    INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID
                    INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.H_DOCUMENTS_APPROVAL ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID = DBDOC.P_CATEGORIES.PK_CATEGORIES_ID
                    INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.T_DOCUMENTS.DOCUMENTS_CBY = DBDOC.V_EMPLOYEE.EMPLOYEE_NO
		WHERE
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = ? AND
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ? AND
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 2
		GROUP BY
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR,
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.UDT,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS
		ORDER BY
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID DESC";

        $query = $this->db->query($sql, array($users_id, DOC_DRAFT));
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_inbox_layer($users_id) {
        $as = "CURRENT_LAYER";
        $step = "STEP_LAYER";
        $sql = "SELECT DISTINCT
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR,
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.UDT,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    Min(DBDOC.H_DOCUMENTS_STEP.STEP_LAYER) AS $step,
                    Max(DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER) AS $as,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS
		FROM
                    DBDOC.H_DOCUMENTS_PROCESS
                    INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID
                    INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.H_DOCUMENTS_APPROVAL ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID = DBDOC.P_CATEGORIES.PK_CATEGORIES_ID
                    INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.T_DOCUMENTS.DOCUMENTS_CBY = DBDOC.V_EMPLOYEE.EMPLOYEE_NO
		WHERE
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = ? AND
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ? AND
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 0
		GROUP BY
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR,
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.UDT,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS
		ORDER BY
                    DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID DESC";

        $query = $this->db->query($sql, array($users_id, DOC_DRAFT));
        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_detail($doc_id, $users_id) {
        $sql = "SELECT
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DESCRIPTION,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CBY,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_UBY,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_UDT,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_ATC_NAME,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_ATC_SYSTEM,
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,						
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR
                FROM
                    DBDOC.H_DOCUMENTS_PROCESS
                    INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    LEFT JOIN DBDOC.H_DOCUMENTS_APPROVAL ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID AND DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO
                    INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.P_CATEGORIES.PK_CATEGORIES_ID = DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID
                    INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.V_EMPLOYEE.EMPLOYEE_NO = DBDOC.T_DOCUMENTS.DOCUMENTS_CBY
                WHERE DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ? AND 
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = ? AND 
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ?";

        $query = $this->db->query($sql, array($doc_id, $users_id, DOC_DRAFT));
        if ($query) {
            $row = $query->row_array();

            if (isset($row['APPROVAL_STATUS']) && $row['APPROVAL_STATUS'] == ACTION_UNREAD) {
                $this->set_to_read($row);
            }

            return $row;
        } else {
            return FALSE;
        }
    }

    public function get_detail_pengesahan($doc_id, $users_id) {
        $sql = "SELECT
                    DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
                    DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DESCRIPTION,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CBY,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_UBY,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_UDT,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_ATC_NAME,
                    DBDOC.T_DOCUMENTS.DOCUMENTS_ATC_SYSTEM,
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
                    DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
                    DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
                    DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,						
                    DBDOC.V_EMPLOYEE.E_MAIL_ADDR
                FROM
                    DBDOC.H_DOCUMENTS_PROCESS
                    INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    LEFT JOIN DBDOC.H_DOCUMENTS_APPROVAL ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID AND DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO
                    INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                    INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.P_CATEGORIES.PK_CATEGORIES_ID = DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID
                    INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.V_EMPLOYEE.EMPLOYEE_NO = DBDOC.T_DOCUMENTS.DOCUMENTS_CBY
                WHERE DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ? AND 
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = ? AND 
                    DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ?";

        $query = $this->db->query($sql, array($doc_id, $users_id, DOC_DRAFT));
        if ($query) {
            $row = $query->row_array();

            if (isset($row['APPROVAL_STATUS']) && $row['APPROVAL_STATUS'] == ACTION_UNREAD) {
                $this->set_to_read($row);
            }

            return $row;
        } else {
            return FALSE;
        }
    }

    private function set_to_read($array) {
        $this->db->set('APPROVAL_STATUS', ACTION_READ);
        $this->db->set('APPROVAL_UDT', date('Y-m-d H:i:s'));
        $this->db->where('FK_DOCUMENTS_ID', $array['FK_DOCUMENTS_ID']);
        $this->db->where('VERSION_ID', $array['VERSION_ID']);
        $this->db->where('STEP_LAYER', $array['CURRENT_LAYER']);
        $this->db->where('EMPLOYEE_NO', $array['EMPLOYEE_NO']);
        $this->db->where('APPROVAL_STATUS', 0);
        $this->db->update('H_DOCUMENTS_APPROVAL');
    }

    public function check_is_make_approval($array) {

        $sql = "SELECT * FROM DBDOC.H_DOCUMENTS_APPROVAL 
                WHERE 
                    DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ? AND
                    DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER = ? AND
                    DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID = ? AND
                    DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = ? AND 
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS <= " . ACTION_READ . " AND
                    DBDOC.H_DOCUMENTS_APPROVAL.FK_TYPE_ID = 1 ";
        $query = $this->db->query($sql, array($array['FK_DOCUMENTS_ID'], $array['CURRENT_LAYER'], $array['VERSION_ID'], $array['EMPLOYEE_NO']));

        if ($query->num_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insert_comment($array) {
        $time = date('Y-m-d H:i:s');

        $data = array(
            'FK_DOCUMENTS_ID' => $array['dI'],
            'VERSION_ID' => $array['vI'],
            'COMMENTS_DESC' => $array['comment'],
            'COMMENTS_CBY' => $array['uID'],
            'FK_TYPE_ID' => 1,
            'COMMENTS_CDT' => $time,
            'STEP_LAYER' => $array['sL']
        );
        $this->db->insert('H_DOCUMENTS_COMMENTS', $data);
    }

    // Utk melakukan insert data ke H_DOCUMENTS_APPROVAL by dok pro
    public function clone_to_approval($documents_id) {

        $clone = "
            INSERT INTO H_DOCUMENTS_APPROVAL (                
                H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
                H_DOCUMENTS_APPROVAL.VERSION_ID,
                H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
                H_DOCUMENTS_APPROVAL.STEP_LAYER,
                H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
                H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
                H_DOCUMENTS_APPROVAL.FK_TYPE_ID
            )
            SELECT
                FK_DOCUMENTS_ID,
                VERSION_ID,
                EMPLOYEE_NO,
                STEP_LAYER,
                APPROVAL_STATUS,
                APPROVAL_MAILED,
                FK_TYPE_ID
            FROM
            (                
                SELECT 
                    FIRST_VALUE(T1.PK_DOCUMENTS_STEP_ID) OVER (ORDER BY T1.APPROVAL_STATUS) ROW_ANALITIC,
                    T1.* 
                FROM
                (                                                    
                    SELECT   
                        H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID,
                        T_DOCUMENTS.FK_CATEGORIES_ID,
                        P_CATEGORY_PROCESS.PROCESS_TYPE,
                        H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
                        H_DOCUMENTS_PROCESS.VERSION_ID,
                        H_DOCUMENTS_STEP.EMPLOYEE_NO,
                        H_DOCUMENTS_STEP.STEP_LAYER,
                        NVL((SELECT DISTINCT H_DOCUMENTS_APPROVAL.VERSION_ID FROM H_DOCUMENTS_APPROVAL 
                            WHERE H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
                                AND H_DOCUMENTS_APPROVAL.FK_TYPE_ID = H_DOCUMENTS_STEP.FK_TYPE_ID
                                AND H_DOCUMENTS_APPROVAL.STEP_LAYER = H_DOCUMENTS_STEP.STEP_LAYER
                                AND H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = H_DOCUMENTS_STEP.EMPLOYEE_NO
                                AND H_DOCUMENTS_APPROVAL.VERSION_ID = H_DOCUMENTS_PROCESS.VERSION_ID
                        ), 0) AS OLD_VERSION_ID,
                        NVL((SELECT DISTINCT H_DOCUMENTS_APPROVAL.APPROVAL_STATUS FROM H_DOCUMENTS_APPROVAL 
                            WHERE H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
                                AND H_DOCUMENTS_APPROVAL.FK_TYPE_ID = H_DOCUMENTS_STEP.FK_TYPE_ID
                                AND H_DOCUMENTS_APPROVAL.STEP_LAYER = H_DOCUMENTS_STEP.STEP_LAYER
                                AND H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = H_DOCUMENTS_STEP.EMPLOYEE_NO
                                AND H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 2 -- Approve
                        ), 0) AS APPROVAL_STATUS,
                        (1) AS APPROVAL_MAILED,
                        (1) AS FK_TYPE_ID
                    FROM
                        H_DOCUMENTS_PROCESS
                        INNER JOIN H_DOCUMENTS_STEP 
                            ON H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                            AND H_DOCUMENTS_STEP.FK_TYPE_ID = H_DOCUMENTS_PROCESS.FK_TYPE_ID
                            AND H_DOCUMENTS_STEP.STEP_LAYER = H_DOCUMENTS_PROCESS.CURRENT_LAYER
                        INNER JOIN T_DOCUMENTS 
                            ON T_DOCUMENTS.PK_DOCUMENTS_ID = H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                        INNER JOIN P_CATEGORY_PROCESS
                            ON T_DOCUMENTS.FK_CATEGORIES_ID = P_CATEGORY_PROCESS.FK_CATEGORIES_ID
                            AND P_CATEGORY_PROCESS.PROCESS_SORT = H_DOCUMENTS_STEP.STEP_LAYER             
                    WHERE H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = " . $documents_id . " 
                        AND H_DOCUMENTS_STEP.FK_TYPE_ID = 1 -- Doc Prosedur                 
                    ORDER BY H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID ASC
                ) T1                  
            ) T2
            WHERE 
                (
                    T2.PROCESS_TYPE = 0
                    -- jika versi sudah ada, artinya data sudah ada di H_DOCUMENTS_APPROVAL, jadi tidak perlu ikut proses insert ulang
                    AND T2.OLD_VERSION_ID = 0 
                    -- proses seri, di approve satu per satu
                    AND TO_NUMBER(T2.PK_DOCUMENTS_STEP_ID) <= TO_NUMBER(T2.ROW_ANALITIC)
                ) 
                OR T2.PROCESS_TYPE = 1 -- pararel                 
             ORDER BY T2.PK_DOCUMENTS_STEP_ID
		";
        $query = $this->db->query($clone);
    }

    // Utk melakukan insert data ke H_DOCUMENTS_APPROVAL by nota

    public function clone_to_approval_nota($documents_id, $current_layer) {
        $clone = "INSERT INTO DBDOC.H_DOCUMENTS_APPROVAL 
                (				
                    DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
                    DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
                    DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
                    DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
                    DBDOC.H_DOCUMENTS_APPROVAL.FK_TYPE_ID
                ) 				
                SELECT				
                    DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
                    (0) as VERSION_ID,
                    DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
                    DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
                    (0) AS APPROVAL_STATUS,
                    (1) AS APPROVAL_MAILED,
                    (2) AS APPROVAL_NOTA
                FROM
                    DBDOC.H_DOCUMENTS_PROCESS
                    INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
                WHERE DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = " . $documents_id . " AND
                    DBDOC.H_DOCUMENTS_STEP.FK_TYPE_ID = 2 AND				
                    DBDOC.H_DOCUMENTS_STEP.STEP_LAYER = " . $current_layer;

        $query = $this->db->query($clone);
    }

    public function count_approve($array) {
        $sql = "
            SELECT
                COUNT(*) TOTAL_RESPON,
                SUM(CASE WHEN T4.APPROVAL_STATUS=2 THEN 1 ELSE 0 END) TOTAL_APPROVE,
                SUM(CASE WHEN T4.APPROVAL_STATUS=3 THEN 1 ELSE 0 END) TOTAL_REJECT
            FROM
            (
                SELECT
                    T1.FK_DOCUMENTS_ID,
                    T1.FK_TYPE_ID,
                    T1.VERSION_ID,
                    T2.STEP_LAYER,
                    T2.EMPLOYEE_NO
                FROM
                    H_DOCUMENTS_PROCESS T1, H_DOCUMENTS_STEP T2 
                WHERE T1.FK_DOCUMENTS_ID = ?
                    AND T1.FK_TYPE_ID = 1
                    AND T1.FK_DOCUMENTS_ID = T2.FK_DOCUMENTS_ID 
                    AND T1.FK_TYPE_ID = T2.FK_TYPE_ID
                    AND T1.CURRENT_LAYER = T2.STEP_LAYER
             ) T3, H_DOCUMENTS_APPROVAL T4
            WHERE T3.FK_DOCUMENTS_ID = T4.FK_DOCUMENTS_ID(+)
                AND T3.FK_TYPE_ID = T4.FK_TYPE_ID(+)
                AND T3.STEP_LAYER = T4.STEP_LAYER(+)
                AND T3.VERSION_ID = T4.VERSION_ID(+)
                AND T3.EMPLOYEE_NO = T4.EMPLOYEE_NO(+)
		";

        $query = $this->db->query($sql, array($array['dI']));

        if ($query) {
            $row = $query->row();
            $return = array('approve' => $row->TOTAL_APPROVE, 'reject' => $row->TOTAL_REJECT, 'total' => $row->TOTAL_RESPON);
            return $return;
        } else {
            return FALSE;
        }
    }

}

// end class