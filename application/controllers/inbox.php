<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends CI_Controller 
{
	var $folder = 'inbox/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->load->model('mm_inbox');
		$this->load->model('mm_documents');
		$this->load->model('mm_inbox_nota');
		$this->load->model('mm_nota');
		$this->load->model('mm_documents_email');
		$this->load->helper('oci8_helper');
		$this->load->library('pagination_bas', '', 'pg_nota');
		$this->load->library('pagination_bas', '', 'pg_doc');
		$this->setter();

		// Document Procedure 		
        $this->field_doc=array(
            array('field'=>'PK_DOCUMENTS_ID', 'label'=>'ID', 'attribut'=>array('class'=>'form-control', 'style'=>'width:40px')),
            array('field'=>'DOCUMENTS_TITLE', 'label'=>'Judul', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'VERSION_DTL', 'label'=>'Versi', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'PROCESS_STATUS_DTL', 'label'=>'Status', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'CURRENT_LAYER_DTL', 'label'=>'Proses Berjalan', 'attribut'=>array('class'=>'form-control', 'style'=>'width:110px')),
            array('field'=>'APPROVAL_STATUS_DTL', 'label'=>'Respon', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'CREATE_BY_NAME', 'label'=>'Penyusun', 'attribut'=>array('class'=>'form-control', 'style'=>'width:170px')),
            array('field'=>'DOCUMENTS_DATEPUB', 'label'=>'Tgl. Publikasi', 'attribut'=>array('class'=>'form-control', 'style'=>'width:70px')),
            array('field'=>'DOCUMENTS_CDT', 'label'=>'Tgl. Buat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:110px'))
        );
	
	$this->field_doc[0]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == DOC_EDIT) 
	? "<a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['PK_DOCUMENTS_ID']}</a>"
	: "<a href=\"".site_url('inbox/detail/'    .\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['PK_DOCUMENTS_ID']}</a>";
EOD;
	$this->field_doc[1]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == DOC_EDIT) 
	? "<span><a href=\"".site_url('documents/detail/'.\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['DOCUMENTS_TITLE']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>"
	: "<span><a href=\"".site_url('inbox/detail/'    .\$value['PK_DOCUMENTS_ID'])."\" title=\"detail\">{\$value['DOCUMENTS_TITLE']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
		$this->field_doc[5]['script'] = <<<EOD
\$this->ci->data['config']['act_status_icon'][ \$value['APPROVAL_STATUS'] ][1].' '.\$value['APPROVAL_STATUS_DTL'] ;
EOD;
		$this->field_doc[6]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

		$this->pg_doc->set_component_id('pg_doc');
        $this->pg_doc->set_field($this->field_doc);

		
		// Nota Dinas
        $this->field_nota=array(
            array('field'=>'PK_NOTA_ID', 'label'=>'No', 'attribut'=>array('class'=>'form-control', 'style'=>'width:70px')),
            array('field'=>'HAL', 'label'=>'Judul', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'PROCESS_STATUS_DTL', 'label'=>'Status', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'CURRENT_LAYER_DTL', 'label'=>'Proses Berjalan', 'attribut'=>array('class'=>'form-control', 'style'=>'width:150px')),
            array('field'=>'APPROVAL_STATUS_DTL', 'label'=>'Respon', 'attribut'=>array('class'=>'form-control', 'style'=>'width:100px')),
            array('field'=>'CREATE_BY_NAME', 'label'=>'Penyusun', 'attribut'=>array('class'=>'form-control', 'style'=>'width:200px')),
            array('field'=>'CREATE_DATE', 'label'=>'Tgl. Buat', 'attribut'=>array('class'=>'form-control', 'style'=>'width:120px'))
        );
		
		$this->field_nota[0]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == NOTA_EDIT) 
	? "<a href=\"".site_url('nota/detail/'      .\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['PK_NOTA_ID']}</a>" 
	: "<a href=\"".site_url('inbox_nota/detail/'.\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['PK_NOTA_ID']}</a>";
EOD;
		$this->field_nota[1]['script'] = <<<EOD
(\$value['PROCESS_STATUS'] == NOTA_EDIT) 		
	? "<span><a href=\"".site_url('nota/detail/'      .\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['HAL']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>"
	: "<span><a href=\"".site_url('inbox_nota/detail/'.\$value['PK_NOTA_ID'])."\" title=\"detail\">{\$value['HAL']}</a></span><br>
	   <span class=\"font-disabled\">{\$value['CATEGORIES_TITLE']}</span>";
EOD;
		$this->field_nota[4]['script'] = <<<EOD
\$this->ci->data['config']['act_status_icon'][ \$value['APPROVAL_STATUS'] ][1].' '.\$value['APPROVAL_STATUS_DTL'] ;
EOD;
		$this->field_nota[5]['script'] = <<<EOD
"{\$value['CREATE_BY_NAME']} <br>
<span class=\"font-disabled\">({\$value['E_MAIL_ADDR']})</span>";
EOD;

		$this->pg_nota->set_component_id('pg_nota');
        $this->pg_nota->set_field($this->field_nota);
		
	}
	
	public function index()
	{		
        $this->pg_doc->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_doc->set_ajax_url(site_url().'inbox/search_doc');
        $this->data['doc_inbox'] = $this->pg_doc->generate_all();
		
        $this->pg_nota->set_attr_table('class="table table-bordered table-condensed table-hover table-striped"');
        $this->pg_nota->set_ajax_url(site_url().'inbox/search_nota');
        $this->data['nota_inbox'] = $this->pg_nota->generate_all();
		
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
    public function search_doc($page){
        $this->pg_doc->set_table('V_INBOX_DOC');
		$this->pg_doc->set_where(array('INBOX_OWNER'=>$this->data['userInfo']['uID']));
        $this->pg_doc->set_paging($_POST,10,$page);
        $this->pg_doc->generate_table_data();
    }
	
    public function search_nota($page){
        $this->pg_nota->set_table('V_INBOX_NOTA');
		$this->pg_nota->set_where(array('INBOX_OWNER'=>$this->data['userInfo']['uID']));
        $this->pg_nota->set_paging($_POST,10,$page);
        $this->pg_nota->generate_table_data();
    }
	
	public function detail()
	{
		$doc_id = intval( $this->uri->segment(3) );
		
		if( ! $doc_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$documents = $this->mm_inbox->get_detail_pengesahan( $doc_id, $this->data['userInfo']['uID'] );
		if( ! $documents )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
				
			
		$this->data['records'] = $documents;
		$this->data['pro_type'] = '';
		$this->data['penandatangan'] = $this->mm_documents->get_penandatangan($doc_id);
		$this->data['versioning'] = $this->mm_documents->get_versioning($doc_id);	
		$this->data['comments'] = $this->mm_documents->get_comments($doc_id);
		$this->data['vers_row'] = $this->mm_documents->get_versioning_rows($doc_id);	
		//print_r($this->data['vers_row']);exit();
		$this->data['vers_min'] = $this->mm_documents->get_versioning_min($doc_id);	
		// approval action ability
		$this->data['make_approval'] = FALSE;
		//echo $documents['PROCESS_STATUS'];exit();
		if( $documents['PROCESS_STATUS'] > DOC_EDIT ) {
			$this->data['make_approval'] = $this->mm_inbox->check_is_make_approval($documents);
		}
		//print_r($documents);exit();
		$this->data['is_step_final'] = ( ($documents['CURRENT_LAYER'] != ACTION_FINAL) ? TRUE : FALSE );
		//$this->data['files'] = $this->mm_documents->get_files($doc_id);			
		$this->data['layout'] = $this->folder.'d';
		$this->load->view('layout', $this->data);
	}
	
	public function action()
	{
		if( ! empty($_POST) )
		{
			$this->form_validation->set_error_delimiters('<span>', '</span><br>');
			
			$this->form_validation->set_rules('dI', 'Judul', 'required');//doc id
			$this->form_validation->set_rules('sL', 'Judul', 'required');//step layer
			$this->form_validation->set_rules('vI', 'Judul', 'required');//version id
			$this->form_validation->set_rules('cL', 'Judul', 'required');//curr layer
			$this->form_validation->set_rules('status', 'Status', '');//approval status			
			
			if( $this->input->post('status') <= ACTION_READ && $this->input->post('sL') == $this->input->post('cL') )
			{
				$this->form_validation->set_rules('respon', 'Respon Anda', 'required');
			}
			
			if ($this->form_validation->run() == FALSE)
			{
				$return['response'] = validation_errors();
				$return['error'] = 1;
				echo json_encode($return);
			}
			else
			{				
				$return = array('error'=>0,'response'=>'', 'message'=>'');
				$doc_id = $this->input->post('dI');
				$step_layer = $this->input->post('sL');
				$version_id = $this->input->post('vI');
				$current_layer = $this->input->post('cL');
				$approval_status = $this->input->post('status');
				$action = $this->input->post('respon');
				$timestamp = date('Y-m-d H:i:s');
				$new_version_id = $version_id + 1;
				$total_step_layer = count($this->data['config']['step_layer']);
				$make_comments = ( ($step_layer==$current_layer) ? TRUE : FALSE );
				$make_comments = ( ($action > 1) ? TRUE : FALSE );
					
				// Langkah Pertama: Simpan Approval Status ke H_DOCUMENTS_APPROVAL
				if( $make_comments )
				{
					$dataApproval = array('APPROVAL_STATUS'=>$action, 'APPROVAL_UDT'=>$timestamp);
										
					$this->db->where('FK_DOCUMENTS_ID', $doc_id);					
					$this->db->where('FK_TYPE_ID', 1);					
					$this->db->where('STEP_LAYER', $current_layer);
					$this->db->where('VERSION_ID', $version_id);
					$this->db->where('EMPLOYEE_NO', $this->data['userInfo']['uID']);
					$this->db->update('H_DOCUMENTS_APPROVAL', $dataApproval);					
				}
				
				$data = array(
					'dI' => $doc_id,
					'vI' => $version_id,
					'cL' => $current_layer,
					'comment' => sanitize_filename($this->input->post('comment')),
					'uID' => $this->data['userInfo']['uID'],
					'timestamp' => $timestamp,
                    'sL' => $step_layer
				);				
				
				// Langkah Kedua: Simpan Komentar
				$this->mm_inbox->insert_comment($data);

				// Langkah Ketiga: Hitung Berapa Total yg harus di Respon, Total Approve, & Total Reject
				$approval = $this->mm_inbox->count_approve($data);				
				$approve = intval($approval['approve']);
				$reject = intval($approval['reject']);
				$total = intval($approval['total']);
				
				// Langkah Keempat :
					// - Untuk Proses Seri : 
						// - Update Process Status=Revisi jika ada yg reject.
						// - Layer menjadi Next Layer jika user sudah approve semua.
						// - Jika Next Layer > Total Step Layer maka: Update Process Status=Doc Final.
						// - Jika Next Layer <= Total Step Layer maka: Update Next Layer.
				if ($this->input->post('pT')==0) {
					if ($reject > 0) {
						$table = array(
							'PROCESS_STATUS' => DOC_EDIT,
							'VERSION_ID' => $new_version_id,
							'UDT' => $timestamp
						);
						$this->db->where('FK_DOCUMENTS_ID', $doc_id);												
						$this->db->where('FK_TYPE_ID', 1);
						$this->db->update('H_DOCUMENTS_PROCESS', $table);
						
						$dokumen_message = $this->mm_documents_email->get_revisi($doc_id);
						$values = array(                               
							'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
							'V_TO' => $this->mm_documents_email->email_penyusun,
							'V_CC' => '',
							'V_BCC' =>  '',
							'V_SUBJECT' => 'Info Dokumen (ID:'.$doc_id.')',
							'V_MESSAGE' => $dokumen_message
						);
						$result = oci8_send_email($values);
						
						$return['message'] = '';
						if ($result != "1") {
							$return['message'] .= '<pre>'.$result.'</pre>';
						}
						$return['message'] .= 'Reject, Raised Version.<br>';
						$return['temp'] = $values;						
					} else {
						$next_layer = ($total == $approve) ? $current_layer + 1 : $current_layer;
						
						if ($next_layer > $total_step_layer) {
							$table = array(
								'PROCESS_STATUS' => DOC_FINAL,
								'CURRENT_LAYER' => ACTION_FINAL,
								'UDT' => $timestamp
							);
							$this->db->where('FK_DOCUMENTS_ID', $doc_id);												
							$this->db->where('FK_TYPE_ID', 1);
							$this->db->update('H_DOCUMENTS_PROCESS', $table);
							
							$dokumen_message = $this->mm_documents_email->get_selesai($doc_id);
							$values = array(                               
								'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
								'V_TO' => $this->mm_documents_email->email_penyusun,
								'V_CC' => '',
								'V_BCC' =>  '',
								'V_SUBJECT' => 'Info Dokumen (ID:'.$doc_id.')',
								'V_MESSAGE' => $dokumen_message
							);
							$result = oci8_send_email($values);
							
							$return['message'] = '';
							if ($result != "1") {
								$return['message'] .= '<pre>'.$result.'</pre>';
							}							
							$return['message'] .= 'Dokumen Telah Mencapai Final.<br>';
						} else {
							$table = array(
								'CURRENT_LAYER' => $next_layer,
								'UDT' => $timestamp
							);
							$this->db->where('FK_DOCUMENTS_ID', $doc_id);												
							$this->db->where('FK_TYPE_ID', 1);
							$this->db->update('H_DOCUMENTS_PROCESS', $table);
							
							$this->mm_inbox->clone_to_approval($doc_id);
							$dokumen_message = $this->mm_documents_email->get_approval($doc_id);
							$rows = $this->mm_documents_email->get_email_to($doc_id);
							
							$return['message'] = '';
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
								if ($result != "1") {
									$return['message'] .= '<pre>'.$result.'</pre>';
								}
							}
							
							if ($next_layer == $current_layer) {
								$return['message'] .= 'Approve, Wait Other User to Respond <br>';
							} else {
								$return['message'] .= 'Approved.<br>';
							}
						}
					}
				}

				// Langkah Keempat :
					// - Untuk Proses Pararel : 
						// - Update Process Status=Revisi jika ada yg reject dan jika user sudah merespon semua.
						// - Layer menjadi Next Layer jika user sudah approve semua.
						// - Jika Next Layer > Total Step Layer maka: Update Process Status=Doc Final.
						// - Jika Next Layer <= Total Step Layer maka: Update Next Layer.
				if ($this->input->post('pT')==1) {
				
					$info_action = '';
					if ($action == 2) $info_action = 'Approve';
					if ($action == 3) $info_action = 'Reject';
					
					if ($reject > 0 && (($approve + $reject) == $total)) {
						$table = array(
							'PROCESS_STATUS' => DOC_EDIT,
							'VERSION_ID' => $new_version_id,
							'UDT' => $timestamp
						);
						$this->db->where('FK_DOCUMENTS_ID', $doc_id);												
						$this->db->where('FK_TYPE_ID', 1);
						$this->db->update('H_DOCUMENTS_PROCESS', $table);
						
						$dokumen_message = $this->mm_documents_email->get_revisi($doc_id);
						$values = array(                               
							'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
							'V_TO' => $this->mm_documents_email->email_penyusun,
							'V_CC' => '',
							'V_BCC' =>  '',
							'V_SUBJECT' => 'Info Dokumen (ID:'.$doc_id.')',
							'V_MESSAGE' => $dokumen_message
						);
						$result = oci8_send_email($values);
						
						$return['message'] = '';
						if ($result != "1") {
							$return['message'] .= '<pre>'.$result.'</pre>';
						}						
						$return['message'] .= $info_action . ', Raised Version <br>';
					} else {
						$next_layer = ($total == $approve) ? $current_layer + 1 : $current_layer;
						if ($next_layer > $total_step_layer) {
							$table = array(
								'PROCESS_STATUS' => DOC_FINAL,
								'CURRENT_LAYER' => ACTION_FINAL,
								'UDT' => $timestamp
							);
							$this->db->where('FK_DOCUMENTS_ID', $doc_id);												
							$this->db->where('FK_TYPE_ID', 1);
							$this->db->update('H_DOCUMENTS_PROCESS', $table);
							
							$dokumen_message = $this->mm_documents_email->get_selesai($doc_id);
							$values = array(                               
								'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
								'V_TO' => $this->mm_documents_email->email_penyusun,
								'V_CC' => '',
								'V_BCC' =>  '',
								'V_SUBJECT' => 'Info Dokumen (ID:'.$doc_id.')',
								'V_MESSAGE' => $dokumen_message
							);
							$result = oci8_send_email($values);
							
							$return['message'] = '';
							if ($result != "1") {
								$return['message'] .= '<pre>'.$result.'</pre>';
							}						
							$return['message'] .= 'Dokumen Telah Mencapai Final.<br>';
						} else {
							$table = array(
								'CURRENT_LAYER' => $next_layer,
								'UDT' => $timestamp
							);
							$this->db->where('FK_DOCUMENTS_ID', $doc_id);												
							$this->db->where('FK_TYPE_ID', 1);
							$this->db->update('H_DOCUMENTS_PROCESS', $table);
							
							if ($next_layer == $current_layer) {
								$return['message'] = $info_action . ', Wait Other User to Respond <br>';
							} else {
								$this->mm_inbox->clone_to_approval($doc_id);
								$dokumen_message = $this->mm_documents_email->get_approval($doc_id);
								$rows = $this->mm_documents_email->get_email_to($doc_id);
								
								$return['message'] = '';
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
									if ($result != "1") {
										$return['message'] .= '<pre>'.$result.'</pre>';
									}
								}
							
								$return['message'] .= 'Approved.<br>';
							}
						}
					}
				}
						
if (empty($this->input->post('comment'))) {
								$return['message'] .= 'Anda tidak mengisi kotak saran. Terima kasih.';

}else{
				$return['message'] .= 'Saran Anda telah disampaikan. Terima kasih.';
}							
				$return['error'] = 0;
				// $return['response'] = $data;
				echo json_encode($return);
				
			} // end validation
		}
		else
		{
			return FALSE;
		}
		
	}
	
	public function test($doc_id, $version_id, $step_layer)
	{
				
		$data = array(
			'dI' => $doc_id,
			'vI' => $version_id,
			'sL' => $step_layer
		);
		$approval = $this->mm_inbox->count_approve($data);
		
		$approve = intval($approval['approve']);
		$reject = intval($approval['reject']);
		$total = intval($approval['total']);
		

		if( $approve > 0 && $reject > 0 )
		{
			if( ($approve + $reject) == $total )
			{
				print_r('all reject - ($approve + $reject) == $total');
				print_r('approve = '.$approve.' reject = '.$reject.', total = '.$total);
				echo '<br>';
			}
		}
		else
		{

			if( $approve == $total && $reject == 0 )
			{
				print_r('approve = '.$approve.', total = '.$total.' all approve');
				echo '<br>';
			}
			
			if( $reject == $total && $approve == 0 )
			{
				print_r('reject = '.$reject.', total = '.$total.' all reject - $reject == $total');
				echo '<br>';
			}		
		
		}
		
		print_r('just commenting');
		echo '<br>';
		print_r($approval);
		exit;
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

}