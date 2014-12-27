<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

	var $data=array();
	
	function __construct() {
		parent::__construct();
		$this->mm_session->islogin();
		$this->setter();
	}
	
	public function index()
	{
		$site = 'www.mmediadata.com';
		$port = 80;

		$fp = @fsockopen($site, $port, $errno, $errstr, $timeout=6);
		if (!$fp) 
		{
			$this->data['updates'] = 'Update server offline';
		}
		else
		{		
			$url = 'http://mmediadata.com/api/sap_api.php?version='.$this->data['config']['app_ver'];
			
			$result = simplexml_load_file($url);
			
			$this->data['updates'] = '<span class="label">No found updates.</span>';
			
			if($result==1)
			{
				$this->data['updates'] = '<span class="label label-success">Updates available.</span>';
			}
		}

		$this->load->view('info', $this->data);
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