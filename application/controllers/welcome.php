<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->model('mm_users');
		
		$method = 'get_all_users';
		$users = $this->mm_users->$method($method);	
		if($users)
		{
			$this->load->library('table');
			$this->table->set_heading('ID', 'Fullname', 'Username');
			echo $this->table->generate($users); 
		}
		
		
	}
	
	public function check_img()
	{
		$this->load->model('mm_documents');
		$penandatangan = $this->mm_documents->get_penandatangan_for_webinfo('157');
		print_r($penandatangan);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */