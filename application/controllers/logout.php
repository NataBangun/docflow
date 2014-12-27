<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	//=========LOGOUT==========//		
	public function index()
	{
		//$this->model_session->log_activity('LOGOUT', 'LOGOUT DARI SISTEM');	
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$this->session->sess_destroy();
		// redirect(site_url('login'));	
		redirect(site_url('login_sso/logout'));	
	}

}