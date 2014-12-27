<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_Nota_kepada extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}	
	
	public function get_kepada($key="get_all_users", $expired=500)
	{
		//$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//$hashed_key = md5($key);

		//if ( ! $query = $this->cache->get($hashed_key))
		//{
			$sql = "SELECT T1.EMPLOYEE_NO, T1.EMPLOYEE_NAME, T1.E_MAIL_ADDR, T1.KEPADA FROM V_NOTA_KEPADA T1";
			$query = $this->db->query($sql);		
			if($query)
			{
				$query = $query->result_array();
			}
			else
			{
				$query = FALSE;
			}
			
			//$this->cache->save($hashed_key, $query, $expired);	
		//}
		
		return $query;
	}
	
	public function get_dari($key="get_all_users", $expired=500)
	{
		//$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//$hashed_key = md5($key);

		//if ( ! $query = $this->cache->get($hashed_key))
		//{
			$sql = "SELECT T1.EMPLOYEE_NO, T1.EMPLOYEE_NAME, T1.DARI FROM V_NOTA_DARI T1";
			$query = $this->db->query($sql);		
			if($query)
			{
				$query = $query->result_array();
			}
			else
			{
				$query = FALSE;
			}
			
			//$this->cache->save($hashed_key, $query, $expired);	
		//}
		
		return $query;
	}
	
	public function get_tembusan($key="get_all_users", $expired=500)
	{
		//$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//$hashed_key = md5($key);

		//if ( ! $query = $this->cache->get($hashed_key))
		//{
			$sql = "SELECT * FROM V_NOTA_TEMBUSAN";
			$query = $this->db->query($sql);		
			if($query)
			{
				$query = $query->result_array();
			}
			else
			{
				$query = FALSE;
			}
			
			//$this->cache->save($hashed_key, $query, $expired);	
		//}
		
		return $query;
	}
	
        public function get_klasifikasi($key="get_all_users", $expired=500)
	{
		//$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//$hashed_key = md5($key);

		//if ( ! $query = $this->cache->get($hashed_key))
		//{
			$sql = "SELECT * FROM P_KLASIFIKASI";
			$query = $this->db->query($sql);		
			if($query)
			{
				$query = $query->result_array();
			}
			else
			{
				$query = FALSE;
			}
			
			//$this->cache->save($hashed_key, $query, $expired);	
		//}
		
		return $query;
	}
        
	public function get_pengesahan($key="get_all_users", $expired=500)
	{
		//$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//$hashed_key = md5($key);

		//if ( ! $query = $this->cache->get($hashed_key))
		//{
			$sql = "SELECT * FROM V_NOTA_PENGESAHAN";
			$query = $this->db->query($sql);		
			if($query)
			{
				$query = $query->result_array();
			}
			else
			{
				$query = FALSE;
			}
			
			//$this->cache->save($hashed_key, $query, $expired);	
		//}
		
		return $query;
	}
	
        public function get_pembuat_konsep($key="get_all_users", $expired=500)
	{
		//$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//$hashed_key = md5($key);

		//if ( ! $query = $this->cache->get($hashed_key))
		//{
			$sql = "SELECT * FROM V_NOTA_PEMBUAT_KONSEP";
			$query = $this->db->query($sql);		
			if($query)
			{
				$query = $query->result_array();
			}
			else
			{
				$query = FALSE;
			}
			
			//$this->cache->save($hashed_key, $query, $expired);	
		//}
		
		return $query;
	}
	
}