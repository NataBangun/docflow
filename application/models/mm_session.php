<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_session extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	public function islogin($page=FALSE)
	{
		$islogin = $this->session->userdata('i');		
		
		if( ! $islogin )
		{
			$login_page = $this->uri->segment(1);
			if( $login_page != 'login' )
			{
				redirect( base_url() );
			}
		}
		else
		{
			if($page=='dashboard')
			{
				redirect( site_url('dashboard') );
			}
			
			if( strstr($page, 'http://')!=FALSE )
			{
				redirect( $page );
			}
		}
	}
	
	public function validate_login($usr)
	{
		$return = array('error'=>0, 'message'=>'');
		
		$this->db->where("V_EMPLOYEE.USER_NAME",strtoupper($usr));
		// $this->db->where("'P4ssword'",$pwd);
		$query = $this->db->get('V_EMPLOYEE');

		if($query->num_rows()==1)
		{
			$row = $query->row_array();
			$session_value = array(
				'uID'=>$row['EMPLOYEE_NO'],
				'fullname'=>$row['EMPLOYEE_NAME'],
				'email'=>$row['E_MAIL_ADDR'],
				'nik'=>$row['EMPLOYEE_NO'],
				'avatar'=>''
			);
			$session_value['auth'] = 1;
			// print_r($session_value);
			
			$client = new SoapClient(WEBSERVICE_UMC_WSDL);
			// print_r($client->getFeaturesText(array("ApplicationName"=>"Document Workflow", "NIK"=>$row['EMPLOYEE_NO'])));
			$session_value['umc_feature'] = $client->getFeaturesText(array("ApplicationName"=>"Document Workflow", "NIK"=>$row['EMPLOYEE_NO']))->GetFeaturesTextResult;
			
			$this->setup_session( $session_value );	
			// print_r($session_value);
			return $return;
			//exit();
		} else {
			$return['error'] = 1;
			$return['message'] = "Error validate_login in mm_session. (Hint: '$usr' tidak ada di V_EMPLOYEE)";
			return $return;
		}
	}
	
	// public function validate_login($usr, $pwd)
	// {
		// $return = array('error'=>0, 'message'=>'');
		// $salt = config_item('salt');
		// $hash_pwd = md5($salt.$pwd);
		
		// $sql = "SELECT users.users_id, users.users_email, CONCAT(users.users_fname, ' ', users.users_lname) AS fullname,
				// users.groups_id, users.roles_id AS users_roles_id, users.users_signature, users.users_avatar,
				// users.users_login, groups.groups_login, groups.groups_title, groups.roles_id AS groups_roles_id
				// FROM users
				// INNER JOIN groups ON users.groups_id = groups.groups_id
				// WHERE users.users_email = ? AND
				// users.users_password = ? AND
				// users.users_login = 1 AND
				// groups.groups_id != 0 AND
				// groups.groups_login = 1
				// LIMIT 1";
		
		// $query = $this->db->query($sql, array($usr, $hash_pwd));
		// if($query->num_rows()==1)
		// {
			// $row = $query->row_array();
			// $session_value = array(
				// 'uID'=>$row['users_id'],
				// 'fullname'=>$row['fullname'],
				// 'email'=>$row['users_email'],
				// 'avatar'=>$row['users_avatar']
			// );
			
			//first try with groups, to check their roles
			// if( $row['groups_id'] )
			// {
				// $groups = $this->get_groups( $row['groups_id'] );				
				//if no groups that relation to roles
				// if( ! $groups )
				// {
					// if( $row['users_roles_id'] )
					// {
						//try login with roles id
						// $roles = $this->get_roles( $row['users_roles_id'] );
						// if( ! $roles )
						// {
							// $return['error'] = 1;
							// $return['message'] = $this->config->item('msg_conf_account2');
						// }
						// else
						// {
							// $session_value['auth'] = $roles['roles_value'];
							// $this->setup_session( $session_value );
						// }
						
					// }
					// else // the account has no group and roles configured well
					// {
						// $return['error'] = 1;
						// $return['message'] = $this->config->item('msg_conf_account1');
					// }
				// }
				// else
				// {
					// $session_value['auth'] = $groups['roles_value'];
					// $this->setup_session( $session_value );				
				// }
			// }
			// else
			// {
				//cause the group id is empty lets trys with roles
				// if( ! $row['users_roles_id'] )
				// {
					// $return['error'] = 1;
					// $return['message'] = $this->config->item('msg_conf_account2');
				// }
				// else
				// {
					// $roles = $this->get_roles( $row['roles_id'] );
					// if( ! $roles ) 
					// {
						// $return['error'] = 1;
						// $return['message'] = $this->config->item('msg_conf_account2');
					// }
					// else
					// {
						// $session_value['auth'] = $roles['roles_value'];
						// $this->setup_session( $session_value );
					// }
				// }
			// }
		// }
		// else
		// {
			// $return['error'] = 1;
			// $return['message'] = $this->config->item('msg_login_fail1');
		// }
		
		// return $return;
		
	// }
	
	// $parameter = string
	private function setup_session($parameter)
	{
		$parameter['i'] = 1;		
		$data = array();
		
		$auth = explode(',', $parameter['auth']);
		foreach($auth as $key=>$val)
		{
			$x = explode('=',$val);
			if(isset($x[1]))
			{
				$data[$x[0]] = $x[1];
			}
		}
		
		$parameter['auth'] = $data;
		$this->session->set_userdata( $parameter );		
	}

	/*
	*  Kick user if they lack of permission
	*  $page (see: config/config.php)
	*  $role (see: config/config.php)
	*/
	public function auth_page($page, $role)
	{
		$CI =& get_instance();
		$CI->load->config('mm_config');
		$session = $this->session->userdata('auth');
		
		if(! isset($session[$page]) )
		{
			$CI->session->set_flashdata('error', $CI->config->item('msg_forbid_page'));
			redirect(site_url());
		}
		
		$currAuth = intval($session[$page]);
	
		if( $currAuth < $role ) 
		{
			$CI->session->set_flashdata('error', $CI->config->item('msg_forbid_page'));
			redirect(site_url());
		}
	}
	
	/*
	*  Hide stuff if user lack of permission
	*  $page (see: config/config.php)
	*  $role (see: config/config.php)
	*/
	public function auth_display($page, $role)
	{
		$CI =& get_instance();
		$session = $CI->session->userdata('auth');
		
		if(! isset($session[$page]) )
		{
			return FALSE;
		}
		
		$currAuth = intval( $session[$page] );
		
		if( $currAuth < $role ) 
		{
			return FALSE;
		}
	}

	// Helper for displaying session
	public function _display_sess()
	{
		$sess_arr = $this->session->all_userdata();
		$output = '';
		$output .= '<div class="dev">';
		while(list($a, $b) = each($sess_arr))
		{
			$output .= '<span class="label">'.$a.'/'.$b.'</span> ';
		}
		$output .= '</div>';
		return $output;
	}
	
	// Server activities
	public function log_activity($type, $changes, $data=NULL)
	{
		$i = 1;
		if($data!=NULL) {
			$changes .= ' Data=> ';
		}
		
		$this->db->set('log_ip', $this->session->userdata('ip_address'));
		$this->db->set('log_agent', $this->session->userdata('user_agent'));
		$this->db->set('log_type', $type);
		$this->db->set('ID_user', $this->session->userdata('uID'));
		
		if(is_array($data)) 
		{			
			foreach($data as $key=>$val) 
			{
				if($key != $this->config->item('csrf_token_name'))
				{					
					if($i!= 1) { 
						$changes .= ', ';
					}
					$changes .= '['.$key.'] : '.(($val!='')?$val:NULL);
					$i++;					
				}
			}			
		}
		else
		{
			$changes .= ' '. $data;
		}
		
		$this->db->set('log_activity', $changes);
		$this->db->set('log_date', 'NOW()', FALSE);
		$this->db->insert('log_server');
	}
	
	private function get_groups($groups_id)
	{
		$sql = "SELECT
				groups.groups_title,
				groups.roles_id,
				roles.roles_title,
				roles.roles_value
				FROM
				groups
				INNER JOIN roles ON groups.roles_id = roles.roles_id
				WHERE
				groups.groups_id = ? AND
				roles.roles_login = 1
				LIMIT 1";
				
		$query = $this->db->query($sql, array($groups_id));	
		if($query->num_rows()==0) return FALSE;
		return $query->row_array();
	}
	
	// $roles_id = int
	private function get_roles($roles_id)
	{
		$sql = "SELECT
				roles.roles_id,
				roles.roles_title,
				roles.roles_value,
				roles.roles_login
				FROM
				roles
				WHERE
				roles.roles_id = ? AND
				roles.roles_login = 1";
		$query = $this->db->query($sql, array($roles_id));
		if($query->num_rows()==0) return FALSE;
		return $query->row_array();
	}
	
}