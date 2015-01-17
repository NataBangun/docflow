<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota extends CI_Controller
{
	var $folder = 'nota/';
	var $data=array();

	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->load->model('mm_nota');
		$this->load->model('mm_categories');
		$this->load->helper('oci8_helper');
		$this->load->model('mm_nota_email');	
		$this->load->library('pagination_bas', '', 'pg_nota1');
		$this->load->library('pagination_bas', '', 'pg_nota2');
		$this->setter();
		
		// Nota Dinas
        $this->field_nota=array(
            array('field'=>'PK_NOTA_ID', 'label'=>'No', 'attribut'=>array('class'=>'form-control', 'style'=>'width:50px')),
            array('field'=>'NO_SURAT', 'label'=>'Nomor Surat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:150px')),
            array('field'=>'HAL', 'label'=>'Judul', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'DARI', 'label'=>'Dari', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'PROCESS_STATUS_DTL', 'label'=>'Status', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'CREATE_BY_NAME', 'label'=>'Penyusun', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'TANGGAL_NOTA', 'label'=>'Tgl. Publikasi', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px')),
            array('field'=>'CREATE_DATE', 'label'=>'Tgl. Buat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px'))
        );
		
		$this->field_nota[0]['script'] = <<<EOD
"<a href=\"".site_url('nota/detail/'      .\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['PK_NOTA_ID']}</a>";
EOD;
		$this->field_nota[1]['script'] = <<<EOD
"<a href=\"".site_url('nota/detail/'      .\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['NO_SURAT']}</a>";
EOD;
		$this->field_nota[2]['script'] = <<<EOD
"<span>{\$value['HAL']}</span><br>
<span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
		$this->field_nota[5]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

		$this->pg_nota1->set_component_id('pg_nota1');
        $this->pg_nota1->set_field($this->field_nota);
		
		$this->pg_nota2->set_component_id('pg_nota2');
        $this->pg_nota2->set_field($this->field_nota);
	}
        
	public function search()
	{
		if( ! empty($_POST) )
		{
			$this->data['records'] = $this->mm_nota->get_search($this->session->userdata('uID'));
			$this->data['layout'] = $this->folder.'v';
			$this->load->view('layout', $this->data);
		}
	}
        
	public function index()
	{
		// $this->data['records'] = $this->mm_nota->get_all( $this->data['userInfo']['uID'] );
		
        $this->pg_nota1->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_nota1->set_ajax_url(site_url().'nota/search_nota1');
        $this->data['daftar_nota1'] = $this->pg_nota1->generate_all();

        $this->pg_nota2->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_nota2->set_ajax_url(site_url().'nota/search_nota2');
        $this->data['daftar_nota2'] = $this->pg_nota2->generate_all();
		
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}

    public function search_nota1($page){
        $this->pg_nota1->set_table('V_DAFTAR_NOTA');
		if (in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))) {
			$where = array('PROCESS_STATUS <'=>NOTA_FINAL);
		} else {
			$where = array('PROCESS_STATUS <'=>NOTA_FINAL, 'CREATE_BY'=>$this->data['userInfo']['uID']);
		}
		$this->pg_nota1->set_where($where);
        $this->pg_nota1->set_paging($_POST,10,$page);
        $this->pg_nota1->generate_table_data();
    }
	
    public function search_nota2($page){
        $this->pg_nota2->set_table('V_DAFTAR_NOTA');
		if (in_array("Admin Sekretaris", explode("|", $this->session->userdata('umc_feature')))) {
			$where = array('PROCESS_STATUS'=>NOTA_FINAL);
		} else {
			$where = array('PROCESS_STATUS'=>NOTA_FINAL, 'CREATE_BY'=>$this->data['userInfo']['uID']);
		}
		$this->pg_nota2->set_where($where);
        $this->pg_nota2->set_paging($_POST,10,$page);
        $this->pg_nota2->generate_table_data();
    }
	
	public function priview(){

		if( ! empty($_POST) )
		{
			$this->form_validation->set_rules('title', 'Judul', 'required');
			$this->form_validation->set_rules('desc', 'Deskripsi/Catatan', 'required');
			$this->form_validation->set_rules("penandatangan$key", $val, 'required');


			if ($this->form_validation->run() == FALSE)
			{
				$data['response'] = validation_errors();
				$data['error'] = 1;
				echo json_encode($data);
			}
			else
			{
			
				$update = $this->mm_nota->update_nota( $this->data['userInfo']['uID'] );
				$data['response'] = $update;
				$data['error'] = 0;
				echo json_encode($data);
			}
		}
		else
		{
			$this->load->view('nota/preview', $this->data);
		}
	}       
        
	public function edit()
	{
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_nota_kepada'));
		$this->data['categories'] = $this->mm_categories->get();				
		$this->data['process'] = $this->mm_categories->get_process();
		$this->data['users_nota_klasifikasi'] = $this->mm_nota_kepada->get_klasifikasi();
		$this->data['users_nota_kepada'] = $this->mm_nota_kepada->get_kepada();
		$this->data['users_nota_dari'] = $this->mm_nota_kepada->get_dari();
		$this->data['users_nota_pengesahan'] = $this->mm_nota_kepada->get_pengesahan();
		$this->data['users_nota_tembusan'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_paraf'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_inisial'] = $this->mm_nota_kepada->get_tembusan();


		$nota_id = intval( $this->uri->segment(3) );
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$nota = $this->mm_nota->get_detail_nota( $nota_id, $this->data['userInfo']['uID'] );
		if( ! $nota )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}					
		
		if( $nota['PROCESS_STATUS'] != NOTA_DRAFT && $nota['PROCESS_STATUS'] != NOTA_EDIT && $nota['PROCESS_STATUS'] != NOTA_FINAL)
		{			
			
			$this->session->set_flashdata('error', $this->data['config']['msg_doc_on_track']);
			redirect( site_url('nota/detail/'.$nota_id) );
		}elseif( $nota['PROCESS_STATUS'] == NOTA_FINAL )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_doc_final']);
			redirect( site_url('nota/detail/'.$nota_id) );
		}

		$this->data['records'] = $nota;
		$this->data['penandatangan'] = $this->mm_nota->get_penandatangan($nota_id);
		//$this->data['files'] = $this->mm_nota->get_version_files($nota_id);
		$this->data['layout'] = $this->folder.'e';
		$this->load->view('layout', $this->data);		
	}
	
	public function edit_revisi()
	{
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_nota_kepada'));
		$this->data['categories'] = $this->mm_categories->get();				
		$this->data['process'] = $this->mm_categories->get_process();
		$this->data['users_nota_klasifikasi'] = $this->mm_nota_kepada->get_klasifikasi();
		$this->data['users_nota_kepada'] = $this->mm_nota_kepada->get_kepada();
		$this->data['users_nota_dari'] = $this->mm_nota_kepada->get_dari();
		$this->data['users_nota_pengesahan'] = $this->mm_nota_kepada->get_pengesahan();
		$this->data['users_nota_tembusan'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_paraf'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_inisial'] = $this->mm_nota_kepada->get_tembusan();


		$nota_id = intval( $this->uri->segment(3) );
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$nota = $this->mm_nota->get_detail_nota( $nota_id, $this->data['userInfo']['uID'] );
		if( ! $nota )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}					
		
		if( $nota['PROCESS_STATUS'] != NOTA_DRAFT && $nota['PROCESS_STATUS'] != NOTA_EDIT && $nota['PROCESS_STATUS'] != NOTA_FINAL)
		{			
			
			$this->session->set_flashdata('error', $this->data['config']['msg_doc_on_track']);
			redirect( site_url('nota/detail/'.$nota_id) );
		}elseif( $nota['PROCESS_STATUS'] == NOTA_FINAL )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_doc_final']);
			redirect( site_url('nota/detail/'.$nota_id) );
		}

		$this->data['records'] = $nota;
		$this->data['penandatangan'] = $this->mm_nota->get_penandatangan($nota_id);
		//$this->data['files'] = $this->mm_nota->get_version_files($nota_id);
		$this->data['layout'] = $this->folder.'e_rev';
		$this->load->view('layout', $this->data);		
	}
	
	public function update()
	{	
		$id = $this->uri->segment(3);
		$this->form_validation->set_rules('dari', 'Dari', 'required');		
		$this->form_validation->set_rules('hal', 'Hal', 'required|max_length[100]');                    
		$this->form_validation->set_rules('tempat', 'Tempat', 'required|max_length[50]');
		$this->form_validation->set_rules('desc', 'Isi', 'required');
		$this->form_validation->set_rules('pembuat_konsep[]', 'Pembuat Konsep', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{			
			$this->edit($id);			
		}
		else
		{
			$update = $this->mm_nota->update_nota( $this->data['userInfo']['uID'] );
			if($update){
				$this->session->set_flashdata('success', 'Nota dinas berhasil diperbaharui.');
				redirect(site_url('nota/edit/'.$id));
			}else{
				$this->session->set_flashdata('error', 'Nota dinas gagal diperbaharui.');
				redirect(site_url('nota/edit/'.$id));
			}			
		}		
	}
	
	public function update_rev()
	{	
		$id = $this->uri->segment(3);
		$this->form_validation->set_rules('dari', 'Dari', 'required');		
		$this->form_validation->set_rules('hal', 'Hal', 'required|max_length[100]');                    
		$this->form_validation->set_rules('tempat', 'Tempat', 'required|max_length[50]');
		$this->form_validation->set_rules('desc', 'Isi', 'required');
		$this->form_validation->set_rules('pembuat_konsep[]', 'Pembuat Konsep', 'required');

		
		if ($this->form_validation->run() == FALSE)
		{			
			$this->edit_revisi($id);			
		}
		else
		{
			$update = $this->mm_nota->update_nota_rev( $this->data['userInfo']['uID'] );
			if($update){
				$this->session->set_flashdata('success', 'Nota dinas berhasil diperbaharui.');
				redirect(site_url('nota/edit_revisi/'.$id));
			}else{
				$this->session->set_flashdata('error', 'Nota dinas gagal diperbaharui.');
				redirect(site_url('nota/edit_revisi/'.$id));
			}			
		}		
	}
	
	public function edit_no()
	{
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_nota_kepada'));
		$this->data['categories'] = $this->mm_categories->get();				
		$this->data['process'] = $this->mm_categories->get_process();
		$this->data['users_nota_klasifikasi'] = $this->mm_nota_kepada->get_klasifikasi();
		$this->data['users_nota_kepada'] = $this->mm_nota_kepada->get_kepada();
		$this->data['users_nota_dari'] = $this->mm_nota_kepada->get_dari();
		$this->data['users_nota_pengesahan'] = $this->mm_nota_kepada->get_pengesahan();
		$this->data['users_nota_tembusan'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_paraf'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_inisial'] = $this->mm_nota_kepada->get_tembusan();


		$nota_id = intval( $this->uri->segment(3) );
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$nota = $this->mm_nota->get_detail_nota( $nota_id, $this->data['userInfo']['uID'] );
		if( ! $nota )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}					
		
		if( $nota['PROCESS_STATUS'] != NOTA_DRAFT && $nota['PROCESS_STATUS'] != NOTA_EDIT && $nota['PROCESS_STATUS'] != NOTA_FINAL)
		{			
			
			$this->session->set_flashdata('error', $this->data['config']['msg_doc_on_track']);
			redirect( site_url('nota/detail/'.$nota_id) );
		}

		$this->data['records'] = $nota;
		$this->data['penandatangan'] = $this->mm_nota->get_penandatangan($nota_id);
		//$this->data['files'] = $this->mm_nota->get_version_files($nota_id);
		$this->data['layout'] = $this->folder.'e_no';

		if( ! empty($_POST) )
		{
			$this->form_validation->set_rules('no_surat', 'No Surat', 'required|callback_no_check');			

			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('layout', $this->data);
			}
			else
			{
				$success = $this->mm_nota->update_no( $this->data['userInfo']['uID'] );
				$this->session->set_flashdata('success', 'Nota dinas berhasil diperbaharui.');
				redirect( site_url('nota/detail/'.$nota_id) );
			}
		}
		else
		{
			$this->load->view('layout', $this->data);
		}
	}
	
	public function no_check($str)
	{
		// pengecekan duplikasi data
		$sql = "SELECT COUNT(*) JML FROM V_NOTA 
			WHERE PK_NOTA_ID <> ? AND UPPER(NO_SURAT) IN 
			(
				SELECT UPPER(?) || SUBSTR(NO_SURAT, INSTR(NO_SURAT, '/')) 
				FROM V_NOTA WHERE PK_NOTA_ID = ?
			)";
		$nota_id = $this->input->post('nota_id');
		$query = $this->db->query($sql, array($nota_id, sanitize_filename($str), $nota_id));
		$result = $query->result_array();
		// echo ($result[0]['JML']);
		if ($result[0]['JML'] > 0) {
			$this->form_validation->set_message('no_check', 'Kolom %s berisi data yang sudah terdaftar (Duplikasi Data)');
			return false; // error, duplikasi data
		}
		return true;
	}
	
	public function add()
	{
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_nota_kepada'));
		$this->data['categories'] = $this->mm_categories->get();
		//$this->data['users'] = $this->mm_users->get();
		$this->data['process'] = $this->mm_categories->get_process();
        $this->data['users_nota_klasifikasi'] = $this->mm_nota_kepada->get_klasifikasi();
		$this->data['users_nota_kepada'] = $this->mm_nota_kepada->get_kepada();
		$this->data['users_nota_dari'] = $this->mm_nota_kepada->get_dari();
		$this->data['users_nota_pengesahan'] = $this->mm_nota_kepada->get_pengesahan();
		$this->data['users_nota_tembusan'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_paraf'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['users_nota_inisial'] = $this->mm_nota_kepada->get_tembusan();
		$this->data['layout'] = $this->folder.'a';
		$this->load->view('layout', $this->data);		
	}
	
	public function insert()
	{			
		$this->form_validation->set_rules('kepada[]', 'Kepada', 'required|max_length[100]');
		$this->form_validation->set_rules('dari', 'Dari', 'required');
		$this->form_validation->set_rules('hal', 'Hal', 'required|max_length[100]');
		$this->form_validation->set_rules('tembusan1[]', 'Tembusan', 'required|max_length[100]');                        
		$this->form_validation->set_rules('tempat', 'Tempat', 'required|max_length[50]');
		$this->form_validation->set_rules('pengesahan_1', 'Pengesahan Kanan', 'required');		
		$this->form_validation->set_rules('desc', 'Isi', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->add();
		}
		else
		{
			//PRINT_R($_POST);EXIT();
			$insertID = $this->mm_nota->insert_nota( $this->data['userInfo']['uID'] );
			//$data['id'] = $insertID;				
			$this->session->set_flashdata('success', 'Nota Dinas berhasil dibuat.');			
			redirect( site_url('nota/edit/'.$insertID) );
		}		
	}

	public function dist()
	{

		$doc_status = $this->input->post('dS');
		$nota_id = $this->input->post('dI');
		$nota_message =	'';
		$version_id = 0;
		$data['error'] = 0;
		
		if( !$doc_status || !$nota_id )
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		$sql = "SELECT * FROM H_DOCUMENTS_PROCESS WHERE FK_DOCUMENTS_ID = ? AND PROCESS_STATUS = ? AND FK_TYPE_ID = 2";
		$query = $this->db->query($sql, array($nota_id, $doc_status));

		if($query->num_rows()==0)
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		if($data['error'] == 0)
		{
			$this->load->model('mm_inbox');
			$clone = $this->mm_inbox->clone_to_approval_nota($nota_id, 1);
			
			$documents_process = array(
				'PROCESS_STATUS' => DOC_REVIEW,
				'CURRENT_LAYER' => 1, // initial layer always 1
				'VERSION_ID' => 0,
				'UDT' => date('Y-m-d H:i:s')
			);
			
			$this->db->where('FK_DOCUMENTS_ID', $nota_id);
			$this->db->where('FK_TYPE_ID', 2);
			$this->db->update('H_DOCUMENTS_PROCESS', $documents_process);
			
			/************************************
			Kirim email untuk penandatangan 
			**********************************/
			
			$this->db->where('PK_NOTA_ID',$nota_id);
			$row = $this->db->get('V_NOTA')->row();
			
			$this->db->where('EMPLOYEE_NO',$row->CREATE_BY);
			$row_employee = $this->db->get('V_EMPLOYEE')->row();
			
			$nota_message = $this->mm_nota_email->get_approval($row->NO_SURAT, $row->HAL, $row_employee->EMPLOYEE_NAME.' ('.$row_employee->E_MAIL_ADDR.')');

			$values = array(                               
				'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
				'V_TO' => $row->PENGESAHAN_EMAIL1,
				'V_CC' => '',
				'V_BCC' =>  '',
				'V_SUBJECT' => 'Info Nota (ID:'.$row->PK_NOTA_ID.')',
				'V_MESSAGE' => $nota_message
			);
			$result = oci8_send_email($values);
			
			$data['response'] = '';
			if ($result != '1') {
				$data['response'] .= '<pre>'.$result.'</pre>';
			}
			$data['response'] .= 'Dokumen sudah disosialisasikan kepada para user.';
			$data['error'] = 0;
		}
		echo json_encode($data);
	}
	
	public function commit()
	{
		$doc_status = $this->input->post('dS');
		$nota_id = $this->input->post('dI');		
		$version_id = $this->input->post('vI');
		$data['error'] = 0;
		
		if( !$doc_status || !$nota_id)
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		$sql = "SELECT * FROM H_DOCUMENTS_PROCESS WHERE FK_DOCUMENTS_ID = ? AND PROCESS_STATUS = ? AND FK_TYPE_ID = 2";
		$query = $this->db->query($sql, array($nota_id, $doc_status));
		//print_r($query);exit();
		if($query->num_rows()==0)
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		if($data['error'] == 0)
		{			
			
			$documents_process = array(
				'PROCESS_STATUS' => DOC_REVIEW,				
				'VERSION_ID' => $version_id,
				'UDT' => date('Y-m-d H:i:s')
			);
			
			$this->db->where('FK_DOCUMENTS_ID', $nota_id);
			$this->db->where('FK_TYPE_ID', 2);
			$this->db->update('H_DOCUMENTS_PROCESS', $documents_process);
			
			$this->db->where('FK_DOCUMENTS_ID',$nota_id);
			$this->db->where('FK_TYPE_ID',2);
			$row = $this->db->get('H_DOCUMENTS_PROCESS')->row();			
			
			$this->load->model('mm_inbox_nota');
			$this->mm_inbox_nota->clone_to_approval_nota( $nota_id, $row->CURRENT_LAYER );
			
			// TODO create email notification to all user in step DD
			
			/************************************
			Kirim email untuk penandatangan 
			**********************************/
			
			$this->db->where('PK_NOTA_ID',$nota_id);
			$row = $this->db->get('V_NOTA')->row();
			
			$this->db->where('EMPLOYEE_NO',$row->CREATE_BY);
			$row_employee = $this->db->get('V_EMPLOYEE')->row();
			
			$nota_message = $this->mm_nota_email->get_approval($row->NO_SURAT, $row->HAL, $row_employee->EMPLOYEE_NAME.' ('.$row_employee->E_MAIL_ADDR.')');

			$sql = "SELECT                
				H_DOCUMENTS_STEP.EMPLOYEE_NO,
				V_EMPLOYEE.EMPLOYEE_NAME,
				V_EMPLOYEE.E_MAIL_ADDR
				FROM H_DOCUMENTS_PROCESS
					INNER JOIN H_DOCUMENTS_STEP 
						ON H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
						AND H_DOCUMENTS_STEP.FK_TYPE_ID = H_DOCUMENTS_PROCESS.FK_TYPE_ID
					INNER JOIN V_EMPLOYEE
						ON H_DOCUMENTS_STEP.EMPLOYEE_NO = V_EMPLOYEE.EMPLOYEE_NO
				WHERE H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID = ? AND
				H_DOCUMENTS_PROCESS.FK_TYPE_ID = 2 AND                
				H_DOCUMENTS_STEP.STEP_LAYER = H_DOCUMENTS_PROCESS.CURRENT_LAYER";
			$query = $this->db->query($sql, array($nota_id));
			$row_pengesahan = $query->row();

			$values = array(                               
				'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
				'V_TO' => $row_pengesahan->E_MAIL_ADDR,
				'V_CC' => '',
				'V_BCC' =>  '',
				'V_SUBJECT' => 'Info Nota (ID:'.$row->PK_NOTA_ID.')',
				'V_MESSAGE' => $nota_message
			);
			$result = oci8_send_email($values);
			
			$data['response'] = '';
			if ($result != '1') {
				$data['response'] .= '<pre>'.$result.'</pre>';
			}
			
			// $nota_message = 0;
			// $this->db->where('PK_NOTA_ID',$nota_id);
			// $row = $this->db->get('T_NOTA')->row();
			// if($row->PENGESAHAN1 != 0){
				// $this->db->where('EMPLOYEE_NO',$row->PENGESAHAN1);
				// $row_employee1 = $this->db->get('V_EMPLOYEE')->row();
			
				// $nota_message .= 'ada Nota dinas dengan judul '.$row->HAL.' yang datanya telah diperbaharui, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

				// $documents = array(                               
					// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
					// 'V_TO' => $row_employee1->E_MAIL_ADDR,
					// 'V_CC' => '',
					// 'V_BCC' =>  '',
					// 'V_SUBJECT' => 'Info Nota',
					// 'V_BODY' => $nota_message
				// );
				// $sql = $this->db->insert('T_EMAIL', $documents);	
			
			// }
			
			// if($row->PENGESAHAN2 != 0){
				// $this->db->where('EMPLOYEE_NO',$row->PENGESAHAN2);
				// $row_employee2 = $this->db->get('V_EMPLOYEE')->row();
				
				// $nota_message .= 'ada Nota dinas dengan judul '.$row->HAL.' yang datanya telah diperbaharui, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

				// $documents = array(                               
					// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
					// 'V_TO' => $row_employee2->E_MAIL_ADDR,
					// 'V_CC' => '',
					// 'V_BCC' =>  '',
					// 'V_SUBJECT' => 'Info Nota',
					// 'V_BODY' => $nota_message
				// );
				// $sql = $this->db->insert('T_EMAIL', $documents);	
			// }
			
			// if($row->PENGESAHAN3 != 0){
				// $this->db->where('EMPLOYEE_NO',$row->PENGESAHAN3);
				// $row_employee3 = $this->db->get('V_EMPLOYEE')->row();
				
				// $nota_message .= 'ada Nota dinas dengan judul '.$row->HAL.' yang datanya telah diperbaharui, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

				// $documents = array(                               
					// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
					// 'V_TO' => $row_employee3->E_MAIL_ADDR,
					// 'V_CC' => '',
					// 'V_BCC' =>  '',
					// 'V_SUBJECT' => 'Info Nota',
					// 'V_BODY' => $nota_message
				// );
				// $sql = $this->db->insert('T_EMAIL', $documents);	
			// }
										
			$data['response'] .= 'Dokumen yang direvisi telah disosialisasikan kembali.';
			$data['error'] = 0;
			
			// start : fungsi update approval untuk proses pararel - NOTA DINAS TIDAK ADA PROSES PARAREL
			// $sql = "SELECT
					// DBDOC.H_DOCUMENTS_APPROVAL.PK_DOCUMENTS_APPROVAL_ID,
					// DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
					// DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
					// DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
					// DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
					// DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS
				// FROM
					// DBDOC.H_DOCUMENTS_APPROVAL
				// WHERE DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 2 
					// AND DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = $nota_id
					// AND	DBDOC.H_DOCUMENTS_APPROVAL.FK_TYPE_ID = 2";
			
			// $row = $this->db->query($sql)->result_array();							
			// foreach($row as $key){
				// $user_id = $key['EMPLOYEE_NO'];	
				// $this->db->set('APPROVAL_STATUS',2);
				// $this->db->where('APPROVAL_STATUS',0);
				// $this->db->where('FK_TYPE_ID', 2);
				// $this->db->where('FK_DOCUMENTS_ID',$nota_id);
				// $this->db->where('EMPLOYEE_NO',$user_id);
				// $this->db->update('H_DOCUMENTS_APPROVAL');
			// }
			// end : fungsi update approval untuk proses pararel
		}
		echo json_encode($data);
	}
	
	public function upload()
	{
            // fungsi ini tidak digunakan, by heru - 2015/01/16
		return $this->mm_nota->insert_attachment();
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

    public function cek_no_surat(){
		$result = $this->mm_nota->cek_no_surat($this->input->post('no_surat'));
		echo $result[0]['jumlah'];
    }
	
	/* NEW FUNC */
	public function detail()
	{
		$nota_id = intval( $this->uri->segment(3) );		
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$nota = $this->mm_nota->get_detail_nota( $nota_id, $this->data['userInfo']['uID'] );		
		$this->data['pnota1'] = $this->mm_nota->get_detail_pengesahan1( $nota_id);
		$this->data['pnota2'] = $this->mm_nota->get_detail_pengesahan2( $nota_id);
		$this->data['pnota3'] = $this->mm_nota->get_detail_pengesahan3( $nota_id);		
		if( ! $nota )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $nota;
		$this->data['comments'] = FALSE;
		if($nota['PROCESS_STATUS']!=DOC_DRAFT)
		{
			$this->data['comments'] = $this->mm_nota->get_comments($nota_id);
			$this->data['versioning'] = $this->mm_nota->get_versioning($nota_id);				
		}else{
			$this->data['comments'] = '';
			$this->data['versioning'] = '';
		}
		
		$this->data['is_step_final'] = ( ($nota['CURRENT_LAYER'] != ACTION_FINAL) ? TRUE : FALSE );
		//$data['files'] = $this->mm_nota->get_files($nota_id);	
		$this->data['layout'] = $this->folder.'d';
		$this->load->view('layout', $this->data);
	}
	
	public function d_dist()
	{
		$nota_id = intval( $this->uri->segment(3) );		
		$key_str = urldecode( $this->uri->segment(4) );		
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$nota = $this->mm_nota->get_detail_nota( $nota_id, $this->data['userInfo']['uID'] );
		if( ! $nota )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $nota;		
		
		$ex_dis = explode(',', $nota['KEPADA_TEXT']);
		foreach ($ex_dis as $k=>$v) {
			if ($k == $key_str) {
				unset($ex_dis[$k]);
			}
		}
		// $index = array_search($dist_str,$ex_dis);
		// if($index !== FALSE){		
		// unset($ex_dis[$index]);
		// }		
		// $ex_dis = array_values($ex_dis);		
		$array_dist = implode(",", $ex_dis);
		
		$data_dist = array(	
			'KEPADA_TEXT' => $this->mm_nota->clean_delimiter_text($array_dist)	
		);
				
		$this->db->where('PK_NOTA_ID', $nota_id);
		$this->db->update('T_NOTA', $data_dist);
		
		if( $nota['PROCESS_STATUS'] == NOTA_EDIT )				
		{
			redirect( site_url('nota/edit_revisi/'.$nota_id) );
		}else{
			redirect( site_url('nota/edit/'.$nota_id) );
		}
	}
	
	public function d_dist_tmb()
	{
		$nota_id = intval( $this->uri->segment(3) );		
		$key_str = urldecode( $this->uri->segment(4) );		
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$nota = $this->mm_nota->get_detail_nota( $nota_id, $this->data['userInfo']['uID'] );
		if( ! $nota )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $nota;		
		
		$ex_dis = explode(',', $nota['TEMBUSAN_TEXT']);
		foreach ($ex_dis as $k=>$v) {
			if ($k == $key_str) {
				unset($ex_dis[$k]);
			}
		}
		// $index = array_search($dist_str,$ex_dis);
		// if($index !== FALSE){		
		// unset($ex_dis[$index]);
		// }		
		// $ex_dis = array_values($ex_dis);		
		$array_dist = implode(",", $ex_dis);
		
		$data_dist = array(	
			'TEMBUSAN_TEXT' => $this->mm_nota->clean_delimiter_text($array_dist)	
		);
				
		$this->db->where('PK_NOTA_ID', $nota_id);
		$this->db->update('T_NOTA', $data_dist);
		
		if( $nota['PROCESS_STATUS'] == NOTA_EDIT )				
		{
			redirect( site_url('nota/edit_revisi/'.$nota_id) );
		}else{
			redirect( site_url('nota/edit/'.$nota_id) );
		}
	}	
	
	public function send_email()
	{
		$nota_id = intval( $this->input->post('nota_id') );		
		$sql = "
			SELECT 
				T1.KEPADA, T1.PK_NOTA_ID, T1.NO_SURAT, T1.HAL, T2.EMPLOYEE_NO, T2.EMPLOYEE_NAME, T2.E_MAIL_ADDR
			FROM
				V_NOTA T1, V_EMPLOYEE T2
			WHERE ',' || T1.KEPADA LIKE '%,' || T2.EMPLOYEE_NO || '%' 
				AND T1.PK_NOTA_ID = ?";
		
		$query = $this->db->query($sql, array($nota_id));
		if($query)
		{
			$rows = $query->result_array();
			
			$data['response'] = '';
			foreach ($rows as $k=>$row) {
				$nota_message = $this->mm_nota_email->get_broadcast($row['NO_SURAT'], $row['HAL']);

				$values = array(                               
					'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
					'V_TO' => $row['E_MAIL_ADDR'],
					'V_CC' => '',
					'V_BCC' =>  '',
					'V_SUBJECT' => 'Info Nota (ID:'.$row['PK_NOTA_ID'].')',
					'V_MESSAGE' => $nota_message,
					'V_ATTACH_TYPE' => 'application/pdf',
					'V_ATTACH_NAME' => 'Nota_Dinas.pdf',
					'V_ATTACH_CLOB' => file_get_contents(base_url()."generate_pdf/nota/$nota_id")
				);
				$result = oci8_send_email($values);			
				if ($result != '1') {
					$data['response'] .= '<pre>'.$result.'</pre>';
				}				
			}
			$data['response'] .= 'Email telah dikirim.';
			$data['error'] = 0;
			$data['result'] = $result;
			echo json_encode($data);
			return;
		}
		$data['response'] = 'Email gagal dikirim.';
		$data['error'] = 1;
		$data['result'] = $result;
		echo json_encode($data);
				
	}

}