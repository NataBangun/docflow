<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {

	var $folder = 'log/';

	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->setter();
	}
	
	public function index()
	{	
		$this->mm_session->auth_page('log', 1);
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
	public function set()
	{	
		$this->mm_session->auth_page('log', 2);
		echo 'erver log';
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
	/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}
}