<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {
	
	var $folder = 'error/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->setter();
	}
	
	public function index()
	{
		$this->data['layout'] = $this->folder.'error';
		$this->load->view('layout', $this->data);
	}
		
	public function browserError()
	{
		$this->load->view($this->folder.'browser_error');		
	}
	
	/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->user_id = $this->session->userdata('uID');
		$this->data['userInfo'] = $this->session->all_userdata();
		$this->data['service'] = 0;
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}
	
}