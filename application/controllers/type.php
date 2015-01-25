<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Type extends CI_Controller 
{
	var $folder = 'type/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->load->model('mm_type');
		$this->setter();
	}
	
	public function index()
	{
		$this->data['records'] = $this->mm_type->get( $this->session->userdata('uID') );
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
	public function delete()
	{
		$cat_id = intval( $this->uri->segment(3) );		
		if( ! $cat_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		$delete = $this->mm_type->non_aktif( $this->data['userInfo']['uID'] );
		if($delete)
		{
			$this->session->set_flashdata('success', $this->data['config']['msg_non_active']);
			redirect(site_url('type'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(site_url('type'));
		}
	}
	
	public function active()
	{
		$cat_id = intval( $this->uri->segment(3) );		
		if( ! $cat_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		$active = $this->mm_type->aktif( $this->data['userInfo']['uID'] );
		if($active)
		{
			$this->session->set_flashdata('success', $this->data['config']['msg_active']);
			redirect(site_url('type'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(site_url('type'));
		}
	}
	
	public function edit()
	{
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model('mm_categories');
		$this->data['type'] = $this->mm_type->get_detail();
		
		$type_id = intval( $this->uri->segment(3) );
		if( ! $type_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$type = $this->mm_type->get_detail( $type_id, $this->data['userInfo']['uID'] );
		
		if( ! $type )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}			
			
		$this->data['records'] = $type;		
		$this->data['layout'] = $this->folder.'e';
		$this->load->view('layout', $this->data);
	}
	
	
	public function update()
	{		
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[20]|valid_text|callback_title_check');
		$this->form_validation->set_rules('desc', 'Deskripsi', 'required|max_length[100]|valid_html');			
			
		if ($this->form_validation->run() == FALSE)
		{
			$type_id = intval( $this->uri->segment(3) );
			$this->edit($type_id);
		}
		else
		{
			$update = $this->mm_type->update( $this->data['userInfo']['uID'] );			
			if($update){
			$this->session->set_flashdata('success', 'Jenis dokumen berhasil diperbaharui.');
			redirect(site_url('type/edit/'.$this->uri->segment(3)));
			}else{
			$this->session->set_flashdata('error', 'Jenis dokumen gagal diperbaharui, coba ulang kembali.');
			redirect(site_url('type/edit/'.$this->uri->segment(3)));
			}
		}				
		
	}
	
	public function add()
	{	
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');			
		$this->load->model('mm_type');
		$this->data['layout'] = $this->folder.'a';
		$this->load->view('layout', $this->data);
	}
	
	
	public function insert()
	{		
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[20]|valid_text|callback_title_check');
		$this->form_validation->set_rules('desc', 'Deskripsi', 'required|max_length[100]|valid_html');
					
		if ($this->form_validation->run() == FALSE)
		{
			$this->add();
		}
		else
		{
			$insertID = $this->mm_type->insert( $this->data['userInfo']['uID'] );			
			$data['id'] = $insertID;			
			if($insertID){
			$this->session->set_flashdata('success', 'Jenis dokumen berhasil dibuat.');
			redirect(site_url('type'));
			}else{
			$this->session->set_flashdata('error', 'Jenis dokumen gagal dibuat, coba ulang kembali.');
			redirect(site_url('type'));
			}
		}									
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

	public function title_check($str)
	{
		// pengecekan duplikasi data
		if ($this->input->post('id') > 0) {		
			// ketika update
			$sql = "SELECT COUNT(*) JML FROM P_TYPE WHERE UPPER(TYPE_NAME) = UPPER(?) AND PK_TYPE_ID <> ?";
			$query = $this->db->query($sql, array(sanitize_filename($this->input->post('title')), $this->input->post('id')));
		} else {
			// ketika insert
			$sql = "SELECT COUNT(*) JML FROM P_TYPE WHERE UPPER(TYPE_NAME) = UPPER(?)";
			$query = $this->db->query($sql, array(sanitize_filename($this->input->post('title'))));
		}
		$result = $query->result_array();
		// echo ($result[0]['JML']);
		if ($result[0]['JML'] > 0) {
			$this->form_validation->set_message('title_check', 'Kolom %s berisi data yang sudah terdaftar (Duplikasi Data)');
			return false; // error, duplikasi data
		}
		return true;
	}
}