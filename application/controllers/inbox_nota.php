<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox_nota extends CI_Controller 
{
	var $folder = 'inbox_nota/';
	var $data=array();
	
	function __construct()
	{
		parent::__construct();
		$this->mm_session->islogin();
		$this->load->model('mm_inbox_nota');
		$this->load->model('mm_nota');
		$this->load->helper('oci8_helper');
		$this->load->model('mm_nota_email');	
		$this->setter();
	}
	
	public function index()
	{
		/*******************
		Funsi untuk menampilkan list inbox nota
		***********************/
		$this->data['records'] = $this->mm_inbox_nota->get_inbox( $this->data['userInfo']['uID'] );
		$this->data['revisi'] = $this->mm_nota->get_all( $this->data['userInfo']['uID'] );		
		$this->data['layout'] = $this->folder.'v';
		$this->load->view('layout', $this->data);
	}
	
	public function detail()
	{		
		/*******************
		Funsi untuk menampilkan detail dari inbox nota
		***********************/
		
		$nota_id = intval( $this->uri->segment(3) );
		
		if( ! $nota_id )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
		
		$documents = $this->mm_inbox_nota->get_detail( $nota_id, $this->data['userInfo']['uID'] );
		if( ! $documents )
		{
			$this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			redirect(404);
		}
			
		$this->data['records'] = $documents;
		$this->data['penandatangan'] = $this->mm_nota->get_penandatangan($nota_id);
		$this->data['pnota1'] = $this->mm_nota->get_detail_pengesahan1( $nota_id);
		$this->data['pnota2'] = $this->mm_nota->get_detail_pengesahan2( $nota_id);
		$this->data['pnota3'] = $this->mm_nota->get_detail_pengesahan3( $nota_id);		
		$this->data['versioning'] = $this->mm_nota->get_versioning($nota_id);	
		$this->data['comments'] = $this->mm_nota->get_comments($nota_id);
		//$this->data['vers_row'] = $this->mm_nota->get_versioning_rows($nota_id);	
		//$this->data['vers_min'] = $this->mm_nota->get_versioning_min($nota_id);	
		// approval action ability
		$this->data['make_approval'] = FALSE;
		if( $documents['PROCESS_STATUS'] > DOC_EDIT ) {
			$this->data['make_approval'] = $this->mm_inbox_nota->check_is_make_approval($documents);
		}
		$this->data['is_step_final'] = ( ($documents['CURRENT_LAYER'] != ACTION_FINAL) ? TRUE : FALSE );
		//$this->data['files'] = $this->mm_nota->get_files($doc_id);			
		$this->data['layout'] = $this->folder.'d';
		$this->load->view('layout', $this->data);
	}
	
	public function action()
	{
		/*******************
		Funsi untuk melakukan respon pada nota
		***********************/
		if( ! empty($_POST) )
		{
			$this->form_validation->set_error_delimiters('<span>', '</span><br>');
			
			$this->form_validation->set_rules('dI', 'Judul', 'required');//doc id
			$this->form_validation->set_rules('sL', 'Judul', 'required');//step layer
			$this->form_validation->set_rules('vI', 'Judul', 'required');//version id
			$this->form_validation->set_rules('cL', 'Judul', 'required');//curr layer
			$this->form_validation->set_rules('status', 'Status', '');//approval status			
			if ($this->input->post('respon') == 3) {
				$this->form_validation->set_rules('comment', 'Isi Saran', 'required');
			}
			
			if( $this->input->post('status') <= ACTION_READ && $this->input->post('sL') == $this->input->post('cL') )
			{
				$this->form_validation->set_rules('respon', 'Respon Anda', 'required');
			}
			
			if ($this->form_validation->run() == FALSE)
			{
				$return['message'] = validation_errors();
				$return['error'] = 1;
				echo json_encode($return);
			}
			else
			{				
				$return = array('error'=>0,'response'=>'', 'message'=>'');
				$nota_id = $this->input->post('dI');
				$step_layer = $this->input->post('sL');				
				$version_id = $this->input->post('vI');
				$mail_messages = '';
				$current_layer = $this->input->post('cL');
				$approval_status = $this->input->post('status');
				$action = $this->input->post('respon');
				$timestamp = date('Y-m-d H:i:s');
				$new_version_id = $version_id + 1;
				$total_step_layer = $this->input->post('ttl');
				$make_comments = ( ($step_layer==$current_layer) ? TRUE : FALSE );
				$make_comments = ( ($action > 1) ? TRUE : FALSE );
					
				/*******************
				untuk melakukan respon pada nota
				***********************/
				if( $make_comments )
				{
					$dataApproval = array('APPROVAL_STATUS'=>$action, 'APPROVAL_UDT'=>$timestamp);
					$this->db->where('FK_DOCUMENTS_ID', $nota_id);
					$this->db->where('VERSION_ID', $version_id);					
					$this->db->where('EMPLOYEE_NO', $this->data['userInfo']['uID']);
					$this->db->where('FK_TYPE_ID', 2);
					$this->db->update('H_DOCUMENTS_APPROVAL', $dataApproval);
				}
				
				$data = array(
					'dI' => $nota_id,
					'vI' => $version_id,
					'cL' => $current_layer,
					'comment' => $this->input->post('comment'),
					'uID' => $this->data['userInfo']['uID'],
					'timestamp' => $timestamp
				);
				
				$approval = $this->mm_inbox_nota->count_approve($data);				
				$approve = intval($approval['approve']);
				$reject = intval($approval['reject']);
				$total = intval($approval['total']);	
				// print_r($approval);
				// print_r($_POST);exit();
				if( $approve > 0 && $reject > 0 )
				{				
				
					/*******************
					apabila respon reject, untuk nota dinas tidak ada proses pararel
					***********************/
					
					// if( ($approve + $reject) == 1 )
					// {
						// $return['message'] = 'reject + raise version <br>';
						// $table = array(
							// 'PROCESS_STATUS' => 2,
							// 'VERSION_ID' => $new_version_id,
							// 'UDT' => $timestamp
						// );

						// $this->db->where('FK_DOCUMENTS_ID', $nota_id);												
						// $this->db->where('FK_TYPE_ID', 2);												
						// $this->db->update('H_DOCUMENTS_PROCESS', $table);
						// $this->mm_inbox_nota->clone_to_approval_nota( $nota_id, $current_layer);
						
						// /************************************
						// Kirim email untuk penyusun 
						// **********************************/

						// $this->db->where('PK_NOTA_ID',$nota_id);
						// $row = $this->db->get('T_NOTA')->row();

						// $this->db->where('EMPLOYEE_NO',$row->CREATE_BY);
						// $row_employee = $this->db->get('V_EMPLOYEE')->row();				

						// $mail_messages .= 'ada Nota dinas dengan judul '.$row->HAL.' telah direspon <b>Ditolak/Reject</b>, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

						// $documents = array(                               
							// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
							// 'V_TO' => $row_employee->E_MAIL_ADDR,
							// 'V_CC' => '',
							// 'V_BCC' =>  '',
							// 'V_SUBJECT' => 'Info Nota',
							// 'V_BODY' => $mail_messages
						// );
						// $sql = $this->db->insert('T_EMAIL', $documents);	
					// }
				}
				else
				{
					/*******************
					apabila respon approve
					***********************/
					if( $approve == 1 )
					{
						$table = array(
							'CURRENT_LAYER' => ($current_layer+1), // important
							'UDT' => $timestamp
						);
						
						/*******************
						fungsi cek dokumen final atau belum
						***********************/
						
						$is_doc_final = ( ($total_step_layer == $current_layer) ? TRUE : FALSE );
							
						if( $is_doc_final )
						{
							/************************************
							apabila final lakukan update 
							pada CURRENT_LAYER = 99 , PROCESS_STATUS = 4							
							**********************************/
							
							$table['CURRENT_LAYER'] = ACTION_FINAL;
							$table['PROCESS_STATUS'] = DOC_FINAL;
						} else {
							/************************************
							 bila belum final, insert step baru
							**********************************/
							
							$this->mm_inbox_nota->clone_to_approval_nota( $nota_id, $table['CURRENT_LAYER'] );
						}
						$this->db->where('FK_DOCUMENTS_ID', $nota_id);
						$this->db->where('FK_TYPE_ID', 2);
						$this->db->update('H_DOCUMENTS_PROCESS', $table);
												
						if( $is_doc_final )
						{							
							/************************************
							Kirim email untuk penyusun 
							**********************************/

							$this->db->where('PK_NOTA_ID',$nota_id);
							$row = $this->db->get('V_NOTA')->row();

							$this->db->where('EMPLOYEE_NO',$row->CREATE_BY);
							$row_employee = $this->db->get('V_EMPLOYEE')->row();				

							$nota_message = $this->mm_nota_email->get_selesai($row->NO_SURAT, $row->HAL, $row_employee->EMPLOYEE_NAME.' ('.$row_employee->E_MAIL_ADDR.')');

							$values = array(                               
								'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
								'V_TO' => $row_employee->E_MAIL_ADDR,
								'V_CC' => '',
								'V_BCC' =>  '',
								'V_SUBJECT' => 'Info Nota (ID:'.$row->PK_NOTA_ID.')',
								'V_MESSAGE' => $nota_message
							);
							$result = oci8_send_email($values);
			
							// $mail_messages .= 'ada Nota dinas dengan judul '.$row->HAL.' telah <b>Selesai direspon</b>, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

							// $documents = array(                               
								// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
								// 'V_TO' => $row_employee->E_MAIL_ADDR,
								// 'V_CC' => '',
								// 'V_BCC' =>  '',
								// 'V_SUBJECT' => 'Info Nota',
								// 'V_BODY' => $mail_messages
							// );
							// $sql = $this->db->insert('T_EMAIL', $documents);	
							
						}
						else
						{
							/************************************
							Kirim email untuk penandatangan selanjutnya 
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
			
							// $this->db->where('PK_NOTA_ID',$nota_id);
							// $row = $this->db->get('T_NOTA')->row();
							
							// if($next == 2)
							// {
								// $this->db->where('EMPLOYEE_NO',$row->PENGESAHAN2);
							// }
							// if($next == 3)
							// {
								// $this->db->where('EMPLOYEE_NO',$row->PENGESAHAN3);
							// }
							
							// $row_employee = $this->db->get('V_EMPLOYEE')->row();				

							// $mail_messages .= 'ada Nota dinas dengan judul '.$row->HAL.' telah direspon <b>Ditolak/Reject</b>, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

							// $documents = array(                               
								// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
								// 'V_TO' =>$row_employee->E_MAIL_ADDR,
								// 'V_CC' => '',
								// 'V_BCC' =>  '',
								// 'V_SUBJECT' => 'Info Nota',
								// 'V_BODY' => $mail_messages
							// );
							// $sql = $this->db->insert('T_EMAIL', $documents);
						}
						
						if ($result != '1') {
							$return['message'] .= '<pre>'.$result.'</pre>'; 
						}
						$return['message'] .= 'approve + next layer <br>';
					}
					
					/*******************
					apabila respon reject
					***********************/
					
					if( $reject == 1 )
					{
						$table = array(
							'PROCESS_STATUS' => 2,
							'VERSION_ID' => $new_version_id,
							'UDT' => $timestamp
						);

						$this->db->where('FK_DOCUMENTS_ID', $nota_id);
						$this->db->where('FK_TYPE_ID', 2);
						$this->db->update('H_DOCUMENTS_PROCESS', $table);
						
						// proses clone_to_approval_nota di pindah ke class nota->commit 
						// $this->mm_inbox_nota->clone_to_approval_nota( $nota_id, $current_layer );
						
						/************************************
						Kirim email untuk penyusun 
						**********************************/

						$this->db->where('PK_NOTA_ID',$nota_id);
						$row = $this->db->get('V_NOTA')->row();

						$this->db->where('EMPLOYEE_NO',$row->CREATE_BY);
						$row_employee = $this->db->get('V_EMPLOYEE')->row();				

						$nota_message = $this->mm_nota_email->get_revisi($row->NO_SURAT, $row->HAL, $row_employee->EMPLOYEE_NAME.' ('.$row_employee->E_MAIL_ADDR.')');

						$values = array(                               
							'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
							'V_TO' => $row_employee->E_MAIL_ADDR,
							'V_CC' => '',
							'V_BCC' =>  '',
							'V_SUBJECT' => 'Info Nota (ID:'.$row->PK_NOTA_ID.')',
							'V_MESSAGE' => $nota_message
						);
						$result = oci8_send_email($values);
							
						// $this->db->where('PK_NOTA_ID',$nota_id);
						// $row = $this->db->get('T_NOTA')->row();

						// $this->db->where('EMPLOYEE_NO',$row->CREATE_BY);
						// $row_employee = $this->db->get('V_EMPLOYEE')->row();				

						// $mail_messages .= 'ada Nota dinas dengan judul '.$row->HAL.' telah direspon <b>Ditolak/Reject</b>, silakan login ke aplikasi <a href="'.base_url().'">klik disini</a>';

						// $documents = array(                               
							// 'V_SENDER' => 'suryo.hartanto@lintasarta.co.id',
							// 'V_TO' => $row_employee->E_MAIL_ADDR,
							// 'V_CC' => '',
							// 'V_BCC' =>  '',
							// 'V_SUBJECT' => 'Info Nota',
							// 'V_BODY' => $mail_messages
						// );
						// $sql = $this->db->insert('T_EMAIL', $documents);
						
						if ($result != '1') {
							$return['message'] .= '<pre>'.$result.'</pre>'; 
						}
						$return['message'] .= 'reject + raise version <br>';
					}		
				
				}				
				
				$this->mm_inbox_nota->insert_comment($data);
				$return['message'] .= 'Saran Anda telah disampaikan.';
				
				$return['error'] = 0;
				$return['response'] = $data;
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
		$approval = $this->mm_inbox_nota->count_approve($data);
		
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