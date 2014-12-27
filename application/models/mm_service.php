<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_service extends CI_Model {

	var $users_id;

	function __construct()
	{
		parent::__construct();
	}
	
	public function main($users_id)
	{
		$this->return_users_id($users_id);
		$data['myInbox'] = $this->get_inbox();
		return $data;
	}
	
	private function return_users_id($users_id)
	{
		$this->users_id = $users_id;
		return $this->users_id;
	}
	
	public function clear_service()
	{
		$list_cache = array('service_get_inbox_'.$this->users_id);
		foreach($list_cache as $key=>$val)
		{
			$this->cache->delete($val);
		}
		//$this->cache->clean();
	}
	
	/* 
	*  process = 1-4 [ draft, edit, review, publication ]
	*/
	private function get_inbox()
	{
		$CI =& get_instance();
		$CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		$key = 'service_get_inbox_'.$this->users_id;
		$hashed_key = md5($key);

		if ( ! $data = $CI->cache->get($hashed_key) )
		{
			$sql = "SELECT
			DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID,
			DBDOC.T_DOCUMENTS.DOCUMENTS_TITLE,
			DBDOC.T_DOCUMENTS.DOCUMENTS_CBY,
			DBDOC.T_DOCUMENTS.DOCUMENTS_CDT,
			DBDOC.T_DOCUMENTS.DOCUMENTS_DATEPUB,
			DBDOC.P_CATEGORIES.CATEGORIES_TITLE,
			DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID
			FROM
			DBDOC.H_DOCUMENTS_APPROVAL
			INNER JOIN DBDOC.H_DOCUMENTS_PROCESS ON DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID						
			INNER JOIN DBDOC.T_DOCUMENTS ON DBDOC.T_DOCUMENTS.PK_DOCUMENTS_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID						
			INNER JOIN DBDOC.P_CATEGORIES ON DBDOC.T_DOCUMENTS.FK_CATEGORIES_ID = DBDOC.P_CATEGORIES.PK_CATEGORIES_ID
			INNER JOIN DBDOC.T_USERS ON DBDOC.T_DOCUMENTS.DOCUMENTS_CBY = DBDOC.T_USERS.PK_USERS_ID
			WHERE DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = ? AND
			DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS <= ?";		
						
			$query = $this->db->query($sql, array($this->users_id, ACTION_READ));
			
			if($query)
			{
				$data['total'] = $query->num_rows();
				$data['records'] = $query->result_array();
			}
			else
			{
				$data['total'] = 0;
				$data['records'] = FALSE;
			}		
		
			$CI->cache->save($hashed_key, $data, CACHE_MIN_EXP);	
		}		
		return $data;	
	
	}
	
} // end class