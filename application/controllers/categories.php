<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller 
{
	var $folder = 'categories/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->load->model('mm_categories');
		$this->setter();
	}
	
	public function index()
	{
		$this->data['records'] = $this->mm_categories->get( $this->session->userdata('uID') );
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
		$delete = $this->mm_categories->non_aktif( $this->data['userInfo']['uID'] );
		if($delete)
		{
			$this->session->set_flashdata('success', $this->data['config']['msg_non_active']);
			redirect(site_url('categories'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(site_url('categories'));
		}
	}
	
	public function aktif()
	{
		$cat_id = intval( $this->uri->segment(3) );		
		if( ! $cat_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		$delete = $this->mm_categories->aktif( $this->data['userInfo']['uID'] );
		if($delete)
		{
			$this->session->set_flashdata('success', $this->data['config']['msg_active']);
			redirect(site_url('categories'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(site_url('categories'));
		}
	}
	public function delete_img()
	{
		$cat_id = intval( $this->uri->segment(3) );		
		if( ! $cat_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		$delete = $this->mm_categories->delete_image( $this->data['userInfo']['uID'] );
		if($delete)
		{
			$this->session->set_flashdata('success', 'Stampel Berhasil dihapus');
			redirect(site_url('categories/edit/'.$cat_id));
		}
		else
		{
			$this->session->set_flashdata('error', 'Stampel Berhasil dihapus');
			redirect(site_url('categories/edit/'.$cat_id));
		}
	}
	
	public function edit()
	{
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model('mm_categories');
		$this->data['categories'] = $this->mm_categories->get();
		$this->data['type'] = $this->mm_categories->get_type();
		
		$cat_id = intval( $this->uri->segment(3) );
		if( ! $cat_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$categories = $this->mm_categories->get_detail( $cat_id, $this->data['userInfo']['uID'] );
		$pro = $this->mm_categories->get_process_by_id( $cat_id, $this->data['userInfo']['uID'] );
		if( ! $categories )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}			
			
		$this->data['records'] = $categories;
		$this->data['proccess'] = $pro;
		$this->data['layout'] = $this->folder.'e';
		$this->load->view('layout', $this->data);
	}
	
	public function update()
	{		
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[50]|callback_title_check');		
		$this->form_validation->set_rules('add1', 'Penandatangan', 'required|max_length[20]|callback_add1_check');		
		$this->form_validation->set_rules('desc', 'Deskripsi', 'required|max_length[1000]');		
		$this->form_validation->set_rules('type', 'Jenis Dokumen', 'required|greater_than[0]');	
			
		if ($this->form_validation->run() == FALSE)
		{
			$cat_id = intval( $this->uri->segment(3) );
			
			$this->edit($cat_id);
		}
		else
		{
			$update = $this->mm_categories->update_categories( $this->data['userInfo']['uID'] );
			if($update)
			{
				$this->session->set_flashdata('success', 'Kategori berhasil diperbaharui.');
				redirect(site_url('categories/edit/'.$this->uri->segment(3)));
			}
			else
			{
				$this->session->set_flashdata('error', 'Kategori baru gagal diperbaharui.');
				redirect(site_url('categories/edit/'.$this->uri->segment(3)));
			}
		}				
	}
	
	public function insert()
	{		
		
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[50]|callback_title_check');		
		$this->form_validation->set_rules('add1', 'Penandatangan', 'required|max_length[20]|callback_add1_check');		
		$this->form_validation->set_rules('desc', 'Deskripsi', 'required|max_length[1000]');		
		$this->form_validation->set_rules('type', 'Jenis Dokumen', 'required|greater_than[0]');		
					
		if ($this->form_validation->run() == FALSE)
		{
			$this->add();	
		}
		else
		{		
			//print_r($_POST);
			$insertID = $this->mm_categories->insert_categories( $this->data['userInfo']['uID'] );									
			if($insertID)
			{
				$this->session->set_flashdata('success', 'Kategori baru berhasil dibuat.');
				redirect(site_url('categories'));
			}
			else
			{
				$this->session->set_flashdata('error', 'Kategori baru gagal dibuat.');
				redirect(site_url('categories/add'));
			}
			
		}								
	}	

	public function add()
	{
		$this->data['type'] = $this->mm_categories->get_type();
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model('mm_categories');
		$this->data['layout'] = $this->folder.'a';	
		$this->load->view('layout', $this->data);
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
			$sql = "SELECT COUNT(*) JML FROM P_CATEGORIES WHERE UPPER(CATEGORIES_TITLE) = UPPER(?) AND PK_CATEGORIES_ID <> ?";
			$query = $this->db->query($sql, array(sanitize_filename($this->input->post('title')), $this->input->post('id')));
		} else {
			// ketika insert
			$sql = "SELECT COUNT(*) JML FROM P_CATEGORIES WHERE UPPER(CATEGORIES_TITLE) = UPPER(?)";
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
	
	public function add1_check($str)
	{
		
		$sql = array();
		if($this->input->post('val')){
			$val = $this->input->post('val');
		}else{
			$val = $this->input->post('categories_val');		
		}
		// $x = 1;
		for ($x=1; $x<=$val; $x++) {
			if ($this->input->post('add'.$x)) {
				$sql[] = "SELECT '".$this->input->post('add'.$x)."' k1 FROM DUAL";
			}
		}
		// while ($this->input->post('order_status'.$x) > 0) 
		// {
			// $x++;
		// }
		$sql = "SELECT count(*) JML FROM
			(
				SELECT k1, count(*) FROM
				(
					".implode(" UNION ALL ", $sql)."
				) t1
				GROUP BY k1
				HAVING count(*) > 1
			) t2";
		echo "<!--". $sql ."-->";	
		$query = $this->db->query($sql);
		$result = $query->result_array();
		for ($x=1; $x<=$val; $x++) {
		$cekttd = $this->input->post('add'.$x);
		// echo ($result[0]['JML']);
		if ($result[0]['JML'] > 0) {
			$this->form_validation->set_message('add1_check', 'Kolom %s tidak boleh berisi data yang sama (Duplikasi Data)');
			return false; // error, duplikasi data
		}
			if(empty($cekttd)){
			$this->form_validation->set_message('add1_check', 'Kolom Penandatangan harus terisi.');
			return false; // error, duplikasi data
			}
			}
		
		return true;
		}
}