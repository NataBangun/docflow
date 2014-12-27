<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documents extends CI_Controller 
{
	var $folder = 'documents/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->load->model('mm_documents');
		$this->load->model('mm_inbox');
		$this->load->model('mm_documents_email');
		$this->load->helper('oci8_helper');
		$this->load->library('pagination_bas', '', 'pg_doc1');
		$this->load->library('pagination_bas', '', 'pg_doc2');
		$this->setter();
		
		// Document Procedure 		
        $this->field_doc=array(
            array('field'=>'PK_DOCUMENTS_ID', 'label'=>'ID', 'attribut'=>array('class'=>'form-control', 'style'=>'width:50px')),
            array('field'=>'DOCUMENTS_NO', 'label'=>'Nomor', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'DOCUMENTS_TITLE', 'label'=>'Judul', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'VERSION_DTL', 'label'=>'Versi', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'PROCESS_STATUS_DTL', 'label'=>'Status', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'CREATE_BY_NAME', 'label'=>'Penyusun', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'DOCUMENTS_DATEPUB', 'label'=>'Tgl. Publikasi', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px')),
            array('field'=>'DOCUMENTS_CDT', 'label'=>'Tgl. Buat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:80px'))
        );
		
		$this->field_doc[0]['script'] = <<<EOD
"<a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['PK_DOCUMENTS_ID']}</a>";
EOD;
		$this->field_doc[1]['script'] = <<<EOD
"<a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['DOCUMENTS_NO']}</a>";
EOD;
		$this->field_doc[2]['script'] = <<<EOD
"<span>{\$value['DOCUMENTS_TITLE']}</span><br>
<span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
		$this->field_doc[5]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

		$this->pg_doc1->set_component_id('pg_doc1');
        $this->pg_doc1->set_field($this->field_doc);

		$this->pg_doc2->set_component_id('pg_doc2');
        $this->pg_doc2->set_field($this->field_doc);
	}
	
	public function index()
	{
		// $this->data['records'] = $this->mm_documents->get_all( $this->session->userdata('uID') );
		
        $this->pg_doc1->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_doc1->set_ajax_url(site_url().'documents/search_doc1');
        $this->data['daftar_doc1'] = $this->pg_doc1->generate_all();

        $this->pg_doc2->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_doc2->set_ajax_url(site_url().'documents/search_doc2');
        $this->data['daftar_doc2'] = $this->pg_doc2->generate_all();
		
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
    public function search_doc1($page){
        $this->pg_doc1->set_table('V_DAFTAR_DOC');
		if (in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature')))) {
			$where = array('PROCESS_STATUS <'=>DOC_FINAL);
		} else {
			$where = array('PROCESS_STATUS <'=>DOC_FINAL, 'DOCUMENTS_CBY'=>$this->data['userInfo']['uID']);
		}
		$this->pg_doc1->set_where($where);
        $this->pg_doc1->set_paging($_POST,10,$page);
        $this->pg_doc1->generate_table_data();
    }
	
    public function search_doc2($page){
        $this->pg_doc2->set_table('V_DAFTAR_DOC');
		if (in_array("Admin Buspro", explode("|", $this->session->userdata('umc_feature')))) {
			$where = array('PROCESS_STATUS'=>DOC_FINAL);
		} else {
			$where = array('PROCESS_STATUS'=>DOC_FINAL, 'DOCUMENTS_CBY'=>$this->data['userInfo']['uID']);
		}
		$this->pg_doc2->set_where($where);
        $this->pg_doc2->set_paging($_POST,10,$page);
        $this->pg_doc2->generate_table_data();
    }
	
	public function detail()
	{
		$this->load->model(array('mm_categories', 'mm_users'));
		$doc_id = intval( $this->uri->segment(3) );		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
		if( ! $documents )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $documents;
		$this->data['process'] = $this->mm_documents->get_process_by_cat($doc_id);
		$this->data['penandatangan'] = $this->mm_documents->get_penandatangan($doc_id);		
		$this->data['versioning'] = FALSE;
		$this->data['comments'] = FALSE;
		if($documents['PROCESS_STATUS']!=DOC_DRAFT)
		{
			$this->data['versioning'] = $this->mm_documents->get_versioning($doc_id);	
			$this->data['comments'] = $this->mm_documents->get_comments($doc_id);
		}
		
		$this->data['is_step_final'] = ( ($documents['CURRENT_LAYER'] != ACTION_FINAL) ? TRUE : FALSE );
		//$this->data['files'] = $this->mm_documents->get_files($doc_id);	
		$this->data['layout'] = $this->folder.'d';
		$this->load->view('layout', $this->data);
	}
	
	public function edit()
	{
		
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_categories', 'mm_users'));
		$this->data['categories'] = $this->mm_categories->get();
		$this->data['num'] = $this->mm_categories->get_num();
		$this->data['process'] = $this->mm_categories->get_process();
		$this->data['users'] = $this->mm_users->get_sign();
		
		$name = array();	
		foreach($this->data['users'] as $key=>$val)
		{
			$name[] = $val['EMPLOYEE_NAME'].' ('.$val['EMPLOYEE_NO'].')';
		}		
		$this->data['name'] = implode(',', $name);
		
		
		$doc_id = intval( $this->uri->segment(3) );
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
		if( ! $documents )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		if( $documents['PROCESS_STATUS'] != DOC_DRAFT && $documents['PROCESS_STATUS'] != DOC_EDIT )
		{	
										
				if( $documents['PROCESS_STATUS'] != DOC_DRAFT && $documents['PROCESS_STATUS'] != DOC_EDIT && $documents['PROCESS_STATUS'] != DOC_FINAL)
				{			
					$this->session->set_flashdata('error', $this->data['config']['msg_doc_on_track']);
					
				}
				elseif( $documents['PROCESS_STATUS'] == DOC_FINAL )				
				{
					$this->session->set_flashdata('error', $this->data['config']['msg_doc_final']);
				}					
				
				
			redirect( site_url('documents/detail/'.$doc_id) );
		}
		elseif( $documents['PROCESS_STATUS'] == DOC_EDIT )				
		{
			redirect( site_url('documents/edit_revisi/'.$doc_id) );
		}
		
			
		$this->data['records'] = $documents;
		$this->data['penandatangan'] = $this->mm_documents->get_penandatangan($doc_id);		
		//$this->data['files'] = $this->mm_documents->get_version_files($doc_id);
		$this->data['layout'] = $this->folder.'e';
		$this->load->view('layout', $this->data);
	}
	
	public function update()
	{
		//print_r($_POST);exit();
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[50]');
		$this->form_validation->set_rules('no', 'No', 'required|max_length[50]|callback_no_check');	
		$this->form_validation->set_rules('versi[]', 'versi', 'required|numeric|max_length[3]');
		$this->form_validation->set_rules('categories', 'Kategori', 'required');			
		$this->form_validation->set_rules('datepub', 'Tanggal terbit', 'required');			
		
		$id = $this->uri->segment(3);		
		// if($this->input->post('dist_name') == 0 || $this->input->post('dist_name') == ''){
			// $this->form_validation->set_rules('distribution[]', 'Distribusi', 'required');	
		// }
		
		if ($this->form_validation->run() == FALSE)
		{
			
			$this->edit($id);
		}
		else
		{
			//print_r($_POST);exit();
			$update = $this->mm_documents->update_documents( $this->data['userInfo']['uID'] );			
			$this->session->set_flashdata('success', 'Dokumen berhasil diperbaharui.');
			redirect(site_url('documents/edit/'.$id));
		}
	}	
	
	public function edit_revisi()
	{
		$data['response'] = $this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_categories', 'mm_users'));
		$this->data['categories'] = $this->mm_categories->get();
		$this->data['num'] = $this->mm_categories->get_num();
		$this->data['process'] = $this->mm_categories->get_process();
		$this->data['users'] = $this->mm_users->get_sign();
		
		$name = '';	
		foreach($this->data['users'] as $key=>$val)
		{
			$name .= $val['EMPLOYEE_NAME'].'('.$val['EMPLOYEE_NO'].')'.',';
		}		
		$this->data['name'] = $name;
		
		$doc_id = intval( $this->uri->segment(3) );		
		
		$documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );

		$this->data['records'] = $documents;
		$this->data['penandatangan'] = $this->mm_documents->get_penandatangan($doc_id);		
		//$this->data['files'] = $this->mm_documents->get_version_files($doc_id);
		$this->data['layout'] = $this->folder.'e_revisi';
		$this->load->view('layout', $this->data);
		
	}
	
	public function update_revisi()
	{
		//print_r($_POST);exit();
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[50]');
		$this->form_validation->set_rules('no', 'No', 'required|max_length[50]|callback_no_check');	
		$id = $this->uri->segment(3);		
		// if($this->input->post('dist_name') == 0 || $this->input->post('dist_name') == ''){
			// $this->form_validation->set_rules('distribution[]', 'Distribusi', 'required');	
		// }
		
		if ($this->form_validation->run() == FALSE)
		{
			
			$this->edit_revisi($id);
		}
		else
		{
			//print_r($_POST);exit();
			$update = $this->mm_documents->update_documents_revision( $this->data['userInfo']['uID'] );			
			$this->session->set_flashdata('success', 'Dokumen berhasil diperbaharui.');
			redirect(site_url('documents/edit_revisi/'.$id));
		}
	}	
	
	public function add()
	{
		$this->form_validation->set_error_delimiters('<span>', '</span><br>');
		$this->load->model(array('mm_categories', 'mm_users'));
		$this->data['categories'] = $this->mm_categories->get();
		$this->data['num'] = $this->mm_categories->get_num();
		$this->data['process'] = $this->mm_categories->get_process();
		$name = '';
		$this->data['users'] = $this->mm_users->get_sign();	
		foreach($this->data['users'] as $key=>$val)
		{
			$name .= $val['EMPLOYEE_NAME'].'('.$val['EMPLOYEE_NO'].')'.',';
		}		
		$this->data['name'] = $name;
		$this->data['layout'] = $this->folder.'a';	
		$this->load->view('layout', $this->data);
	}
	
	public function insert()
	{			
		//print_r($_POST);exit();
		$this->form_validation->set_rules('title', 'Judul', 'required|max_length[50]');
		$this->form_validation->set_rules('no', 'No', 'required|max_length[50]|callback_no_check');
		$this->form_validation->set_rules('versi[]', 'versi', 'required|numeric|max_length[3]');		
		$this->form_validation->set_rules('penandatangan1[]', 'Penandatangan', 'required');		
		$this->form_validation->set_rules('penandatangan2[]', 'Penandatangan', 'required');		
		$this->form_validation->set_rules('penandatangan3[]', 'Penandatangan', 'required');		
		$this->form_validation->set_rules('penandatangan4[]', 'Penandatangan', 'required');		
		$this->form_validation->set_rules('categories', 'Kategori', 'required');
		$this->form_validation->set_rules('distribution[]', 'Distribusi', 'required|max_length[50]');			
		
		if ($this->form_validation->run() == FALSE)
		{			
			$this->add();
		}
		else
		{
			$insertID = $this->mm_documents->insert_documents( $this->session->userdata('uID') );						
			if($insertID)
			{
				$this->session->set_flashdata('success', 'Dokumen berhasil dibuat.');
				redirect(site_url('documents/edit/'.$insertID));
			}else{
				$this->session->set_flashdata('error', 'Dokumen gagal dibuat, ulangi proses dengan benar.');
				redirect(site_url('documents/add'));
			}			
		
		}
	}
	
	public function no_check($str) 
	{
		// pengecekan karakter terlarang
		$invalidChar = '\\/:*?"<>|';		
		for ($idx=0; $idx<strlen($str); $idx++) {
			if (strpos($invalidChar, $str[$idx]) === false) {
				// OK, check next character
			} else {
				$this->form_validation->set_message('no_check', 'Kolom %s berisi karakter terlarang \''.$invalidChar.'\'');
				return false;
			}
		}
		// pengecekan duplikasi data
		if ($this->input->post('documents_id') > 0) {
			// ketika update
			$sql = "SELECT COUNT(*) JML FROM T_DOCUMENTS WHERE DOCUMENTS_NO = ? AND PK_DOCUMENTS_ID <> ?";
			$query = $this->db->query($sql, array($str, $this->input->post('documents_id')));
		} else {
			// ketika insert
			$sql = "SELECT COUNT(*) JML FROM T_DOCUMENTS WHERE DOCUMENTS_NO = ?";
			$query = $this->db->query($sql, array($str));
		}
		$result = $query->result_array();
		if ($result[0]['JML'] > 0) {
			$this->form_validation->set_message('no_check', 'Kolom %s berisi data yang sudah terdaftar (Duplikasi Data)');
			return false; // error, duplikasi data
		}
		return true;
	}
	
	public function dist()
	{
		$doc_status = $this->input->post('dS');
		$doc_id = $this->input->post('dI');		
		$version_id = $this->input->post('vI');
		$data['error'] = 0;
		
		if( !$doc_status || !$doc_id || !$version_id)
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		$sql = "SELECT * FROM H_DOCUMENTS_PROCESS WHERE FK_DOCUMENTS_ID = ? AND FK_TYPE_ID = 1 AND PROCESS_STATUS = ?";
		$query = $this->db->query($sql, array($doc_id, $doc_status));

		if($query->num_rows()==0)
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		if($data['error'] == 0)
		{
			$this->load->model('mm_inbox');
			
			$documents_process = array(
				'PROCESS_STATUS' => DOC_REVIEW,
				'CURRENT_LAYER' => 1, // initial layer always 1 
				'VERSION_ID' => $version_id,
				'UDT' => date('Y-m-d H:i:s')
			);
			
			$this->db->where('FK_DOCUMENTS_ID', $doc_id);
			$this->db->where('FK_TYPE_ID', 1);
			$this->db->update('H_DOCUMENTS_PROCESS', $documents_process);
			
			// clone_to_approval dijalankan setelah update status H_DOCUMENTS_PROCESS, 
			// karena parameter CURRENT_LAYER sekarang diambil/dijoin dari table H_DOCUMENTS_PROCESS
			$clone = $this->mm_inbox->clone_to_approval($doc_id);

			// TODO create email notification to all user in step DD
			$dokumen_message = $this->mm_documents_email->get_approval($doc_id);
			$rows = $this->mm_documents_email->get_email_to($doc_id);
			
			$data['response'] = '';
			foreach ($rows as $row) {	
				$values = array(                               
					'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
					'V_TO' => $row->E_MAIL_ADDR,
					'V_CC' => '',
					'V_BCC' =>  '',
					'V_SUBJECT' => 'Info Dokumen (ID:'.$doc_id.')',
					'V_MESSAGE' => $dokumen_message
				);
				$result = oci8_send_email($values);
				if ($result != '1') {
					$data['response'] .= '<pre>'.$result.'</pre>';
				}
			}
			
			$data['response'] .= 'Dokumen sudah disosialisasikan kepada para user.';
			$data['error'] = 0;
		}
		echo json_encode($data);
	}	
	
	public function test()
	{
		$id = $this->uri->segment(3);
		$this->db->where('FK_DOCUMENTS_ID',$id);
		$this->db->where('STEP_LAYER',1);
		$data = $this->db->get('H_DOCUMENTS_STEP')->result_array();		
		
		// $this->db->where('',1);
		// $data = $this->db->get('H_DOCUMENTS_STEP')->result_array();		
		
		print_r($data);
		
		foreach($data as $key=>$val)
		{
			$this->db->where('EMPLOYEE_NO',$val['EMPLOYEE_NO']);
			$row_employee = $this->db->get('V_EMPLOYEE')->row_array();
			echo $row_employee['E_MAIL_ADDR'];
		}
	}

	
	public function d_dist()
	{
		$doc_id = intval( $this->uri->segment(3) );		
		$key_str = urldecode( $this->uri->segment(4) );		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
		if( ! $documents )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $documents;		
		
		$ex_dis = explode(',', $documents['DOCUMENTS_DISTRIBUTION']);
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
			'DOCUMENTS_DISTRIBUTION' => $array_dist	
		);
				
		$this->db->where('PK_DOCUMENTS_ID', $doc_id);
		$this->db->update('T_DOCUMENTS', $data_dist);
		
		if( $documents['PROCESS_STATUS'] == DOC_EDIT )				
		{
			redirect( site_url('documents/edit_revisi/'.$doc_id) );
		}else{
			redirect( site_url('documents/edit/'.$doc_id) );
		}
	}
	
	public function d_lampiran()
	{
		$doc_id = intval( $this->uri->segment(3) );		
		$dist_str = intval( $this->uri->segment(4) );		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
		if( ! $documents )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $documents;		
		
		$ex_dis = explode(',', $documents['DOCUMENTS_ATC_NAME']);
		$index = array_search($dist_str,$ex_dis);
		
		if($index !== FALSE){		
		unset($ex_dis[$index]);
		@unlink(realpath('uploads/lampiran_dokpro'.$ex_dis[$index]));
		}		
		
		$ex_dis = array_values($ex_dis);
		
		$array_dist = implode(",", $ex_dis);			
		
		$data_dist = array(	
			'DOCUMENTS_ATC_NAME' => $array_dist	
		);
				
		$this->db->where('PK_DOCUMENTS_ID', $doc_id);
		$this->db->update('T_DOCUMENTS', $data_dist);
		
		if( $documents['PROCESS_STATUS'] == DOC_EDIT )				
		{
			redirect( site_url('documents/edit_revisi/'.$doc_id) );
		}else{
			redirect( site_url('documents/edit/'.$doc_id) );
		}
	}
	
	public function commit()
	{
		$doc_status = $this->input->post('dS');
		$doc_id = $this->input->post('dI');		
		$version_id = $this->input->post('vI');
		$data['error'] = 0;
		
		if( !$doc_status || !$doc_id || !$version_id)
		{
			$data['response'] = 'Data tidak ditemukan';
			$data['error'] = 1;
		}
		
		$sql = "SELECT * FROM H_DOCUMENTS_PROCESS WHERE FK_DOCUMENTS_ID = ? AND FK_TYPE_ID = 1 AND PROCESS_STATUS = ?";
		$query = $this->db->query($sql, array($doc_id, $doc_status));
		
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
			
			$this->db->where('FK_DOCUMENTS_ID', $doc_id);
			$this->db->where('FK_TYPE_ID', 1);
			$this->db->update('H_DOCUMENTS_PROCESS', $documents_process);
			// TODO create email notification to all user in step DD

			// clone_to_approval dijalankan setelah update status H_DOCUMENTS_PROCESS, 
			// karena parameter CURRENT_LAYER sekarang diambil/dijoin dari table H_DOCUMENTS_PROCESS
			$clone = $this->mm_inbox->clone_to_approval($doc_id);

			// TODO create email notification to all user in step DD
			$dokumen_message = $this->mm_documents_email->get_approval($doc_id);
			$rows = $this->mm_documents_email->get_email_to($doc_id);
			
			$data['response'] = '';
			foreach ($rows as $row) {	
				$values = array(                               
					'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
					'V_TO' => $row->E_MAIL_ADDR,
					'V_CC' => '',
					'V_BCC' =>  '',
					'V_SUBJECT' => 'Info Dokumen (ID:'.$doc_id.')',
					'V_MESSAGE' => $dokumen_message
				);
				$result = oci8_send_email($values);
				if ($result != '1') {
					$data['response'] .= '<pre>'.$result.'</pre>';
				}
			}
						
			$data['response'] .= 'Dokumen yang direvisi telah disosialisasikan kembali.';
			$data['error'] = 0;
			
			// $sql="SELECT
			// DBDOC.H_DOCUMENTS_APPROVAL.PK_DOCUMENTS_APPROVAL_ID,
			// DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
			// DBDOC.H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
			// DBDOC.H_DOCUMENTS_APPROVAL.VERSION_ID,
			// DBDOC.H_DOCUMENTS_APPROVAL.STEP_LAYER,
			// DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS
			// FROM
			// DBDOC.H_DOCUMENTS_APPROVAL
			// WHERE
			// DBDOC.H_DOCUMENTS_APPROVAL.APPROVAL_STATUS = 2
			// AND
			// DBDOC.H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = $doc_id";
			
			// $row = $this->db->query($sql)->result_array();							
			// foreach($row as $key){
			// $user_id = $key['EMPLOYEE_NO'];	
			// $this->db->set('APPROVAL_STATUS',2);
			// $this->db->where('APPROVAL_STATUS',0);
			// $this->db->where('FK_DOCUMENTS_ID',$doc_id);
			// $this->db->where('EMPLOYEE_NO',$user_id);
			// $this->db->update('H_DOCUMENTS_APPROVAL');
			// }						
			
			
		}
		echo json_encode($data);
	}
	
	public function upload()
	{
		$upload = $this->mm_documents->insert_attachment();
		if($upload){
			$this->session->set_flashdata('success', 'File berhasil diupload.');
				redirect(site_url('documents/edit/'.$this->input->post('documents_id')));
		}else{
			$this->session->set_flashdata('error', 'File gagal diupload.');
				redirect(site_url('documents/edit/'.$this->input->post('documents_id')));
		}
	}
	
	
	public function search()
	{
		if( ! empty($_POST) )
		{
			$this->data['records'] = $this->mm_documents->get_search($this->session->userdata('uID'));
			$this->data['layout'] = $this->folder.'v';
			$this->load->view('layout', $this->data);
		}
	}
	
	//test 	
	
	public function webinfo()
	{
		$doc_id = intval( $this->uri->segment(3) );		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		redirect(site_url('generate_doc_pro/testerMM/'.$doc_id));
		
	}
	
	public function d()
	{
		$id = $this->uri->segment(3);
		$this->db->where('PK_DOCUMENTS_ID',$id);
		$this->db->delete('T_DOCUMENTS');
		$this->db->where('FK_DOCUMENTS_ID',$id);
		$this->db->delete('H_DOCUMENTS_APPROVAL');	
		$this->db->where('FK_DOCUMENTS_ID',$id);
		$this->db->delete('H_DOCUMENTS_ATTACHMENT');	
		$this->db->where('FK_DOCUMENTS_ID',$id);
		$this->db->delete('H_DOCUMENTS_STEP');
		$this->db->where('FK_DOCUMENTS_ID',$id);
		$this->db->delete('H_DOCUMENTS_COMMENTS');	
		$this->db->where('FK_DOCUMENTS_ID',$id);
		$this->db->delete('H_DOCUMENTS_PROCESS');	
		echo 'done';
	}
	
	public function preview()
	{
		$doc_id = intval( $this->uri->segment(3) );		
		$process_id = intval( $this->uri->segment(4) );		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$sql = "select * from t_digital_certificate where fk_documents_process_id = ?";
		$query = $this->db->query($sql, array($process_id));
		$row = $query->row_array();
		
		if (isset($row['FILE_PDF_SIGNED']) && is_file($row['FILE_PDF_SIGNED'])) {
			redirect(site_url('signed_pdf/index/'.$process_id));
		} else {		
			redirect(site_url('generate_doc_pro/testerMM/'.$doc_id));
		}
		
	}
	
	public function view()
	{
		$doc_id = intval( $this->uri->segment(3) );		
		$process_id = intval( $this->uri->segment(4) );		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}			
				
		redirect(site_url('generate_doc_pro2/testerMM/'.$doc_id));
		
		
	}
	
	/* Setter */
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();
		$this->data['service'] = 0;
		//$this->data['service'] = $this->mm_service->main( $this->data['userInfo']['uID'] );
		$this->data['header'] = 'header';
		$this->data['footer'] = 'footer';
		return $this->data;
	}

}