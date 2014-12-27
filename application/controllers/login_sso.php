<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_sso extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$TicketId = $this->input->get("LassoTicketId");		
		if($TicketId)
		{				
			$this->client = new SoapClient(WEBSERVICE_SSO_WSDL);						
			$result = $this->client->Validate(array("RequestUrl" => base_url(), "LassoTicketId" => $TicketId))->ValidateResult;
			// var_dump($result);
			$result = explode("|",$result);
			// print_r($result);
			// return;
			if ($result[0] == "OK") {
				$validate = $this->mm_session->validate_login($result[2]);
				if( $validate['error'] == 1) 
				{
					echo "<script>alert(".json_encode($validate['message']).")</script>";
					// redirect(WEBSERVICE_SSO . 'SignOut.aspx?SenderUrl='.base_url());
					echo "<script>location.href = ".json_encode(WEBSERVICE_SSO . 'SignOut.aspx?SenderUrl='.base_url())."</script>";
				} 
				else 
				{
					redirect( site_url('dashboard') );
					return;
				}				
			} else {
				redirect(WEBSERVICE_SSO . 'SignOut.aspx?SenderUrl='.base_url());
			}
		} else {		
			redirect(WEBSERVICE_SSO . 'SignIn.aspx?SenderUrl='.base_url());
		}
	}

	public function logout()
	{
		redirect(WEBSERVICE_SSO . 'SignOut.aspx?senderURL='.base_url());
	}

}