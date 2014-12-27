<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usr extends CI_Controller {

	var $folder = 'users/';

	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();		
		$this->load->model('mm_users');
		$this->setter();
	}
	public function index()
	{		
		$this->data['records_user'] = $this->mm_users->get_list_sign( $this->session->userdata('uID') );
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
	public function add()
	{	
		$id = $this->uri->segment(3);
		$this->db->where('EMPLOYEE_NO',$id);
		$this->data['records'] = $this->db->get('T_USERS')->row_array();
		$this->data['layout'] = $this->folder.'a';
		$this->load->view('layout', $this->data);
	}
	
	public function uploads()
	{
		$id = $this->uri->segment(3);
			if($_FILES['userfile']['name'] == '' && $_FILES['paraf']['name'] == '')
			{
				$this->session->set_flashdata('error', 'Isi data dengan benar.');
				redirect(site_url('usr/add/'.$id));
			}			
			$uploads = $this->mm_users->uploads();
			if($uploads)
			{
				$this->session->set_flashdata('success', 'Data telah tersimpan.');
				redirect(site_url('usr/add/'.$id));
			}else{
				$this->session->set_flashdata('error', 'Gagal mengupload tanda tangan dan paraf.');
				redirect(site_url('usr/add/'.$id));
			}		
	}
	
	/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();
		//$this->data['service'] = 0;
		$this->data['service'] = $this->mm_service->main( $this->session->userdata('uID') );
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}
}