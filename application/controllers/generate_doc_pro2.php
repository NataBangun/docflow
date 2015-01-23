<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(dirname(__FILE__) . "/../libraries/MPDF/mpdf.php");

class Generate_doc_pro2 extends CI_Controller {

	var $_tplIdx;
	
    function __construct()
    {
        parent::__construct();
		$this->load->library("pdf2");
		$this->load->library("fpdf");
		$this->load->library("fpdi");
		$this->load->library("PDFMerger2"); 
		$this->load->model('mm_documents');	
		$this->load->model(array('mm_categories', 'mm_users'));
		$this->load->model('mm_digital_certificate');
		$this->setter();	
    }
	
	public function index()
	{
		echo '<center><a href="'.base_url().'generate_doc_pro/create/1"><button> run run run run run run run run run run run run run run run run</button></a></center>';
	}
	
	public function create($doc_id)
	{

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
			
		$records = $documents;
		$penandatangan = $this->mm_documents->get_penandatangan($doc_id);		
		//$data['versioning'] = FALSE;		
		$versioning = $this->mm_documents->get_versioning($doc_id);	
		$process = $this->mm_documents->get_process_by_cat($doc_id);		
		//For Mrg PDF
		$files = $this->mm_documents->get_files_merge($doc_id);	
		$string = $records['DOCUMENTS_VERSION'];	
		
		$mpdf = new mPDF($mode = 'c', $format = 'A4', $font_size = '10', $font_type = 'dejavusans',
			$margin_left = 25, $margin_right = 20, $margin_top = 55, $margin_bottom = 10, 
			$margin_header = 0, $margin_footer = 0, $orientation = 'P'); 

		$mpdf->SetDisplayMode('fullpage');

		// $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
		
		$html =
		'
		<style>
			.division {
				border-collapse: collapse;
			}
			.division tr td{
				font-size: 9px;
				width: 50%;
			}
			.bordered {
				border-collapse: collapse;
			}
			.bordered tr td{
				border: 1px solid #000;
				text-align: center;
				width: 20%;
			}
			.bordered .header td{
				background-color:grey; color: #fff;
			}
		</style>
		<p><b><u>KOLOM PENGESAHAN</u></b></p>
		<!-- tabel 1 -->
		<p><b>Disusun Oleh : </b></p>
		<table style="width:100%;" border="1" cellspacing="0" cellpadding="4" class="bordered">
		<tr class="header">
			<td style="width:5%;"> NO </td>
			<td> NAMA </td>
			<td> JABATAN </td>
			<td> TANDA TANGAN </td>
			<td> TANGGAL </td>
		</tr>
		<tr>
			<td style="width:5%;">1</td>
			<td>'.ucwords(strtolower($records['EMPLOYEE_NAME'])).'</td>
			<td>'.$records['ORGANIZATION_CODE'].'<br>'.$records['JOB_POSITION_CODE'].'</td>
			<td>&nbsp;</td>
			<td>'.id_date_complete($records['DOCUMENTS_CDT']).'</td>
		</tr>
		</table>';
		foreach($process as $key=>$val){
		if($val['PROCESS_SORT'] <4){
			$key = $key+1;
			$html .= '
			<!-- tabel 2 -->
			<p><b>'.ucwords(strtolower($val['PROCESS_PDF_NAME'])).' Oleh :</b></p>
			<table style="width:100%;" border="1" cellspacing="0" cellpadding="4" class="bordered">
			<tr class="header">
				<td style="width:5%;"> NO. </td>
				<td> NAMA </td>
				<td> JABATAN </td>
				<td> TANDA TANGAN </td>
				<td> TANGGAL </td>
			</tr>';
			$num = 1;			
			foreach($penandatangan as $level=>$user){
				if($user['STEP_LAYER']==$key) {
					$html .= '	
					<tr>
						<td style="width:5%;">'.$num.'</td>
						<td>'.ucwords(strtolower($user['EMPLOYEE_NAME'])).'</td>
						<td>'.$user['ORGANIZATION_CODE'].'<br>'.$user['JOB_POSITION_CODE'].'</td>
						<td>&nbsp;</td>
						<td>'.id_date_complete($user['APPROVAL_UDT']).'</td>
					</tr>';
					$num++;
				}			
			}
			$html .= '
			</table>';
		}
		}

		$categories_image = dirname($_SERVER['SCRIPT_FILENAME']).'uploads/category/'.$val['CATEGORIES_IMAGE'];
		if (!is_file($categories_image)) {
			$categories_image = '&nbsp;';
		} else {
			$categories_image = '<img src="'.$categories_image.'" alt="logo">';
		}
		
		$html .= '
		<!-- distribusi -->
		<p><b>Distribusi Kepada :</b></p>

		<table style="width:100%;" border="0" cellspacing="0" cellpadding="4">
			<tr><td>
					<ul class="listing">';			
					$clears = rtrim($records['DOCUMENTS_DISTRIBUTION'], ', ');
					$ex_dis = explode(',', $clears);
					$a = 0;			
					foreach($ex_dis as $key=>$val){
					if($key == $a ){
					$html .= '<li style="font-family:dejavusans;font-size: 10px;">'.$val.'</li>';
					$a =($a++)+2;					
					}					
					}
					$html .= '</ul>
				</td>				
				<td>
					<ul class="listing">';
					$clears = rtrim($records['DOCUMENTS_DISTRIBUTION'], ', ');
					$ex_dis = explode(',', $clears);
					$a = 1;			
					foreach($ex_dis as $key=>$val){
					if($key == $a ){
					$html .= '<li Style="font-family:dejavusans;font-size: 10;">'.$val.'</li>';	
					$a = ($a++)+2;
					}					
					}										
					$html .= '</ul>
				</td>
			</tr>
		</table>

		<!--<div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div>-->
		
		<!-- stempel -->
		<div style="page-break-inside:avoid;">
		<div style="display: block; width: 100%;clear: both;"></div>
		<p style="margin-bottom: 0;"><b>KOLOM STEMPEL</b><br>&nbsp;</p>
		<div style="width: 100%;border: 1px solid #000; text-align: center;">
			'.$categories_image.'
		</div>
		</div>
		'
		;

		$mpdf->WriteHTML($html);

		$file_out = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/dokumen_orig_".$records['DOCUMENTS_NO'].".pdf";
		$mpdf->Output($file_out, 'F'); 

		return $file_out;
	}
	
	
	public function create_header_footer($file_merge = "")
	{
		if (is_file($file_merge)) {
			$fpdi = new FPDI;
			$count = $fpdi->setSourceFile($file_merge);
		} else {
			$count = 1;
		}
	
		$doc_id = intval( $this->uri->segment(3) );		
		$documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
		$records = $documents;
		$string = $records['DOCUMENTS_VERSION'];

		$mpdf = new mPDF($mode = 'c', $format = 'A4', $font_size = '10', $font_type = 'dejavusans',
			$margin_left = 25, $margin_right = 20, $margin_top = 10, $margin_bottom = 10, 
			$margin_header = 0, $margin_footer = 0, $orientation = 'P'); 

		$mpdf->SetDisplayMode('fullpage');

		// $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

		for ($page=1; $page<=$count; $page++) {	
		
			$logo = base_url() . "assets/img/hl3.png";
			$html = <<<EOD
			<table cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%;">
				<tr>
					<td width="33%" style="border: 1px solid #000; font-size: 10px; padding: 10px 0 10px 5px;">No. Dok: {$records['DOCUMENTS_NO']}</td>
					<td width="33%" style="border: 1px solid #000; text-align: center;" rowspan="4"><h2>{$records['DOCUMENTS_TITLE']}</h2></td>
					<td width="33%" style="border: 1px solid #000; text-align: center;" rowspan="4"><div><img src="$logo" alt="logo"></div></td>
				</tr>
				<tr>
					<td style="border: 1px solid #000; font-size: 10px; padding: 10px 0 10px 5px;">Versi: {$string[0]}.{$string[1]}</td>
				</tr>
				<tr>
					<td style="border: 1px solid #000; font-size: 10px; padding: 10px 0 10px 5px;">Hal: $page dari $count </td>
				</tr>
				<tr>
					<td style="border: 1px solid #000; font-size: 10px; padding: 10px 0 10px 5px;">Label: Internal</td>
				</tr>
			</table>

			<!-- Ukuran kertas A4: 210mm x 297mm; Width = 210mm (Lebar kertas) - 25mm (margin kiri) - 20mm (margin kanan) = 165mm -->
			<p style="width: 165mm; border-top: 1px solid #000; font-size: 8px; position: absolute; left: 25mm; bottom: 10mm;">
				<br>
				@HakCipta PT.APLIKANUSA LINTASARTA, Indonesia <br>
				{$records['DOCUMENTS_NO']}  {$records['DOCUMENTS_TITLE']}.
			</p>
EOD;
			$mpdf->WriteHTML($html);
			if ($page < $count) {
				$mpdf->AddPage();
			}
		}

		$file_header_footer = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/hf_".$records['DOCUMENTS_CBY'].".pdf";
		$mpdf->Output($file_header_footer,'F');

		return $file_header_footer;
	}
	
	public function testerMM($doc_id, $return_pdf = false) 
	{			
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
			
		$records = $documents;
		
		$files = $this->mm_documents->get_files_merge($doc_id);
		if(!$files){
			$file = 0;
		}			
		
		$file_dock = $this->create($doc_id);
		$file_lampiran = dirname($_SERVER['SCRIPT_FILENAME']).UPLOAD_DOKPRO.$records['PK_DOCUMENTS_ID']."/".$records['DOCUMENTS_ATC_SYSTEM']."";
		
		$file_merge = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/merger_".$records['PK_DOCUMENTS_ID'].".pdf";
		
		$file_finish = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/dokumen_no_".$records['PK_DOCUMENTS_ID'].".pdf";
		
		$PDFMerger = new PDFMerger2();
		$PDFMerger->addPDF($file_dock);		
		if (is_file($file_lampiran)) {
			$PDFMerger->addPDF($file_lampiran);		
		}
				
		$PDFMerger->merge('file', $file_merge);
		
		$PDFMerger = new PDFMerger2();
		$PDFMerger->addPDF_header_footer($file_merge, 'all', $this->create_header_footer($file_merge));				
		$PDFMerger->merge('file', $file_finish);
		
		if ($return_pdf == false) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.pathinfo($file_finish, PATHINFO_BASENAME).'"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');		
			readfile($file_finish);
		} else {
			return $file_finish;
		}
    } 
	
	private function setter()
	{
		$this->data['config'] = mm_cache_config();
		$this->data['userInfo'] = $this->session->all_userdata();		
		return $this->data;
	}

    public function sign($doc_id, $process_id, $uID) 
	{		
		$this->data['userInfo']['uID'] = $uID; // fix bug
		$this->session->set_userdata('uID', $uID); // fix bug
		
		$file_pdf = $this->testerMM($doc_id, true);
		
		$sql = "SELECT
					V_EMPLOYEE.E_MAIL_ADDR,                
					V_EMPLOYEE.ORGANIZATION_CODE,                
					V_EMPLOYEE.EMPLOYEE_NAME,
					V_EMPLOYEE.EMPLOYEE_NO                
				FROM
					T_DOCUMENTS 
					INNER JOIN V_EMPLOYEE ON V_EMPLOYEE.EMPLOYEE_NO = T_DOCUMENTS.DOCUMENTS_CBY
				WHERE
					T_DOCUMENTS.PK_DOCUMENTS_ID = ? 
			";
		$query = $this->db->query($sql, array($doc_id));
		$records = $query->row_array();
		
		$penandatangan = $this->mm_documents->get_penandatangan_for_webinfo($doc_id);		

		$this->mm_digital_certificate->set_process_id($process_id);
		$this->mm_digital_certificate->set_folder($this->session->userdata('session_id'));
		$this->mm_digital_certificate->set_pdf($file_pdf);
		
		$this->mm_digital_certificate->sign($alias = $records['EMPLOYEE_NO'], 
			$email = $records['E_MAIL_ADDR'], 
			$nama = $records['EMPLOYEE_NAME'], 
			$unit = $records['ORGANIZATION_CODE']);
				
		foreach ($penandatangan as $k=>$v) {
			$this->mm_digital_certificate->sign($alias = $v['EMPLOYEE_NO'], 
				$email = $v['E_MAIL_ADDR'], 
				$nama = $v['EMPLOYEE_NAME'], 
				$unit = $v['ORGANIZATION_CODE']);
		}
			
		$file_pdf_signed = $this->mm_digital_certificate->get_signed_pdf();

		echo "sukses";
		// header('Location: '. $_SERVER['HTTP_REFERER']);
		
    }
}
