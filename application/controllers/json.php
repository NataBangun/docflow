<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends CI_Controller {
	
	var $vdir = 'json';
	
	function __construct()
	{
		parent::__construct();
		//$this->model_session->check_login(NULL, 'login');
	}

	public function index()
	{
		redirect(404);
	}
	
	public function user()
	{
		$query = $this->input->post('query');
		
		$this->db->select('userID,userFname,userName');
		if($this->input->post('init')==FALSE) {
			$this->db->like('userFname', $query);
			$this->db->or_like('userName', $query);
		} else {
			foreach($this->input->post('vars') as $key) {
				$this->db->or_where('userID', $key);
			}
		}
			
		$this->db->limit(15);		
		$records = $this->db->get(DBUSR);
		
		if($records) {
			$arr = array();
			foreach($records->result() as $val) {
				$arr[] = array('name'=>$val->userFname.' ['.$val->userName.']', 'id'=>$val->userID);
			}
			echo json_encode($arr);
		}
	}
	
	public function category()
	{
		$query = $this->input->post('query');
		
		$this->db->select('category_id,category_content');
		if($this->input->post('init')==FALSE) {
			$this->db->or_like('category_content', $query);
		} else {
			foreach($this->input->post('vars') as $key) {
				$this->db->or_where('category_content', $key);
			}
		}	
			
		$this->db->limit(15);		
		$records = $this->db->get(DBTAG);
		
		if($records) {
			$arr = array();
			foreach($records->result() as $val) {
				$arr[] = array('name'=>$val->category_content, 'id'=>$val->category_content);
			}
			echo json_encode($arr);
		}
	}
	
}
/* End of file Json.php */
/* Location: ./application/controllers/Json.php */