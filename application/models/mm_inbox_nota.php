<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_inbox_nota extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	/* 
	*  process = 1-4 [ draft, edit, review, publication ]
	*/	
	
	public function get_inbox($users_id)
	{
		$sql = "SELECT * FROM V_INBOX_NOTA WHERE INBOX_OWNER = ?";
		$query = $this->db->query($sql, array($users_id));
		if($query) {
			return $query->result_array();
		} else {
			return FALSE;
		}	
	}

	public function get_monitoring($users_id)
	{
		$sql = "SELECT * FROM V_MONITORING_NOTA WHERE MONITORING_OWNER = ?";
		$query = $this->db->query($sql, array($users_id));
		if($query) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}
	
	public function get_inbox_layer($users_id)
	{
		$as = "APPROVAL_STATUS";
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
		Max(DBDOC.H_DOCUMENTS_STEP.STEP_LAYER) AS $step,
		Max(DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS) AS $as,
		DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER
		FROM
		DBDOC.H_DOCUMENTS_PROCESS
		INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID
		INNER JOIN DBDOC.H_DOCUMENTS_STEP ON DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
		INNER JOIN DBDOC.H_DOCUMENTS_APPROVAL ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
		INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID = DBDOC.P_CATEGORIES.PK_CATEGORIES_ID
		INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.T_DOCUMENTS.DOCUMENTS_CBY = DBDOC.V_EMPLOYEE.EMPLOYEE_NO
		WHERE 
		DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = ? AND 
		DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ?
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
		DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
		DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER";
						
		$query = $this->db->query($sql, array($users_id, DOC_DRAFT));
		//print_r($query);exit();
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	
	}
	
	public function get_detail($nota_id, $users_id)
	{
		$sql = "SELECT
				DBDOC.T_NOTA.HAL,
				DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
				DBDOC.T_NOTA.DESKRIPSI,
				DBDOC.T_NOTA.CREATE_BY,
				DBDOC.T_NOTA.CREATE_DATE,
				DBDOC.T_NOTA.UPDATE_BY,
				DBDOC.T_NOTA.UPDATE_DATE,				
				DBDOC.T_NOTA.TANGGAL_NOTA,				
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
				INNER JOIN DBDOC.T_NOTA ON DBDOC.T_NOTA.PK_NOTA_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
				INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.P_CATEGORIES.PK_CATEGORIES_ID = DBDOC.T_NOTA.FK_CATEGORIES_ID
				INNER JOIN DBDOC.V_EMPLOYEE ON DBDOC.V_EMPLOYEE.EMPLOYEE_NO = DBDOC.T_NOTA.CREATE_BY
				WHERE DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ? AND 
				DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO = ? AND 
				DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS > ? AND
				DBDOC.H_DOCUMENTS_STEP.FK_TYPE_ID = 2 AND
				DBDOC.H_DOCUMENTS_PROCESS.FK_TYPE_ID = 2";
				
		$query = $this->db->query($sql, array($nota_id, $users_id, DOC_DRAFT));
		if($query)
		{
			$row = $query->row_array();
			
			if(isset($row['approval_status']) && $row['approval_status']==ACTION_UNREAD) {
				$this->set_to_read($row);
			}
			
			return $row;
		}
		else
		{
			return FALSE;
		}
	}
	
	private function set_to_read($array)
	{
		$this->db->set('APPROVAL_STATUS', ACTION_READ);
		$this->db->set('APPROVAL_UDT', date('Y-m-d H:i:s'));
		$this->db->where('FK_DOCUMENTS_ID', $array['FK_DOCUMENTS_ID']);
		$this->db->where('VERSION_ID', $array['VERSION_ID']);
		//$this->db->where('step_layer', $array['step_layer']);
		$this->db->where('EMPLOYEE_NO', $array['EMPLOYEE_NO']);
		//$row = $this->db->get('documents_approval');
		$this->db->update('H_DOCUMENTS_APPROVAL');
	}
	
	public function check_is_make_approval($array)
	{
		$sql = "SELECT * FROM DBDOC.H_DOCUMENTS_APPROVAL 
						WHERE 
						DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ? AND
						DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER = ? AND
						DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = ? AND 
						DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS <= ".ACTION_READ." AND
						DBDOC.H_DOCUMENTS_APPROVAL.FK_TYPE_ID = 2";
		$query = $this->db->query($sql, array($array['FK_DOCUMENTS_ID'], $array['CURRENT_LAYER'], $array['EMPLOYEE_NO']));
		
		if($query->num_rows() == 1)
		{
			//return $query->row_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function insert_comment($array)
	{
		$time =  date('Y-m-d H:i:s');
		
		$data = array(	
			'FK_DOCUMENTS_ID' => $array['dI'],
			'VERSION_ID' => $array['vI'],
			'COMMENTS_DESC' => $array['comment'],
			'COMMENTS_CBY' => $array['uID'],
			'FK_TYPE_ID' => 2,
			'COMMENTS_CDT' => $time,
            'STEP_LAYER' => $array['sL']
		);
		$this->db->insert('H_DOCUMENTS_COMMENTS', $data);
	}
		
	// Utk melakukan insert data ke H_DOCUMENTS_APPROVAL by nota
	
	public function clone_to_approval_nota($documents_id, $current_layer)
	{			
		// ORACLE NOW () == TO_CHAR(SYSTIMESTAMP, 'YYYY-MM-DD HH:MI:SS') AS dates_
		$clone = "INSERT INTO H_DOCUMENTS_APPROVAL (				
				H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
				H_DOCUMENTS_APPROVAL.VERSION_ID,
				H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
				H_DOCUMENTS_APPROVAL.STEP_LAYER,
				H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
				H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
				H_DOCUMENTS_APPROVAL.FK_TYPE_ID
				) 				
				SELECT				
				H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
				H_DOCUMENTS_PROCESS.VERSION_ID,
				H_DOCUMENTS_STEP.EMPLOYEE_NO,
				H_DOCUMENTS_STEP.STEP_LAYER,
				(0) AS APPROVAL_STATUS,
				(1) AS APPROVAL_MAILED,
				(2) AS APPROVAL_NOTA
				FROM
				H_DOCUMENTS_PROCESS
				INNER JOIN H_DOCUMENTS_STEP 
					ON H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
					AND H_DOCUMENTS_STEP.FK_TYPE_ID = H_DOCUMENTS_PROCESS.FK_TYPE_ID
				WHERE H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = ".$documents_id." AND
				H_DOCUMENTS_PROCESS.FK_TYPE_ID = 2 AND				
				H_DOCUMENTS_STEP.STEP_LAYER = ".$current_layer;
		
		$query = $this->db->query($clone);
	}
	
	public function count_approve($array)
	{
		$sql = "SELECT
				DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
				DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
				DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
				DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
			FROM DBDOC.H_DOCUMENTS_APPROVAL
			WHERE DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ?
				AND DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER = ?
				AND DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID = ?";
				
		$query = $this->db->query($sql, array($array['dI'], $array['cL'], $array['vI']));
		//print_r($query);exit();
		if($query)
		{
			$return = array('approve'=>0, 'reject'=>0, 'total'=>$query->num_rows());
			foreach($query->result_array() as $key=>$val)
			{
				if( $val['APPROVAL_STATUS']==ACTION_APPROVE )
				{
					$return['approve']++;
				}
				
				if( $val['APPROVAL_STATUS']==ACTION_REJECT )
				{
					$return['reject']++;
				}				
			}
			
			return $return;
		}
		else
		{
			return FALSE;
		}
	}
	
} // end class