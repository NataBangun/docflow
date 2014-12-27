<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_mail extends CI_Model 
{
	var $config;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('email');	
	}
	
	public function send_mass_mail($email_addr_arr=array(), $mail_subject, $mail_content, $mail_content_alt='')
	{
		$this->email->clear();
		$config['useragent'] = config_item('app_abbr');
		$config['charset'] = 'iso-8859-1'; // utf-8
		$config['wordwrap'] = FALSE;
		$config['mailtype'] = 'text';

		$this->email->initialize($config);
		
		if( empty($email_addr_arr['from']) )
		{
			return FALSE;	
		}
		
		$this->email->from($email_addr_arr['from']['mail'], $email_addr_arr['from']['name']);
		
		if( !empty($email_addr_arr['to']) )
		{
			$to_arr = array();
			foreach($email_addr_arr['to'] as $users)
			{
				$to_arr[] = $users['mail'];
			}
			$this->email->to($to_arr);	
		}
		
		if( !empty($email_addr_arr['cc']) )
		{
			$cc_arr = array();
			foreach($email_addr_arr['cc'] as $users)
			{
				$cc_arr[] = $users['mail'];
			}
			$this->email->cc($cc_arr);	
		}
		
		if( !empty($email_addr_arr['bcc']) )
		{
			$bcc_arr = array();
			foreach($email_addr_arr['bcc'] as $users)
			{
				$bcc_arr[] = $users['mail'];
			}
			$this->email->bcc($bcc_arr);	
		}
		
		$this->email->subject($mail_subject);
		$this->email->message($mail_content);

		if($mail_content_alt)
		{
			$this->email->set_alt_message($mail_content_alt);
		}
		
		if($this->email->send())
		{
			return TRUE;
		}
		else
		{
			//echo $this->email->print_debugger();
			return FALSE;
		}
		
	}
	
} 
/* End of file MM_mail.php */
/* Location: ./application/model/MM_mail.php */	