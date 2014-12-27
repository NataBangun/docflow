<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dis extends CI_Controller 
{
	var $folder = 'documents/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->setter();
	}
	
	public function index()
	{
		//$this->data['layout'] = $this->folder.'dis';
		$this->load->view($this->folder.'dis');
	}
/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();
		$this->data['service'] = 0;
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}
}