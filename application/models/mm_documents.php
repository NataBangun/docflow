<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mm_documents extends CI_Model {

    private $_error_signature; 
    
    function __construct() {
        parent::__construct();
    }

    /*
     *  process = 1-4 [ draft, edit, review, publication ]
     */

    public function get_all($users_id, $process = FALSE) {
        $return = FALSE;
        $sql = "SELECT
		DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
		DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
		DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER,
		DBDOC.H_DOCUMENTS_PROCESS.UDT,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.DOCUMENTS_NO,
		DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
		DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
		DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
		DBDOC.T_DOCUMENTS.DOCUMENTS_LOCK,
		DBDOC.T_DOCUMENTS.DOCUMENTS_CBY,
		DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
		FROM
		DBDOC.H_DOCUMENTS_PROCESS
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID";
        $data = $this->db->query($sql);
        //print_r($data);exit();
        if ($process) {
            $sql .= " AND DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS = $process";
        }

        $sql .= " ORDER BY DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID DESC";

        $query = $this->db->query($sql);

        if ($query) {
            $return = $query->result_array();
        }

        return $return;
    }

    public function get_all_new($users_id, $process = FALSE) {
        $return = FALSE;
        $sql = "SELECT
		DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
		DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
		DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.DOCUMENTS_NO,
		DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
		DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
		DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
		DBDOC.T_DOCUMENTS.DOCUMENTS_LOCK,
		DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
		FROM
		DBDOC.H_DOCUMENTS_PROCESS
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID";
        $data = $this->db->query($sql);
        //print_r($data);exit();
        if ($process) {
            $sql .= " AND DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS = $process";
        }

        $sql .= " ORDER BY DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID DESC";

        $query = $this->db->query($sql);

        if ($query) {
            $return = $query->result_array();
        }

        return $return;
    }

    public function get_search($users_id, $process = FALSE) {
        $search = $this->input->post('search');
        $return = FALSE;
        $sql = "SELECT
		DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
		DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
		DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.DOCUMENTS_NO,
		DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
		DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
		DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
		DBDOC.T_DOCUMENTS.DOCUMENTS_LOCK,
		DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
		FROM
		DBDOC.H_DOCUMENTS_PROCESS
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID
		WHERE
		DBDOC.T_DOCUMENTS.DOCUMENTS_CBY = $users_id
		AND
		DBDOC.T_DOCUMENTS.DOCUMENTS_NO LIKE '$search'
		OR
		DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE LIKE '$search'"
        ;
        $data = $this->db->query($sql);
        //print_r($data);exit();
        if ($process) {
            $sql .= "AND DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS = $process";
        }

        $sql .= "ORDER BY DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID DESC";

        $query = $this->db->query($sql);

        if ($query) {
            $return = $query->result_array();
        }

        return $return;
    }

    public function get_detail($doc_id) {
        $sql = "SELECT
				H_DOCUMENTS_PROCESS.PK_DOCUMENTS_PROCESS_ID,
				H_DOCUMENTS_PROCESS.PROCESS_STATUS,
				H_DOCUMENTS_PROCESS.CURRENT_LAYER,
				H_DOCUMENTS_PROCESS.VERSION_ID,
				T_DOCUMENTS.PK_DOCUMENTS_ID,
				T_DOCUMENTS.DOCUMENTS_NO,
				T_DOCUMENTS.DOCUMENTS_TITLE,
				T_DOCUMENTS.DOCUMENTS_DESCRIPTION,
				T_DOCUMENTS.DOCUMENTS_DATEPUB,
				T_DOCUMENTS.DOCUMENTS_ATC_NAME,
				T_DOCUMENTS.DOCUMENTS_ATC_SYSTEM,
				T_DOCUMENTS.DOCUMENTS_VERSION,
				T_DOCUMENTS.DOCUMENTS_CDT,
				T_DOCUMENTS.DOCUMENTS_CBY,
				T_DOCUMENTS.DOCUMENTS_UDT,
				T_DOCUMENTS.DOCUMENTS_DISTRIBUTION,
				P_CATEGORIES.PK_CATEGORIES_ID,
				P_CATEGORIES.CATEGORIES_TITLE,
				V_EMPLOYEE.E_MAIL_ADDR,				
				V_EMPLOYEE.ORGANIZATION_CODE,				
				V_EMPLOYEE.JOB_POSITION_CODE,				
				V_EMPLOYEE.EMPLOYEE_NAME,
				INITCAP(TO_CHAR(T_WEBINFO.UPLOAD_DATE, 'DD MONTH YYYY HH24:MI:SS', 'NLS_DATE_LANGUAGE=indonesian')) UPLOAD_DATE,
                T_USERS.USERS_SIGNATURE,
                T_USERS.USERS_PARAF				
				FROM
				H_DOCUMENTS_PROCESS
				INNER JOIN T_DOCUMENTS ON T_DOCUMENTS.PK_DOCUMENTS_ID = H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
				INNER JOIN P_CATEGORIES ON P_CATEGORIES.PK_CATEGORIES_ID = T_DOCUMENTS.FK_CATEGORIES_ID
				INNER JOIN V_EMPLOYEE ON V_EMPLOYEE.EMPLOYEE_NO = T_DOCUMENTS.DOCUMENTS_CBY
				LEFT JOIN T_WEBINFO ON H_DOCUMENTS_PROCESS.PK_DOCUMENTS_PROCESS_ID = T_WEBINFO. FK_DOCUMENTS_PROCESS_ID
                LEFT JOIN T_USERS ON T_DOCUMENTS.DOCUMENTS_CBY = T_USERS.EMPLOYEE_NO				
				WHERE
				T_DOCUMENTS.PK_DOCUMENTS_ID = ? AND H_DOCUMENTS_PROCESS.FK_TYPE_ID = 1 ";

        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function get_penandatangan($doc_id) {
        $sql = "SELECT
		DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
		DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_STEP.STEP_CDT,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.E_MAIL_ADDR,				
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.JOB_POSITION_CODE,				
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.ORGANIZATION_CODE				
		FROM
		DBDOC.H_DOCUMENTS_STEP
		INNER JOIN DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR ON DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO
		WHERE
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ? AND DBDOC.H_DOCUMENTS_STEP.FK_TYPE_ID = 1
		ORDER BY
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID ASC";
        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_penandatangan_for_webinfo($doc_id) {
        $sql = "SELECT DISTINCT
		DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
		DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.E_MAIL_ADDR,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.ORGANIZATION_CODE,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.JOB_POSITION_CODE,
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID,
		DBDOC.H_DOCUMENTS_STEP.STEP_CDT,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_UDT,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
        T_USERS.USERS_SIGNATURE,
        T_USERS.USERS_PARAF
		FROM
		DBDOC.H_DOCUMENTS_STEP
		INNER JOIN DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR 
			ON DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO
		INNER JOIN DBDOC.H_DOCUMENTS_APPROVAL 
			ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID 
			AND DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO
			AND DBDOC.H_DOCUMENTS_STEP.STEP_LAYER = DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER
        LEFT JOIN T_USERS
            ON H_DOCUMENTS_STEP.EMPLOYEE_NO = T_USERS.EMPLOYEE_NO
		WHERE
		DBDOC.H_DOCUMENTS_STEP.FK_TYPE_ID = 1 AND
		DBDOC.H_DOCUMENTS_APPROVAL.FK_TYPE_ID = 1 AND
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ? AND
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 2 AND
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_UDT IS NOT NULL
		ORDER BY
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID ASC";
        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_process_by_cat($doc_id) {
        $sql = "SELECT
		DBDOC.P_CATEGORIES.PK_CATEGORIES_ID,
		DBDOC.P_CATEGORIES.CATEGORIES_TITLE,		
		DBDOC.P_CATEGORIES.CATEGORIES_IMAGE,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_NAME,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_PDF_NAME,
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
		DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID
		FROM
		DBDOC.P_CATEGORY_PROCESS
		INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.P_CATEGORIES.PK_CATEGORIES_ID = DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.P_CATEGORIES.PK_CATEGORIES_ID = DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID
		WHERE
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = ?
		ORDER BY
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT ASC";
        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_versioning($doc_id) {
        $sql = "SELECT DISTINCT
		DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
		DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
		DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_UDT,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.E_MAIL_ADDR,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID,
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
		DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID,
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
		FROM
		DBDOC.H_DOCUMENTS_APPROVAL
		INNER JOIN DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR ON DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
		INNER JOIN DBDOC.P_CATEGORY_PROCESS ON DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID = DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID AND DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER = DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT
		INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO AND DBDOC.H_DOCUMENTS_STEP.STEP_LAYER = DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER
		WHERE
		DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ?
		AND
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ?
		ORDER BY
		DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID DESC,
		DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER ASC,
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID ASC";

        $query = $this->db->query($sql, array($doc_id, $doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_versioning_min($doc_id) {
        $sql = "SELECT DISTINCT
		DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
		DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
		DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_UDT,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.E_MAIL_ADDR,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID,
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
		DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID,
		DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID
		FROM
		DBDOC.H_DOCUMENTS_APPROVAL
		INNER JOIN DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR ON DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
		INNER JOIN DBDOC.P_CATEGORY_PROCESS ON DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID = DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID AND DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER = DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT
		INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO AND DBDOC.H_DOCUMENTS_STEP.STEP_LAYER = DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER
		WHERE
		DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ?
		AND
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 3
		AND		
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ?";

        $query = $this->db->query($sql, array($doc_id, $doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_versioning_rows($doc_id) {
        $sql = "SELECT DISTINCT
		DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
		DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
		DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_UDT,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME,
		DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.E_MAIL_ADDR,
		DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
		DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID,
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
		DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID,
		DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
		DBDOC.H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID
		FROM
		DBDOC.H_DOCUMENTS_APPROVAL
		INNER JOIN DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR ON DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = DBDOC.V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
		INNER JOIN DBDOC.P_CATEGORY_PROCESS ON DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID = DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID AND DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER = DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT
		INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO AND DBDOC.H_DOCUMENTS_STEP.STEP_LAYER = DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER
		WHERE
		DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ?
		AND
		DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS < 2
		AND		
		DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ?";

        $query = $this->db->query($sql, array($doc_id, $doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_comments($doc_id) {
        /*
          $sql = "SELECT
          documents_comments.comments_id,
          documents_comments.comments_title,
          documents_comments.comments_descriptions,
          DATE_FORMAT(documents_comments.comments_cdt, '%a, %e %b %Y %H:%i') AS comments_cdt,
          documents_comments.version_id,
          CONCAT(users.users_fname, ' ', users.users_lname) AS fullname
          FROM
          documents_comments
          INNER JOIN users ON documents_comments.comments_cby = users.users_id
          WHERE
          documents_comments.documents_id = ?
          ORDER BY documents_comments.version_id DESC,
          documents_comments.comments_id DESC";
         */
        $sql = "SELECT DISTINCT
					H_DOCUMENTS_COMMENTS.PK_DOCUMENTS_COMMENTS_ID,						
					H_DOCUMENTS_COMMENTS.COMMENTS_CBY,
					H_DOCUMENTS_COMMENTS.COMMENTS_CDT,
					H_DOCUMENTS_COMMENTS.COMMENTS_DESC,
					H_DOCUMENTS_COMMENTS.FK_DOCUMENTS_ID,
					H_DOCUMENTS_COMMENTS.VERSION_ID,
                    H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
					V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NAME
				FROM
					H_DOCUMENTS_COMMENTS
					INNER JOIN V_EMPLOYEE_DOKUMEN_PROSEDUR
						ON H_DOCUMENTS_COMMENTS.COMMENTS_CBY = V_EMPLOYEE_DOKUMEN_PROSEDUR.EMPLOYEE_NO
                    LEFT JOIN H_DOCUMENTS_APPROVAL
                        ON H_DOCUMENTS_COMMENTS.FK_DOCUMENTS_ID =  H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
                        AND H_DOCUMENTS_COMMENTS.FK_TYPE_ID = H_DOCUMENTS_APPROVAL.FK_TYPE_ID
                        AND H_DOCUMENTS_COMMENTS.STEP_LAYER= H_DOCUMENTS_APPROVAL.STEP_LAYER
                        AND H_DOCUMENTS_COMMENTS.VERSION_ID = H_DOCUMENTS_APPROVAL.VERSION_ID
				WHERE H_DOCUMENTS_COMMENTS.FK_DOCUMENTS_ID = ?
					AND H_DOCUMENTS_COMMENTS.FK_TYPE_ID = 1
                    AND H_DOCUMENTS_APPROVAL.APPROVAL_UDT IS NOT NULL
                    AND H_DOCUMENTS_COMMENTS.COMMENTS_CDT IS NOT NULL
                    AND H_DOCUMENTS_APPROVAL.APPROVAL_STATUS IS NOT NULL
                    AND H_DOCUMENTS_COMMENTS.STEP_LAYER IS NOT NULL
				ORDER BY H_DOCUMENTS_COMMENTS.PK_DOCUMENTS_COMMENTS_ID DESC";

        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_files_merge($doc_id) {
        /* $sql = "SELECT
          DBDOC.H_DOCUMENTS_ATTACHMENT.PK_ATC_ID,
          DBDOC.H_DOCUMENTS_ATTACHMENT.VERSION_ID,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_ORIGNAME,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_RAWNAME,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_EXTENSION,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_SIZE,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_CDT,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_CBY,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_FOLDER,
          DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_HASH_KEY
          FROM
          DBDOC.H_DOCUMENTS_ATTACHMENT
          WHERE
          DBDOC.H_DOCUMENTS_ATTACHMENT.FK_DOCUMENTS_ID = ?
          ORDER BY
          DBDOC.H_DOCUMENTS_ATTACHMENT.VERSION_ID DESC"; */
        $sql = "SELECT DOCUMENTS_ATC_SYSTEM FROM T_DOCUMENTS WHERE PK_DOCUMENTS_ID=?";

        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function get_version_files($doc_id) {
        /* fungsi ini tidak digunakan */
        $sql = "SELECT
				DBDOC.H_DOCUMENTS_ATTACHMENT.VERSION_ID,
				DBDOC.H_DOCUMENTS_ATTACHMENT.PK_ATC_ID,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_RAWNAME,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_SIZE,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_ORIGNAME,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_EXTENSION,				
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_FOLDER,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_HASH_KEY,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_CDT,
				DBDOC.H_DOCUMENTS_ATTACHMENT.ATC_CBY
				FROM DBDOC.H_DOCUMENTS_ATTACHMENT
				WHERE
				DBDOC.H_DOCUMENTS_ATTACHMENT.FK_DOCUMENTS_ID = ?				
				ORDER BY DBDOC.H_DOCUMENTS_ATTACHMENT.VERSION_ID DESC";

        $query = $this->db->query($sql, array($doc_id));

        if ($query) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function check_num() {
        $sql = 'SELECT NVL("MAX"(DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID), 0)+1
		FROM
		DBDOC.T_DOCUMENTS';
        $row = $this->db->query($sql)->row();
        foreach ($row as $key => $val) {
            $row = $val;
        }
        return $row;
    }

    public function input_arr_form($arr) {
        if (!$arr) {
            return NULL;
        } else {
            return implode(',', $arr);
        }
    }

    /* Insert new documents */

    public function insert_documents() {

        $image_data = FALSE;
        if ($this->upload->do_multi_upload("files")) {
            $image_data = $this->upload->get_multi_upload_data();
        }

        $img_data = '';
        if ($image_data) {
            foreach ($image_data as $file) { // loop over the upload data 
                $img_data .= $file['file_name'] . ',';
            }
        }

        $timestamp = date('Y-m-d H:i:s');

        $DISTRIBUTION = '';
        if ($this->input->post('distribution') != 0) {
            $DISTRIBUTION = $this->input_arr_form($this->input->post('distribution'));
        }

        $versi = $this->input->post('versi');
        $versi = implode($versi);
        if (!$versi) {
            $versi = 100;
        }

        $doc_status = DOC_DRAFT; // we use always draft docs first

        /* documents - metadata */
        $documents = array(
            'DOCUMENTS_NO' => $this->input->post('no'),
            'DOCUMENTS_TITLE' => $this->input->post('title'),
            'DOCUMENTS_DESCRIPTION' => $this->input->post('descrip'),
            'DOCUMENTS_ATC_NAME' => $img_data,
            'FK_CATEGORIES_ID' => $this->input->post('categories'),
            'DOCUMENTS_VERSION' => $versi,
            'DOCUMENTS_DATEPUB' => $this->input->post('datepub'),
            'DOCUMENTS_CBY' => $this->session->userdata('uID'),
            'DOCUMENTS_DISTRIBUTION' => $DISTRIBUTION,
            'DOCUMENTS_CDT' => $timestamp
        );
        $this->db->insert('T_DOCUMENTS', $documents);

        $sql = 'SELECT
		Max(DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID)
		FROM
		DBDOC.T_DOCUMENTS';
        $row = $this->db->query($sql)->row();
        foreach ($row as $key => $val) {
            $appID = $val;
        }

        /* documents_process - documents project */
        $documents_process = array(
            'FK_DOCUMENTS_ID' => $appID,
            'PROCESS_STATUS' => $doc_status,
            'CURRENT_LAYER' => 1,
            'VERSION_ID' => $versi,
            'FK_TYPE_ID' => 1,
            'UDT' => $timestamp
        );
        $this->db->insert('H_DOCUMENTS_PROCESS', $documents_process);

        // step layer
        $cat_id = $this->input->post('categories');
        $step_layer = $this->check_process($cat_id);

        for ($i = 1; $i <= count($step_layer); $i++) {

            $penandatangan_key = 'penandatangan_' . $cat_id . '_' . $i;
            $penandatangan = $this->input->post($penandatangan_key);

            foreach ($penandatangan as $key => $value) {
                $employee_no = preg_replace("/[^0-9]/", "", $value);
                /* documents_step - step DD */
                $documents_step = array(
                    'FK_DOCUMENTS_ID' => $appID,
                    'EMPLOYEE_NO' => $employee_no,
                    'STEP_LAYER' => $i,
                    'FK_TYPE_ID' => 1,
                    'STEP_CDT' => $timestamp
                );
                $this->db->insert('H_DOCUMENTS_STEP', $documents_step);
            }
        }

        return $appID;
    }

    public function update_documents($users_id) {

        $image_data = FALSE;
        if ($this->upload->do_multi_upload("files")) {
            $image_data = $this->upload->get_multi_upload_data();
        }

        $img_data = '';
        if ($image_data) {
            foreach ($image_data as $file) { // loop over the upload data 
                $img_data .= $file['file_name'] . ',';
            }
        }

        $timestamp = date('Y-m-d H:i:s');
        $doc_id = $this->input->post('documents_id');
        $versi = $this->input->post('versi');
        $versi = implode($versi);

        $DISTRIBUTION = '';
        if ($this->input->post('distribution') != 0) {
            $DISTRIBUTION = $this->input_arr_form($this->input->post('distribution'));
        }

        /* documents - metadata */
        $documents = array(
            'DOCUMENTS_NO' => $this->input->post('no'),
            'DOCUMENTS_TITLE' => $this->input->post('title'),
            'DOCUMENTS_DESCRIPTION' => $this->input->post('descrip'),
            'DOCUMENTS_ATC_NAME' => $this->input->post('file_name') . $img_data,
            'FK_CATEGORIES_ID' => $this->input->post('categories'),
            'DOCUMENTS_VERSION' => $versi,
            'DOCUMENTS_DATEPUB' => $this->input->post('datepub'),
            'DOCUMENTS_DISTRIBUTION' => $DISTRIBUTION,
            'DOCUMENTS_UBY' => $users_id,
            'DOCUMENTS_UDT' => $timestamp
        );

        $this->db->where('PK_DOCUMENTS_ID', $doc_id);
        $this->db->update('T_DOCUMENTS', $documents);

        /* documents_process - documents project */
        $documents_process = array(
            'CURRENT_LAYER' => 1, // initial layer always 1
            'VERSION_ID' => $versi,
            'UDT' => $timestamp
        );

        $this->db->where('FK_DOCUMENTS_ID', $doc_id);
        $this->db->update('H_DOCUMENTS_PROCESS', $documents_process);

        // step layer
        // delete all Step DD this documents_id		
        $this->db->where('FK_DOCUMENTS_ID', $doc_id);
        $this->db->delete('H_DOCUMENTS_STEP');

        // step layer
        $cat_id = $this->input->post('categories');
        $step_layer = $this->check_process($cat_id);

        for ($i = 1; $i <= count($step_layer); $i++) {

            $penandatangan_key = 'penandatangan_' . $cat_id . '_' . $i;
            $penandatangan = $this->input->post($penandatangan_key);

            foreach ($penandatangan as $key => $value) {
                $employee_no = preg_replace("/[^0-9]/", "", $value);
                /* documents_step - step DD */
                $documents_step = array(
                    'FK_DOCUMENTS_ID' => $doc_id,
                    'EMPLOYEE_NO' => $employee_no,
                    'STEP_LAYER' => $i,
                    'FK_TYPE_ID' => 1,
                    'STEP_CDT' => $timestamp
                );
                $this->db->insert('H_DOCUMENTS_STEP', $documents_step);
            }
        }

        return TRUE;
    }

    public function update_documents_revision($users_id) {

        $image_data = FALSE;
        if ($this->upload->do_multi_upload("files")) {
            $image_data = $this->upload->get_multi_upload_data();
        }

        $img_data = '';
        if ($image_data) {
            foreach ($image_data as $file) { // loop over the upload data 
                $img_data .= $file['file_name'] . ',';
            }
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $doc_id = $this->input->post('documents_id');
        $versi = $this->input->post('versi');
        $versi = implode($versi);

        $DISTRIBUTION = '';
        if ($this->input->post('distribution') != 0) {
            $DISTRIBUTION = $this->input_arr_form($this->input->post('distribution'));
        }

        /* documents - metadata */
        $documents = array(
            'DOCUMENTS_NO' => $this->input->post('no'),
            'DOCUMENTS_TITLE' => $this->input->post('title'),
            'DOCUMENTS_DESCRIPTION' => $this->input->post('descrip'),
            'DOCUMENTS_ATC_NAME' => $this->input->post('file_name') . $img_data,
            'DOCUMENTS_DATEPUB' => $this->input->post('datepub'),
            'DOCUMENTS_DISTRIBUTION' => $DISTRIBUTION,
            'DOCUMENTS_UBY' => $users_id,
            'DOCUMENTS_UDT' => $timestamp
        );

        $this->db->where('PK_DOCUMENTS_ID', $doc_id);
        $this->db->update('T_DOCUMENTS', $documents);

        return TRUE;
    }

    public function update_attachment() {
        $user_id = $this->input->post('uid');
        $documents_id = $this->input->post('documents_id');
        $imageData = $this->upload->data();
        $date = date('Y-m-d H:i:s');

        $metadata = array(
            'DOCUMENTS_ATC_SYSTEM' => $imageData['file_name'],
            'DOCUMENTS_UBY' => $user_id,
            'DOCUMENTS_UDT' => $date
        );

        $this->db->where('PK_DOCUMENTS_ID', $documents_id);
        $this->db->update('T_DOCUMENTS', $metadata);            
    }
    
    public function is_attachment_exists($documents_id) {
        $query = $this->db->query("SELECT * FROM T_DOCUMENTS WHERE PK_DOCUMENTS_ID = ? ", array($documents_id));
        $row = $query->row_array();
        if (isset($row['DOCUMENTS_ATC_SYSTEM']) && $row['DOCUMENTS_ATC_SYSTEM']!='') {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function is_signature_exists($documents_id) {
        $this->_error_signature = "";
        $sql = "SELECT * FROM
            (
                SELECT DISTINCT
                    T1.EMPLOYEE_NO,
                    T2.EMPLOYEE_NAME,
                    T3.USERS_SIGNATURE,
                    T3.USERS_PARAF                
                FROM
                    H_DOCUMENTS_STEP T1, 
                    V_EMPLOYEE_DOKUMEN_PROSEDUR T2,
                    T_USERS T3
                WHERE T1.EMPLOYEE_NO = T2.EMPLOYEE_NO 
                    AND T1.FK_DOCUMENTS_ID = ? AND T1.FK_TYPE_ID = 1
                    AND T1.EMPLOYEE_NO = T3.EMPLOYEE_NO(+)    
                ORDER BY
                    T1.EMPLOYEE_NO ASC
            ) T1
            WHERE T1.USERS_SIGNATURE IS NULL 
                OR T1.USERS_PARAF IS NULL
                OR TRIM(T1.USERS_SIGNATURE) = '0'
                OR TRIM(T1.USERS_PARAF) = '0'";
        $query = $this->db->query($sql, array($documents_id));
        $rows = $query->result_array();

        if (is_array($rows) && count($rows) > 0) {
            $this->_error_signature = "Paraf dan/atau Tanda Tangan User belum diupload :";
            foreach ($rows as $row) {
                $this->_error_signature.= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                        .$row['EMPLOYEE_NAME']." (".$row['EMPLOYEE_NO'].")";
            }
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
    
    public function get_error_signature() {
        return $this->_error_signature;
    }

    public function check_process($cat_id) {
        $sql = "SELECT
		DBDOC.P_CATEGORY_PROCESS.PROCESS_NAME,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_SORT,
		DBDOC.P_CATEGORY_PROCESS.PROCESS_TYPE,
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID
		FROM
		DBDOC.P_CATEGORY_PROCESS
		WHERE
		DBDOC.P_CATEGORY_PROCESS.FK_CATEGORIES_ID = $cat_id";
        $data = $this->db->query($sql);
        return $data->result_array();
    }
	
	    public function hapus($doc_id) {
        $sql = "DELETE * FROM 
		DBDOC.T_DOCUMENTS
		WHERE
		PK_DOCUMENTS_ID = $doc_id";
        $data = $this->db->query($sql);
    }

    public function check_img() {
        $sql = "SELECT
		DBDOC.H_DOCUMENTS_ATTACHMENT.PK_ATC_ID
		FROM
		DBDOC.H_DOCUMENTS_ATTACHMENT";
        $data = $this->db->query($sql);
        if ($data) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

// end class
