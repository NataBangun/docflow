<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mm_nota extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('oci8_helper');
	}

	/*
	*  process = 1-4 [ draft, edit, review, publication ]
	*/
	public function get_all($users_id, $process=FALSE)
	{
		$return = FALSE;
                
		$sql = "SELECT
				V_NOTA.PK_NOTA_ID,
				V_NOTA.NO_SURAT,
				V_NOTA.KLASIFIKASI,
				V_NOTA.KEPADA,
				V_NOTA.TEMPAT,
				V_NOTA.TANGGAL_NOTA,
				V_NOTA.DARI,
				V_NOTA.HAL,
				V_NOTA.DESKRIPSI,
				V_NOTA.CREATE_BY,
				V_NOTA.CREATE_DATE,
				H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
				H_DOCUMENTS_PROCESS.PROCESS_STATUS,
				H_DOCUMENTS_PROCESS.CURRENT_LAYER,
				H_DOCUMENTS_PROCESS.UDT,
				H_DOCUMENTS_PROCESS.FK_TYPE_ID
			FROM V_NOTA
				INNER JOIN H_DOCUMENTS_PROCESS ON V_NOTA.PK_NOTA_ID = H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
			WHERE H_DOCUMENTS_PROCESS.FK_TYPE_ID = 2 
				AND V_NOTA.CREATE_BY = ? ";
                
		// if($process)
		// {
			// $sql .= "AND V_NOTA.PROCESS_STATUS = $process";
		// }

		$sql .= "ORDER BY PK_NOTA_ID DESC";

		$query = $this->db->query($sql, array($users_id));

		if($query)
		{
			$return = $query->result_array();
		}

		return $return;
	}
        
    public function get_detail($nota_id)
	{
		$sql = "SELECT *				
				FROM
				V_NOTA
				WHERE
				PK_NOTA_ID = ?";
		
		$query = $this->db->query($sql, array($nota_id));
		
		if($query)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_detail_nota($nota_id)
	{
		$sql = "SELECT
				DBDOC.T_NOTA.PK_NOTA_ID,
				DBDOC.T_NOTA.NO_SURAT,
				DBDOC.T_NOTA.TANGGAL_NOTA,
				DBDOC.T_NOTA.KEPADA,
				DBDOC.T_NOTA.KEPADA_TEXT,
				DBDOC.T_NOTA.DARI,
				DBDOC.T_NOTA.HAL,
				DBDOC.T_NOTA.DESKRIPSI,
				DBDOC.T_NOTA.PENGESAHAN1,
				DBDOC.T_NOTA.PENGESAHAN2,
				DBDOC.T_NOTA.PENGESAHAN3,
				DBDOC.T_NOTA.CREATE_BY,
				DBDOC.T_NOTA.CREATE_DATE,
				DBDOC.T_NOTA.UPDATE_BY,
				DBDOC.T_NOTA.UPDATE_DATE,
				DBDOC.T_NOTA.FK_KLASIFIKASI_ID,
				DBDOC.T_NOTA.PEMBUAT_KONSEP,
				DBDOC.T_NOTA.TEMPAT,
				DBDOC.T_NOTA.TEMBUSAN,
				DBDOC.T_NOTA.TEMBUSAN_TEXT,
				DBDOC.T_NOTA.LAMPIRAN_NAME,
				DBDOC.T_NOTA.LAMPIRAN_RAWNAME,
				--DBDOC.T_NOTA.PROCESS_STATUS, -- ambil data dari H_DOCUMENTS_PROCESS
				DBDOC.T_NOTA.REVISION,
				--DBDOC.T_NOTA.CURRENT_LAYER, -- ambil data dari H_DOCUMENTS_PROCESS
				DBDOC.T_NOTA.FK_CATEGORIES_ID,
				DBDOC.V_EMPLOYEE.EMPLOYEE_NO,
				DBDOC.V_EMPLOYEE.EMPLOYEE_NAME,
				DBDOC.V_EMPLOYEE.P_ORGANIZATION_ID,
				DBDOC.V_EMPLOYEE.INACTIVE_DATE,
				DBDOC.V_EMPLOYEE.ORGANIZATION_CODE,
				DBDOC.V_EMPLOYEE.NAMA,
				DBDOC.V_EMPLOYEE.P_JOB_POSITION_ID,
				DBDOC.V_EMPLOYEE.JOB_POSITION_CODE,
				DBDOC.V_EMPLOYEE.P_JOB_LOCATION_ID,
				DBDOC.V_EMPLOYEE.JOB_LOCATION,
				DBDOC.V_EMPLOYEE.E_MAIL_ADDR,
				DBDOC.V_EMPLOYEE.PROFIT_CODE,
				DBDOC.V_EMPLOYEE.PROFITC_CODE,
				DBDOC.V_EMPLOYEE.P_KODE_BEBAN_ID,
				DBDOC.V_EMPLOYEE.STATUS,
				DBDOC.V_EMPLOYEE.P_ORGANIZATION_ID_SUB,
				DBDOC.V_EMPLOYEE.ORGANIZATION_CODE_SUB,
				DBDOC.V_EMPLOYEE.KODE_BAGIAN,
				DBDOC.V_EMPLOYEE.USER_NAME,
				DBDOC.H_DOCUMENTS_PROCESS.PK_DOCUMENTS_PROCESS_ID,
				DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID,
				DBDOC.H_DOCUMENTS_PROCESS.VERSION_ID,
				DBDOC.H_DOCUMENTS_PROCESS.PROCESS_STATUS,
				DBDOC.H_DOCUMENTS_PROCESS.CURRENT_LAYER,
				DBDOC.H_DOCUMENTS_PROCESS.FK_TYPE_ID,
				V_NOTA.NO_SURAT NO_SURAT_LONG,
				NVL(T_DIGITAL_CERTIFICATE.FILE_PDF, '') FILE_PDF,
				NVL(T_DIGITAL_CERTIFICATE.FILE_PDF_SIGNED, '') FILE_PDF_SIGNED,
				TO_CHAR(T_DIGITAL_CERTIFICATE.SIGNED_DATE, 'YYYY-MM-DD HH24:MI:SS') SIGNED_DATE,
				TO_CHAR(T_WEBINFO.UPLOAD_DATE, 'YYYY-MM-DD HH24:MI:SS') UPLOAD_DATE
			FROM
				DBDOC.T_NOTA
				LEFT JOIN DBDOC.V_EMPLOYEE ON DBDOC.T_NOTA.CREATE_BY = DBDOC.V_EMPLOYEE.EMPLOYEE_NO
				INNER JOIN DBDOC.H_DOCUMENTS_PROCESS ON DBDOC.T_NOTA.PK_NOTA_ID = DBDOC.H_DOCUMENTS_PROCESS.FK_DOCUMENTS_ID
				INNER JOIN DBDOC.V_NOTA ON T_NOTA.PK_NOTA_ID = V_NOTA.PK_NOTA_ID
				LEFT JOIN T_DIGITAL_CERTIFICATE ON H_DOCUMENTS_PROCESS.PK_DOCUMENTS_PROCESS_ID = T_DIGITAL_CERTIFICATE.FK_DOCUMENTS_PROCESS_ID
				LEFT JOIN T_WEBINFO ON H_DOCUMENTS_PROCESS.PK_DOCUMENTS_PROCESS_ID = T_WEBINFO.FK_DOCUMENTS_PROCESS_ID
			WHERE
				DBDOC.T_NOTA.PK_NOTA_ID = ? AND
				DBDOC.H_DOCUMENTS_PROCESS.FK_TYPE_ID = 2";
		
		$query = $this->db->query($sql, array($nota_id));
		
		if($query)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
		
	public function get_penandatangan($doc_id)
	{
		$sql = "SELECT
				DBDOC.H_DOCUMENTS_STEP.STEP_LAYER,
				DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO,
				DBDOC.H_DOCUMENTS_STEP.STEP_CDT,
				DBDOC.V_NOTA_KEPADA.EMPLOYEE_NO,
				DBDOC.V_NOTA_KEPADA.EMPLOYEE_NAME,
				DBDOC.V_NOTA_KEPADA.KODE_BAGIAN,
				DBDOC.V_NOTA_KEPADA.USER_NAME	
			FROM
				DBDOC.H_DOCUMENTS_STEP
				INNER JOIN DBDOC.V_NOTA_KEPADA ON DBDOC.V_NOTA_KEPADA.EMPLOYEE_NO = DBDOC.H_DOCUMENTS_STEP.EMPLOYEE_NO
			WHERE
				DBDOC.H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = ?
				AND
				DBDOC.H_DOCUMENTS_STEP.FK_TYPE_ID = 2
			";
		$query = $this->db->query($sql, array($doc_id));
		
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_detail_pengesahan1($nota_id)
	{
		$sql = "SELECT
			V_NOTA_PENGESAHAN.EMPLOYEE_NO,
			V_NOTA_PENGESAHAN.EMPLOYEE_NAME,
			V_NOTA_PENGESAHAN.KODE_BAGIAN,
			V_NOTA_PENGESAHAN.USER_NAME,
			V_NOTA_PENGESAHAN.E_MAIL_ADDR,
			T_NOTA.PK_NOTA_ID,
			T_NOTA.PENGESAHAN1
		FROM
			T_NOTA
		INNER JOIN V_NOTA_PENGESAHAN ON T_NOTA.PENGESAHAN1 = V_NOTA_PENGESAHAN.EMPLOYEE_NO
		WHERE
			T_NOTA.PK_NOTA_ID = ?";
		
		$query = $this->db->query($sql, array($nota_id));
		
		if($query)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_detail_pengesahan2($nota_id)
	{
		$sql = "SELECT
			V_NOTA_PENGESAHAN.EMPLOYEE_NO,
			V_NOTA_PENGESAHAN.EMPLOYEE_NAME,
			V_NOTA_PENGESAHAN.KODE_BAGIAN,
			V_NOTA_PENGESAHAN.USER_NAME,
			V_NOTA_PENGESAHAN.E_MAIL_ADDR,
			T_NOTA.PK_NOTA_ID,
			T_NOTA.PENGESAHAN2
		FROM
			T_NOTA
		INNER JOIN V_NOTA_PENGESAHAN ON T_NOTA.PENGESAHAN2 = V_NOTA_PENGESAHAN.EMPLOYEE_NO
		WHERE
			T_NOTA.PK_NOTA_ID = ?";
		
		$query = $this->db->query($sql, array($nota_id));
		
		if($query)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_detail_pengesahan3($nota_id)
	{
		$sql = "SELECT
			V_NOTA_PENGESAHAN.EMPLOYEE_NO,
			V_NOTA_PENGESAHAN.EMPLOYEE_NAME,
			V_NOTA_PENGESAHAN.KODE_BAGIAN,
			V_NOTA_PENGESAHAN.USER_NAME,
			V_NOTA_PENGESAHAN.E_MAIL_ADDR,
			T_NOTA.PK_NOTA_ID,
			T_NOTA.PENGESAHAN3
		FROM
			T_NOTA
		INNER JOIN V_NOTA_PENGESAHAN ON T_NOTA.PENGESAHAN3 = V_NOTA_PENGESAHAN.EMPLOYEE_NO
		WHERE
			T_NOTA.PK_NOTA_ID = ?";
		
		$query = $this->db->query($sql, array($nota_id));
		
		if($query)
		{
			return $query->row_array();
		}
		else
		{
			return FALSE;
		}
	}
    
	public function get_versioning($nota_id)
	{				
		$sql = "SELECT DISTINCT
                H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID,
                TO_NUMBER(H_DOCUMENTS_APPROVAL.VERSION_ID) VERSION_ID,
                H_DOCUMENTS_APPROVAL.EMPLOYEE_NO,
                H_DOCUMENTS_APPROVAL.STEP_LAYER,
                H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
                H_DOCUMENTS_APPROVAL.APPROVAL_MAILED,
                H_DOCUMENTS_APPROVAL.APPROVAL_UDT,
                V_EMPLOYEE.EMPLOYEE_NAME,
                V_EMPLOYEE.E_MAIL_ADDR,
                T_NOTA.PK_NOTA_ID,
                T_NOTA.FK_CATEGORIES_ID,
                P_CATEGORY_PROCESS.FK_CATEGORIES_ID,
                P_CATEGORY_PROCESS.PROCESS_TYPE,
                P_CATEGORY_PROCESS.PROCESS_SORT,
                H_DOCUMENTS_STEP.EMPLOYEE_NO,
                H_DOCUMENTS_STEP.STEP_LAYER,
                H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID,
                H_DOCUMENTS_STEP.FK_DOCUMENTS_ID
            FROM
                H_DOCUMENTS_APPROVAL
                INNER JOIN V_EMPLOYEE 
                    ON H_DOCUMENTS_APPROVAL.EMPLOYEE_NO = V_EMPLOYEE.EMPLOYEE_NO
                INNER JOIN T_NOTA 
                    ON T_NOTA.PK_NOTA_ID = H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
                INNER JOIN P_CATEGORY_PROCESS 
                    ON P_CATEGORY_PROCESS.FK_CATEGORIES_ID = T_NOTA.FK_CATEGORIES_ID 
                    AND H_DOCUMENTS_APPROVAL.STEP_LAYER = P_CATEGORY_PROCESS.PROCESS_SORT
                INNER JOIN H_DOCUMENTS_STEP 
                    ON H_DOCUMENTS_STEP.EMPLOYEE_NO = H_DOCUMENTS_APPROVAL.EMPLOYEE_NO 
                    AND H_DOCUMENTS_STEP.STEP_LAYER = H_DOCUMENTS_APPROVAL.STEP_LAYER
                    AND H_DOCUMENTS_STEP.FK_DOCUMENTS_ID = H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
                    AND H_DOCUMENTS_STEP.FK_TYPE_ID = H_DOCUMENTS_APPROVAL.FK_TYPE_ID
            WHERE
                H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID = ?
                AND H_DOCUMENTS_APPROVAL.FK_TYPE_ID = ?
            ORDER BY
                TO_NUMBER(H_DOCUMENTS_APPROVAL.VERSION_ID) DESC,
                H_DOCUMENTS_APPROVAL.STEP_LAYER ASC,
                H_DOCUMENTS_STEP.PK_DOCUMENTS_STEP_ID ASC";
				
		$query = $this->db->query($sql, array($nota_id, 2));
		
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	 
    public function get_comments($nota_id)
	{
		$sql = "SELECT
					H_DOCUMENTS_COMMENTS.PK_DOCUMENTS_COMMENTS_ID,							
					H_DOCUMENTS_COMMENTS.COMMENTS_CBY,
					H_DOCUMENTS_COMMENTS.COMMENTS_CDT,
					H_DOCUMENTS_COMMENTS.COMMENTS_DESC,
					H_DOCUMENTS_COMMENTS.FK_DOCUMENTS_ID,
					H_DOCUMENTS_COMMENTS.VERSION_ID,
                    H_DOCUMENTS_APPROVAL.APPROVAL_STATUS,
					V_EMPLOYEE.EMPLOYEE_NAME
				FROM H_DOCUMENTS_COMMENTS 
                INNER JOIN V_EMPLOYEE
					ON H_DOCUMENTS_COMMENTS.COMMENTS_CBY = V_EMPLOYEE.EMPLOYEE_NO
                 LEFT JOIN H_DOCUMENTS_APPROVAL
                        ON H_DOCUMENTS_COMMENTS.FK_DOCUMENTS_ID =  H_DOCUMENTS_APPROVAL.FK_DOCUMENTS_ID
                        AND H_DOCUMENTS_COMMENTS.FK_TYPE_ID = H_DOCUMENTS_APPROVAL.FK_TYPE_ID
                        AND H_DOCUMENTS_COMMENTS.STEP_LAYER= H_DOCUMENTS_APPROVAL.STEP_LAYER
                        AND H_DOCUMENTS_COMMENTS.VERSION_ID = H_DOCUMENTS_APPROVAL.VERSION_ID    
				WHERE H_DOCUMENTS_COMMENTS.FK_DOCUMENTS_ID = ?
					AND H_DOCUMENTS_COMMENTS.FK_TYPE_ID = 2
                    AND H_DOCUMENTS_APPROVAL.APPROVAL_UDT IS NOT NULL
                    AND H_DOCUMENTS_COMMENTS.COMMENTS_CDT IS NOT NULL
                    AND H_DOCUMENTS_APPROVAL.APPROVAL_STATUS IS NOT NULL
                    AND H_DOCUMENTS_COMMENTS.STEP_LAYER IS NOT NULL
				ORDER BY
					H_DOCUMENTS_COMMENTS.VERSION_ID DESC,
					H_DOCUMENTS_COMMENTS.PK_DOCUMENTS_COMMENTS_ID DESC";
				
		$query = $this->db->query($sql, array($nota_id));
		
		if($query)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	
	}
        
	public function get_all_new($user_id, $process=FALSE)
	{
		$return = FALSE;
                
		$sql = "SELECT
				DBDOC.T_NOTA.PK_NOTA_ID,
				DBDOC.T_NOTA.NO_SURAT,
				DBDOC.T_NOTA.TANGGAL_NOTA,
				DBDOC.T_NOTA.KEPADA,
				DBDOC.T_NOTA.DARI,
				DBDOC.T_NOTA.HAL,
				DBDOC.T_NOTA.CREATE_DATE
			FROM
				DBDOC.T_NOTA
			WHERE
				DBDOC.T_NOTA.CREATE_BY = '$users_id'";
                
		if($process)
		{
			$sql .= "AND H_NOTA_PROCESS.PROCESS_STATUS = $process";
		}

		$sql .= "ORDER BY T_NOTA.PK_NOTA_ID DESC";

		$query = $this->db->query($sql);

		if($query)
		{
			$return = $query->result_array();
		}

		return $return;
        }
        
	public function get_search($users_id, $process=FALSE)
	{
		$search = $this->input->post('search');
		$return = FALSE;
		$sql = "SELECT
				PK_NOTA_ID,
				NO_SURAT,
				TANGGAL_NOTA,
				KEPADA,
				DARI,
				HAL,
				CREATE_DATE,
				PROCESS_STATUS,
				PROCESS_NAME                
			FROM
			DBDOC.V_NOTA
			WHERE
				CREATE_BY = '$users_id'
					AND NO_SURAT LIKE '%$search%'
					OR HAL LIKE '%$search%'
					OR KEPADA LIKE '%$search%'
					OR DARI LIKE '%$search%'
					OR PROCESS_NAME LIKE '%$search%'
					OR TANGGAL_PUBLIKASI LIKE '%$search%'
                ";
                
		if($process)
		{
			$sql .= "AND V_NOTA.PROCESS_STATUS = $process";
		}

		$sql .= "ORDER BY PK_NOTA_ID DESC";

		$query = $this->db->query($sql);

		if($query)
		{
			$return = $query->result_array();
		}

		return $return;
        }
       
	public function cek_no_surat($no_surat)
	{
        $return = FALSE;

        if(isset($no_surat)){
            $sql = "SELECT count(NO_SURAT) as jumlah FROM DBDOC.T_NOTA WHERE NO_SURAT = '$no_surat'";
            //$query = $this->db->get_where('T_NOTA',array('NO_SURAT'=>$no_surat));
            $query = $this->db->query($sql);
			$return = $query->result_array();
        }
        return $return;
	}
    
	public function auto_increment($table,$PK)
	{
        $this->db->select($PK.',NO_SURAT');
        $this->db->order_by($PK,'desc');
        $row = $this->db->get($table)->row();
        if($row){
                $no = $row->$PK;
        }else{
                $no = 0;
        }
        return $no;
    }
    
    public function check_num()
	{
		$this->db->select('PK_NOTA_ID,NO_SURAT');
		$this->db->order_by('PK_NOTA_ID','desc');
		$row = $this->db->get('T_NOTA')->row();
		return $row;
                
	}
        
	public function input_arr_form($arr)
	{
		if(!$arr){
			return NULL;
		}else{
			return implode(',',$arr);
		}
	}

	public function insert_documents_step($nota_id, $timestamp)
	{
		$penandatangan1 = $this->input->post("pengesahan_1");																	
		/* data_nota_step - step DD */
		$data_nota_step = array(							
			'FK_DOCUMENTS_ID' => $nota_id,
			'EMPLOYEE_NO' => $penandatangan1,
			'STEP_LAYER' => 1,
			'FK_TYPE_ID' => 2,
			'STEP_CDT' => $timestamp
		);
		
		$this->db->insert('H_DOCUMENTS_STEP', $data_nota_step);	
		
		$penandatangan2 = $this->input->post("pengesahan_2");																	
		if($penandatangan2){
			/* data_nota_step - step DD */
			$data_nota_step2 = array(							
				'FK_DOCUMENTS_ID' => $nota_id,
				'EMPLOYEE_NO' => $penandatangan2,
				'STEP_LAYER' => 2,
				'FK_TYPE_ID' => 2,
				'STEP_CDT' => $timestamp
			);
			
			$this->db->insert('H_DOCUMENTS_STEP', $data_nota_step2);	
		}
		
		$penandatangan3 = $this->input->post("pengesahan_3");
		if($penandatangan3){																	
			/* data_nota_step - step DD */
			$data_nota_step3 = array(							
			'FK_DOCUMENTS_ID' => $nota_id,
			'EMPLOYEE_NO' => $penandatangan3,
			'STEP_LAYER' => 3,
			'FK_TYPE_ID' => 2,
			'STEP_CDT' => $timestamp
			);
			
			$this->db->insert('H_DOCUMENTS_STEP', $data_nota_step3);								
		}
	}
	
	public function insert_nota()
	{

		$this->load->helper('date');
		$timestamp = date('Y-m-d H:i:s');
		$desc = $this->input->post('desc');       
		$upload_path = UPLOADDIR.$this->session->userdata('uID').'/';
		
		if(! realpath($upload_path)) {
			$this->load->helper('file');
			mkdir($upload_path);
			$indexPhp = '<?php header("Location: ../"); exit();?>';
			write_file($upload_path.'index.php', $indexPhp);
		}
		
		if($this->input->post('kepada') != 0){			
			$kepada = sanitize_filename($this->input_arr_form($this->input->post('kepada')));
		}
		
		if($this->input->post('tembusan1') != 0){			
			$tembusan1 = sanitize_filename($this->input_arr_form($this->input->post('tembusan1')));
		}
		
		$config['upload_path'] = $upload_path;


		$config['allowed_types'] = 'pdf';
		$config['max_size']	= UPLOADSIZE;
		$config['encrypt_name'] = TRUE;		
	
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('lampiran') )
		{
			$date = date('Y-m-d H:i:s');
			
			$doc_status = DOC_DRAFT; // we use always draft docs first

			/* documents - metadata */
			//var_dump($this->input->post('dari'));
			$documents = array(                                
				'NO_SURAT' => '0',
				'FK_CATEGORIES_ID' => $this->input->post('categories'),
				'FK_KLASIFIKASI_ID' => $this->input->post('klasifikasi'),
				'TANGGAL_NOTA' => $this->input->post('datepub'),
				'DESKRIPSI' => $desc,
				'KEPADA' => $this->input_arr_form($this->input->post('kepada1')),
				'KEPADA_TEXT' => $this->clean_delimiter_text($kepada),
				'DARI' => sanitize_filename($this->input->post('dari')),
				'HAL' => sanitize_filename($this->input->post('hal')),
				'TEMBUSAN' => $this->input_arr_form($this->input->post('tembusan')),
				'TEMBUSAN_TEXT' => $this->clean_delimiter_text($tembusan1),
				'TEMPAT' => sanitize_filename($this->input->post('tempat')),
				'PEMBUAT_KONSEP' => $this->input_arr_form($this->input->post('pembuat_konsep')),
				'PENGESAHAN1' => $this->input->post('pengesahan_1'),
				'PENGESAHAN2' => $this->input->post('pengesahan_2'),
				'PENGESAHAN3' => $this->input->post('pengesahan_3'),
				'CREATE_DATE' => $timestamp,
				'CREATE_BY'=> $this->session->userdata('uID'),
				'PROCESS_STATUS' => $doc_status,
				'REVISION' => '1',
				'CURRENT_LAYER' => '1'
			);


			// $this->db->insert('T_NOTA', $documents);
			oci8_insert_bind('T_NOTA', $documents);
			
			$sql = 'SELECT MAX(TO_NUMBER(DBDOC.T_NOTA.PK_NOTA_ID))
				FROM
				DBDOC.T_NOTA WHERE CREATE_BY = ?';
			$row = $this->db->query($sql, array($this->session->userdata('uID')))->row();		
			
			foreach($row as $key=>$val)
			{
				$nota_id = $val;
			}

			/* nota_process - nota project */
			$nota_process = array(	
				'FK_DOCUMENTS_ID' => $nota_id,
				'PROCESS_STATUS' => $doc_status,
				'FK_TYPE_ID' => 2,
				'CURRENT_LAYER' => 1,						
				'UDT' => $timestamp
			);



			$this->db->insert('H_DOCUMENTS_PROCESS', $nota_process);






			
			$this->insert_documents_step($nota_id, $timestamp);
			
		}
		else
		{	
			$imageData = $this->upload->data();	
			$date = date('Y-m-d H:i:s');
			
			$doc_status = DOC_DRAFT; // we use always draft docs first

			/* documents - metadata */
			//var_dump($this->input->post('dari'));
			$documents = array(                               
				'NO_SURAT' => '0',
				'FK_CATEGORIES_ID' => $this->input->post('categories'),
				'FK_KLASIFIKASI_ID' => $this->input->post('klasifikasi'),
				'TANGGAL_NOTA' => $this->input->post('datepub'),
				'DESKRIPSI' => $desc,
				'KEPADA' => $this->input_arr_form($this->input->post('kepada1')),
				'KEPADA_TEXT' => $this->clean_delimiter_text($kepada),
				'DARI' => sanitize_filename($this->input->post('dari')),
				'HAL' => sanitize_filename($this->input->post('hal')),
				'TEMBUSAN' => $this->input_arr_form($this->input->post('tembusan')),
				'TEMBUSAN_TEXT' => $this->clean_delimiter_text($tembusan1),
				'TEMPAT' => sanitize_filename($this->input->post('tempat')),
				'PEMBUAT_KONSEP' => $this->input_arr_form($this->input->post('pembuat_konsep')),
				'PENGESAHAN1' => $this->input->post('pengesahan_1'),
				'PENGESAHAN2' => $this->input->post('pengesahan_2'),
				'PENGESAHAN3' => $this->input->post('pengesahan_3'),
				'LAMPIRAN_NAME' => $imageData['orig_name'],
				'LAMPIRAN_RAWNAME' => $upload_path.$imageData['file_name'],
				'CREATE_DATE' => $timestamp,
				'CREATE_BY'=> $this->session->userdata('uID'),
				'PROCESS_STATUS' => $doc_status,
				'REVISION' => '1',
				'CURRENT_LAYER' => '1'
			);

			$this->db->insert('T_NOTA', $documents);
			//$num = $this->check_num();
			
			$sql = 'SELECT MAX(TO_NUMBER(DBDOC.T_NOTA.PK_NOTA_ID))
				FROM
				DBDOC.T_NOTA';
			$row = $this->db->query($sql)->row();		
			
			foreach($row as $key=>$val)
			{
				$nota_id = $val;
			}

			/* nota_process - nota project */
			$nota_process = array(							
				'FK_DOCUMENTS_ID' => $nota_id,
				'PROCESS_STATUS' => $doc_status,
				'CURRENT_LAYER' => 1,
				'FK_TYPE_ID' => 2,							
				'UDT' => $timestamp
			);


			$this->db->insert('H_DOCUMENTS_PROCESS', $nota_process);






			
			$this->insert_documents_step($nota_id, $timestamp);
				
		}
                
		return $nota_id;
	}
    
	public function update_nota()
	{
		$nota_id = $this->input->post('nota_id');
		$this->load->helper('date');
		$timestamp = date('Y-m-d H:i:s');
		$desc = $this->input->post('desc');
		$upload_path = UPLOADDIR.$this->session->userdata('uID').'/';
		
		if(! realpath($upload_path)) {
			$this->load->helper('file');
			mkdir($upload_path);
			$indexPhp = '<?php header("Location: ../"); exit();?>';
			write_file($upload_path.'index.php', $indexPhp);
		}
		
		if($this->input->post('kepada') != 0){			
			$kepada = sanitize_filename($this->input_arr_form($this->input->post('kepada')));
		}
				
		if($this->input->post('tembusan1') != 0){			
			$tembusan1 = sanitize_filename($this->input_arr_form($this->input->post('tembusan1')));
		}
		
		$config['upload_path'] = $upload_path;
		$config['file_name'] = $this->input->post('file_name');		
		$config['overwrite'] = true;
		$config['allowed_types'] = 'pdf';
		$config['max_size']	= UPLOADSIZE;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('lampiran') )
		{
			$date = date('Y-m-d H:i:s');
			


			/* documents - metadata */
			//var_dump($this->input->post('dari'));
			$documents = array(                              

				'FK_CATEGORIES_ID' => $this->input->post('categories'),
				'FK_KLASIFIKASI_ID' => $this->input->post('klasifikasi'),
				'TANGGAL_NOTA' => $this->input->post('datepub'),
				'DESKRIPSI' => $desc,
				'KEPADA' => $this->input_arr_form($this->input->post('kepada1')),
				'KEPADA_TEXT' => $this->clean_delimiter_text($this->input->post('list_kepada').','.$kepada),
				'DARI' => sanitize_filename($this->input->post('dari')),
				'HAL' => sanitize_filename($this->input->post('hal')),
				'TEMBUSAN' => $this->input_arr_form($this->input->post('tembusan')),
				'TEMBUSAN_TEXT' => $this->clean_delimiter_text($this->input->post('list_tembusan').','.$tembusan1),
				'TEMPAT' => sanitize_filename($this->input->post('tempat')),
				'PEMBUAT_KONSEP' => $this->input_arr_form($this->input->post('pembuat_konsep')),
				'PENGESAHAN1' => $this->input->post('pengesahan_1'),
				'PENGESAHAN2' => $this->input->post('pengesahan_2'),
				'PENGESAHAN3' => $this->input->post('pengesahan_3'),
				'UPDATE_DATE' => $timestamp,
				'UPDATE_BY'=> $this->session->userdata('uID'),

				'REVISION' => '1',
				'CURRENT_LAYER' => '1'
			);
			// $this->db->where('PK_NOTA_ID',$nota_id);
			// $this->db->update('T_NOTA', $documents);
			oci8_update_bind('T_NOTA', $documents, array('PK_NOTA_ID'=>$nota_id));





			





			/* nota_process - nota project */
			$nota_process = array(							




				'UDT' => $timestamp
			);
			
			$this->db->where('FK_DOCUMENTS_ID',$nota_id);
			$this->db->where('FK_TYPE_ID',2);
			$this->db->update('H_DOCUMENTS_PROCESS', $nota_process);											
			
			// step layer
			// delete all Step DD this nota_id		
			$this->db->where('FK_DOCUMENTS_ID', $nota_id);
			$this->db->where('FK_TYPE_ID',2);
			$this->db->delete('H_DOCUMENTS_STEP');

			$this->insert_documents_step($nota_id, $timestamp);
	
		}
		else
		{	
			$imageData = $this->upload->data();	
			$date = date('Y-m-d H:i:s');
			


			/* documents - metadata */
			//  var_dump($this->input->post('dari'));
			$documents = array(                                                              

				'FK_CATEGORIES_ID' => $this->input->post('categories'),
				'FK_KLASIFIKASI_ID' => $this->input->post('klasifikasi'),
				'TANGGAL_NOTA' => $this->input->post('datepub'),
				'DESKRIPSI' => $desc,
				'KEPADA' => $this->input_arr_form($this->input->post('kepada1')),
				'KEPADA_TEXT' => $this->clean_delimiter_text($this->input->post('list_kepada').','.$kepada),
				'DARI' => sanitize_filename($this->input->post('dari')),
				'HAL' => sanitize_filename($this->input->post('hal')),
				'TEMBUSAN' => $this->input_arr_form($this->input->post('tembusan')),
				'TEMBUSAN_TEXT' => $this->clean_delimiter_text($this->input->post('list_tembusan').','.$tembusan1),
				'TEMPAT' => sanitize_filename($this->input->post('tempat')),
				'PEMBUAT_KONSEP' => $this->input_arr_form($this->input->post('pembuat_konsep')),
				'PENGESAHAN1' => $this->input->post('pengesahan_1'),
				'PENGESAHAN2' => $this->input->post('pengesahan_2'),
				'PENGESAHAN3' => $this->input->post('pengesahan_3'),
				'LAMPIRAN_NAME' => $imageData['orig_name'],
				'LAMPIRAN_RAWNAME' => $upload_path.$imageData['file_name'],
				'UPDATE_DATE' => $timestamp,
				'UPDATE_BY'=> $this->session->userdata('uID'),

				'REVISION' => '1',
				'CURRENT_LAYER' => '1'
			);
			// $this->db->where('PK_NOTA_ID',$nota_id);
			// $this->db->update('T_NOTA', $documents);
			oci8_update_bind('T_NOTA', $documents, array('PK_NOTA_ID'=>$nota_id));
			










			/* nota_process - nota project */
			$nota_process = array(														




				'UDT' => $timestamp
			);
			$this->db->where('FK_DOCUMENTS_ID',$nota_id);
			$this->db->where('FK_TYPE_ID',2);
			$this->db->update('H_DOCUMENTS_PROCESS', $nota_process);
			
			// step layer
			// delete all Step DD this nota_id		
			$this->db->where('FK_DOCUMENTS_ID', $nota_id);
			$this->db->where('FK_TYPE_ID',2);
			$this->db->delete('H_DOCUMENTS_STEP');

			$this->insert_documents_step($nota_id, $timestamp);
			
		}
                
		return $nota_id;
	}
	
	public function update_nota_rev()
	{
		$nota_id = $this->input->post('nota_id');
		$this->load->helper('date');
		$timestamp = date('Y-m-d H:i:s');
		$desc = $this->input->post('desc');
		$upload_path = UPLOADDIR.$this->session->userdata('uID').'/';
		
		if(! realpath($upload_path)) {
			$this->load->helper('file');
			mkdir($upload_path);
			$indexPhp = '<?php header("Location: ../"); exit();?>';
			write_file($upload_path.'index.php', $indexPhp);
		}
		
		if($this->input->post('kepada') != 0){			
			$kepada = sanitize_filename($this->input_arr_form($this->input->post('kepada')));
		}
				
		if($this->input->post('tembusan1') != 0){			
			$tembusan1 = sanitize_filename($this->input_arr_form($this->input->post('tembusan1')));
		}
		
		$config['upload_path'] = $upload_path;
		$config['file_name'] = $this->input->post('file_name');		
		$config['overwrite'] = true;
		$config['allowed_types'] = 'pdf';
		$config['max_size']	= UPLOADSIZE;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('lampiran') )
		{
			$date = date('Y-m-d H:i:s');
			


			/* documents - metadata */
			//var_dump($this->input->post('dari'));
			$documents = array(                              

				'FK_CATEGORIES_ID' => $this->input->post('categories'),
				'FK_KLASIFIKASI_ID' => $this->input->post('klasifikasi'),
				'TANGGAL_NOTA' => $this->input->post('datepub'),
				'DESKRIPSI' => $desc,
				'KEPADA' => $this->input_arr_form($this->input->post('kepada1')),
				'KEPADA_TEXT' => $this->clean_delimiter_text($this->input->post('list_kepada').','.$kepada),
				'DARI' => sanitize_filename($this->input->post('dari')),
				'HAL' => sanitize_filename($this->input->post('hal')),
				'TEMBUSAN' => $this->input_arr_form($this->input->post('tembusan')),
				'TEMBUSAN_TEXT' => $this->clean_delimiter_text($this->input->post('list_tembusan').','.$tembusan1),
				'TEMPAT' => sanitize_filename($this->input->post('tempat')),
				'PEMBUAT_KONSEP' => $this->input_arr_form($this->input->post('pembuat_konsep')),
				'UPDATE_DATE' => $timestamp,
				'UPDATE_BY'=> $this->session->userdata('uID'),

				'REVISION' => '1',
				'CURRENT_LAYER' => '1'
			);
			// $this->db->where('PK_NOTA_ID',$nota_id);
			// $this->db->update('T_NOTA', $documents);
			oci8_update_bind('T_NOTA', $documents, array('PK_NOTA_ID'=>$nota_id));
	
		}
		else
		{	
			$imageData = $this->upload->data();	
			$date = date('Y-m-d H:i:s');
			


			/* documents - metadata */
			//  var_dump($this->input->post('dari'));
			$documents = array(                                                              

				'FK_CATEGORIES_ID' => $this->input->post('categories'),
				'FK_KLASIFIKASI_ID' => $this->input->post('klasifikasi'),
				'TANGGAL_NOTA' => $this->input->post('datepub'),
				'DESKRIPSI' => $desc,
				'KEPADA' => $this->input_arr_form($this->input->post('kepada1')),
				'KEPADA_TEXT' => $this->clean_delimiter_text($this->input->post('list_kepada').','.$kepada),
				'DARI' => sanitize_filename($this->input->post('dari')),
				'HAL' => sanitize_filename($this->input->post('hal')),
				'TEMBUSAN' => $this->input_arr_form($this->input->post('tembusan')),
				'TEMBUSAN_TEXT' => $this->clean_delimiter_text($this->input->post('list_tembusan').','.$tembusan1),
				'TEMPAT' => sanitize_filename($this->input->post('tempat')),
				'PEMBUAT_KONSEP' => $this->input_arr_form($this->input->post('pembuat_konsep')),
				'LAMPIRAN_NAME' => $imageData['orig_name'],
				'LAMPIRAN_RAWNAME' => $upload_path.$imageData['file_name'],
				'UPDATE_DATE' => $timestamp,
				'UPDATE_BY'=> $this->session->userdata('uID'),

				'REVISION' => '1',
				'CURRENT_LAYER' => '1'
			);
			// $this->db->where('PK_NOTA_ID',$nota_id);
			// $this->db->update('T_NOTA', $documents);
			oci8_update_bind('T_NOTA', $documents, array('PK_NOTA_ID'=>$nota_id));
			
		}
                
		return $nota_id;
	}
	
	public function update_no()
	{
		$id = $this->input->post('nota_id');
		$this->load->helper('date');
		$timestamp = date('Y-m-d H:i:s');
			                       
		/* documents - metadata */                        
		$documents = array(                               
			'NO_SURAT' => sanitize_filename($this->input->post('no_surat')),
			'TANGGAL_NOTA' => $this->input->post('datepub'),
			'UPDATE_DATE' => $timestamp,
			'UPDATE_BY'=> $this->session->userdata('uID'),                                
		);
		$this->db->where('PK_NOTA_ID',$id);
		$this->db->update('T_NOTA', $documents);
		
		return true;
	}
	
	public function clean_delimiter_text($str) 
	{
		$arr = explode(",", $str);
		foreach ($arr as $k=>$v) {
			if ($v == "") {
				unset($arr[$k]);
			}
		}
		return implode(",", $arr);
	}
      	
} // end class