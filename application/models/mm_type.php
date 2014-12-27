<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_type extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	public function get()
	{
		$sql = "SELECT
		DBDOC.P_TYPE.PK_TYPE_ID,
		DBDOC.P_TYPE.TYPE_NAME,
		DBDOC.P_TYPE.TYPE_DESC,
		DBDOC.P_TYPE.TYPE_CDT,
		DBDOC.P_TYPE.TYPE_STATUS,
		DBDOC.P_TYPE.TYPE_CBY
		FROM
		DBDOC.P_TYPE
		";
		$query = $this->db->query($sql);		
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}			
	
	public function get_detail()
	{
		$id = $this->uri->segment(3);
		$sql = "SELECT
		DBDOC.P_TYPE.PK_TYPE_ID,
		DBDOC.P_TYPE.TYPE_NAME,
		DBDOC.P_TYPE.TYPE_DESC,
		DBDOC.P_TYPE.TYPE_CDT,
		DBDOC.P_TYPE.TYPE_CBY
		FROM
		DBDOC.P_TYPE
		WHERE DBDOC.P_TYPE.PK_TYPE_ID = $id";
		$query = $this->db->query($sql);		
		if($query)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}

	public function insert()
	{				
		$timestamp = date('Y-m-d H:i:s');
		$desc = sanitize_filename($this->input->post('desc'));		
		$data = array(			
			'TYPE_NAME' => sanitize_filename($this->input->post('title')),
			'TYPE_DESC' => $desc,						
			'TYPE_CBY' => $this->session->userdata('uID'),				
			'TYPE_STATUS' =>0,				
			'TYPE_CDT' => $timestamp					
		);
		$cat = $this->db->insert('P_TYPE', $data);						
		
		return TRUE;
	}	
	
	public function update()
	{	
		$id = $this->input->post('id');					
		$timestamp = date('Y-m-d H:i:s');
		$desc = sanitize_filename($this->input->post('desc'));		
		$data = array(			
			'TYPE_NAME' => sanitize_filename($this->input->post('title')),
			'TYPE_DESC' => $desc,						
			'TYPE_UBY' => $this->session->userdata('uID'),				
			'TYPE_UDT' => $timestamp					
		);
		$this->db->where('PK_TYPE_ID',$id);
		$cat = $this->db->update("P_TYPE", $data);
		return TRUE;
	}		
		
	public function aktif()
	{	
		$id = $this->uri->segment(3);		
		$this->db->set('TYPE_STATUS',0);		
		$this->db->where('PK_TYPE_ID',$id);		
		$cat = $this->db->UPDATE("P_TYPE");		
		return TRUE;
	}	
	
	public function non_aktif()
	{	
		$id = $this->uri->segment(3);		
		$this->db->set('TYPE_STATUS',1);		
		$this->db->where('PK_TYPE_ID',$id);		
		$cat = $this->db->UPDATE("P_TYPE");		
		return TRUE;
	}	
	
}