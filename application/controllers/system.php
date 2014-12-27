<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System extends CI_Controller 
{
	var $folder = 'system/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->setter();
	}
	
	public function index()
	{
		redirect(404);
	}
	
	public function smtp()
	{
		$this->data['layout'] = $this->folder.'smtp';
		$this->load->view('layout', $this->data);
	}
	
	/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();
		$this->data['service'] = $this->mm_service->main( $this->data['userInfo']['uID'] );
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}

}