<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	var $folder = '';
	
	function __construct()
	{
		parent::__construct();
		//$this->mm_session->islogin('dashboard');
		$this->load->config('mm_config');
	}
	
	public function index()
	{				
		
		$data['layout'] = '';
		$data['message'] = '<div class="warning">Your login information is incorrect!</div>';
		
		$this->form_validation->set_error_delimiters('<div class="warning">', '</div>');	
		
		if( ! empty($_POST) )
		{
			$this->form_validation->set_rules('usr', 'Username', 'required');
			$this->form_validation->set_rules('pwd', 'Password', 'required');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('login', $data);
			}
			else
			{
				$usr = $this->input->post('usr');
				$pwd = $this->input->post('pwd');
				$validate = $this->mm_session->validate_login($usr, $pwd);
				if( $validate['error'] == 1) 
				{
					$data['error'] = $validate['message'];
					$this->load->view('login', $data);
				} 
				else 
				{
					redirect( site_url('dashboard') );
				}
			}		
		}
		else
		{
			$this->load->view('login', $data);
		}
	}
	
}