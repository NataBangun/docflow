<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(dirname(__FILE__) . "/../libraries/MPDF/mpdf.php");

class Generate_doc_pro extends CI_Controller {

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
	
	public function create_old($doc_id)
	{

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
		$penandatangan = $this->mm_documents->get_penandatangan_for_webinfo($doc_id);		
		//$data['versioning'] = FALSE;		
		$versioning = $this->mm_documents->get_versioning($doc_id);	
		$process = $this->mm_documents->get_process_by_cat($doc_id);		
		//For Mrg PDF
		$files = $this->mm_documents->get_files_merge($doc_id);	
		$string = $records['DOCUMENTS_VERSION'];	
		
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
			 


		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', '7'));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(17);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-15);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------
		$pdf->setListIndentWidth(4);
		// set default font subsetting mode
		$pdf->setFontSubsetting(true);
		// Set font
		$pdf->SetFont('dejavusans', '', 10, '', true);
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// Set some content to print
		$html =
		'
		<style>
			.division tr td{
				font-size: 9px;
			}
			.bordered tr td{
				border: 1px solid #000;
				text-align: center;
			}
			.clear{
				display: block;
				width: 100%;
				clear: both;
				height: 10cm;
			}
		</style>
		<div class="clear"></div>
		<h4>kolom pengesahan</h4>
		<!-- tabel 1 -->
		<h5>Disusun oleh : </h5>
		<table style="width:100%;" border="1" cellspacing="0" cellpadding="4" class="bordered">
		<tr style="background-color:grey; color: #fff;">
			<td> NO. </td>
			<td> NAMA </td>
			<td> JABATAN </td>
			<td> TANDA TANGAN </td>
			<td> TANGGAL </td>
		</tr>
		<tr>
			<td>1</td>
			<td>'.ucwords(strtolower($records['EMPLOYEE_NAME'])).'</td>
			<td>'.ucwords(strtolower($records['ORGANIZATION_CODE'])).'<br>'.ucwords(strtolower($records['JOB_POSITION_CODE'])).'</td>
			<td><img src="'.base_url().'assets/img/ttd.png" alt="logo"></td>
			<td>'.id_date_complete($records['DOCUMENTS_CDT']).'</td>
		</tr>
		</table>';
		foreach($process as $key=>$val){
			$key = $key+1;
			$html .= '
			<!-- tabel 2 -->
			<h5>'.$val['PROCESS_NAME'].' oleh :</h5>
			<table style="width:100%;" border="1" cellspacing="0" cellpadding="4" class="bordered">
			<tr style="background-color:grey; color: #fff;">
				<td> NO. </td>
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
						<td>'.$num.'</td>
						<td>'.ucwords(strtolower($user['EMPLOYEE_NAME'])).'</td>
						<td>'.ucwords(strtolower($records['ORGANIZATION_CODE'])).'<br>'.ucwords(strtolower($records['JOB_POSITION_CODE'])).'</td>
						<td><img src="'.base_url().'assets/img/ttd.png" alt="logo"></td>
						<td>'.id_date_complete($user['APPROVAL_UDT']).'</td>
					</tr>';
					$num++;
				}			
			}
			$html .= '
			</table>';
		}
		$html .= '
		<!-- distribusi -->
		<h4>Distribusi Kepada :</h4>

		<table style="width:100%;" border="0" cellspacing="0" cellpadding="4" class="division">
			<tr>
				<td>
					<ul class="listing">
						<li>CAM Finance & Supply Chain Industri Division</li>
						<li>CAM Resources & Partnership Division</li>
						<li>Operations & Maintenance Division</li>
						<li>Supply Chain Management Division</li>
					</ul>
				</td>
				<td>
					<ul class="listing">
						<li>Data Com Commerce Division</li>
						<li>Service Delivery Division</li>
						<li>Management Representative</li>
					</ul>
				</td>
			</tr>
		</table>

		<!-- stempel -->
		<div style="page-break-inside:avoid;">
		<div style="display: block; width: 100%;clear: both;"></div>
		<h4>KOLOM STEMPEL</h4>
		<div style="width: 100%;border: 1px solid #000;">
			<img src="'.base_url('uploads/category/'.$val['CATEGORIES_IMAGE']).'" alt="logo" style="text-align:center;">
		</div>
		</div>
		'
		;
		// Print text using writeHTMLCell()
		$pdf->writeHTML($html, true, false, true, false, '');


		$file_out = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/dokumen_orig_".$records['DOCUMENTS_NO'].".pdf";
		$pdf->Output($file_out, 'F'); 

		return $file_out;
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
		$penandatangan = $this->mm_documents->get_penandatangan_for_webinfo($doc_id);		
		//$data['versioning'] = FALSE;		
		$versioning = $this->mm_documents->get_versioning($doc_id);	
		$process = $this->mm_documents->get_process_by_cat($doc_id);		
		//For Mrg PDF
		$files = $this->mm_documents->get_files_merge($doc_id);	
		$string = $records['DOCUMENTS_VERSION'];	
		
		$mpdf = new mPDF($mode = 'c', $format = 'A4', $font_size = '10', $font_type = 'dejavusans',
			$margin_left = 25, $margin_right = 20, $margin_top = 55, $margin_bottom = 30, 
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
		<h4><u>KOLOM PENGESAHAN</u></h4>
		<!-- tabel 1 -->
		<p><b>Disusun Oleh : </b></p>
		<table style="width:100%;" border="1" cellspacing="0" cellpadding="4" class="bordered">
		<tr class="header">
			<td> NO. </td>
			<td> NAMA </td>
			<td> JABATAN </td>
			<td> TANDA TANGAN </td>
			<td> TANGGAL </td>
		</tr>
		<tr>
			<td>1</td>
			<td>'.ucwords(strtolower($records['EMPLOYEE_NAME'])).'</td>
			<td>'.$records['ORGANIZATION_CODE'].'<br>'.$records['JOB_POSITION_CODE'].'</td>
			<td><img src="'.base_url().'uploads/paraf/ttd/'.$records['USERS_SIGNATURE'].'" height="40px" alt="logo"></td>
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
				<td>No</td>
				<td>Nama</td>
				<td>Jabatan</td>
				<td>Tanda tangan</td>
				<td>Tanggal</td>
			</tr>';
			$num = 1;		
			//print_r($penandatangan);exit();

			foreach($penandatangan as $level=>$user){
				if($user['STEP_LAYER']==$key && $user['STEP_LAYER'] < 4){
					$html .= '	
					<tr>
						<td>'.$num.'</td>
						<td>'.ucwords(strtolower($user['EMPLOYEE_NAME'])).'</td>
						<td>'.$user['ORGANIZATION_CODE'].'<br>'.$user['JOB_POSITION_CODE'].'</td>
						<td><img src="'.base_url().'uploads/paraf/ttd/'.$user['USERS_SIGNATURE'].'" height="40px" alt="logo"></td>
						<td>'.id_date_complete($user['APPROVAL_UDT']).'</td>
					</tr>';
					$num++;			
				}			
			}
			$html .= '
			</table>';		
		}
		}

		if (!$val['CATEGORIES_IMAGE']) {
			$img_stempel = '&nbsp;';
		} else {
			$categories_image = $val['CATEGORIES_IMAGE'];
			
			// get data stempel
			$tgl_stempel = "12 Desember 2012";
			$ttd_stempel = "ttd.png";
			foreach($penandatangan as $level=>$user){
				if($user['STEP_LAYER'] == 4){
					$tgl_stempel = id_date_complete($user['APPROVAL_UDT']);
					$ttd_stempel = $user['USERS_PARAF']; // masih static
				}
			}
							
			$img_stempel = base_url().'generate_doc_pro/create_stempel/' . $categories_image. '/' . $tgl_stempel .'/' . $ttd_stempel ;
			$img_stempel = '<img src="'.$img_stempel.'">';
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
					$a = ($a++)+2;
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
		<div style="page-break-inside:avoid; border: 0px solid #000; ">			
			<p style="margin-bottom: 0;"><b>KOLOM STEMPEL</b><br>&nbsp;</p>						
			<div style="width: 165mm; border: 1px solid #000; padding: 5px; text-align: center;">
				&nbsp;<br>
				'.$img_stempel.'<br>
				<!--'.$img_stempel.'-->
				&nbsp;
			</div>
		</div>
		';

		$mpdf->WriteHTML($html);

		$file_out = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/dokumen_orig_".$records['DOCUMENTS_NO'].".pdf";
		$mpdf->Output($file_out, 'F'); 

		return $file_out;
	}
	
	public function create_stempel($categories_image, $tgl_stempel, $ttd_stempel)
	{	
		// $categories_image = dirname($_SERVER['SCRIPT_FILENAME']) . "/uploads/category/iso_90011.png";
		$categories_image = dirname($_SERVER['SCRIPT_FILENAME']) . "/uploads/category/" . $categories_image;
		$categories_size = getimagesize($categories_image);
		
		// $tgl_stempel = "02 Desember 2014";
		$tgl_stempel = str_replace("%20", " ", $tgl_stempel);
		
		// $ttd_stempel = dirname($_SERVER['SCRIPT_FILENAME']) . "/assets/img/ttd.png";
		$ttd_stempel = dirname($_SERVER['SCRIPT_FILENAME']) . "/uploads/paraf/prf/" . $ttd_stempel;
		if (is_file($ttd_stempel)) {
			$ttd_size = getimagesize($ttd_stempel);
		}
		
		//$dest = imagecreatetruecolor($width=260, $height=185);
		$dest = imagecreatetruecolor($width=150, $height=95);
		imagecolortransparent($dest, imagecolorallocatealpha($dest, 255, 255, 255, 127));
		imagealphablending($dest, false);
		imagesavealpha($dest, true);		

		// copy image stempel
		$categories_image_png = imagecreatefrompng($categories_image);
		imagecopyresampled($dest, $categories_image_png, 0, 0, 0, 0, $width, $height, $categories_size[0], $categories_size[1]);
		
		if (isset($ttd_size)) {
			// add ttd stempel
			//$width_ttd=100; 
			//$height_ttd=30;
			$width_ttd=70; 
			$height_ttd=10;
			$ttd_stempel_png = imagecreatefrompng($ttd_stempel);
			//imagecopyresampled($dest, $ttd_stempel_png, 135, 150, 0, 0, $width_ttd, $height_ttd, $ttd_size[0], $ttd_size[1]);
			imagecopyresampled($dest, $ttd_stempel_png, 65, 80, 0, 0, $width_ttd, $height_ttd, $ttd_size[0], $ttd_size[1]);
		}
		
		// add tgl stempel
		//imagettftext($dest, 9, 0, 75, 153, imagecolorallocate($dest, 0, 0, 0), "application/libraries/MPDF/ttfonts/DejaVuSans.ttf", $tgl_stempel);
		imagettftext($dest, 6, 0, 45, 79, imagecolorallocate($dest, 0, 0, 0), "application/libraries/MPDF/ttfonts/DejaVuSans.ttf", $tgl_stempel);
		
		header("Content-type: image/png");
		imagePng($dest);		
	}
	
	public function create_header_footer_old()
	{
		// // create new PDF document
	// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// $pdf->SetCreator('Lintasarta Document Workflow');
		// $pdf->SetAuthor('Lintasarta Document Workflow');
		// $pdf->SetTitle('Dockflow');
		// $pdf->SetSubject('No. ');
		// $pdf->SetKeywords('No. ');   
		
	// $doc_id = intval( $this->uri->segment(3) );		
	// if( ! $doc_id )
	// {
		// $this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
		// redirect(404);
	// }

	// $documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
	// if( ! $documents )
	// {
		// $this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
		// redirect(404);
	// }
	// $pdf->setHtmlHeader(FALSE);	
	// $records = $documents;
	// $penandatangan = $this->mm_documents->get_penandatangan_for_webinfo($doc_id);		
	// //$data['versioning'] = FALSE;		
	// $versioning = $this->mm_documents->get_versioning($doc_id);	
	// $process = $this->mm_documents->get_process_by_cat($doc_id);		
	// //For Mrg PDF
	// $files = $this->mm_documents->get_files_merge($doc_id);	
	// $string = $records['DOCUMENTS_VERSION'];
	// // $fpdi = new FPDI();
	// // $filename = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/merger_31.pdf";
	// // $pageCount = $fpdi->setSourceFile($filename);
	// // for ($n = 1; $n <= $pageCount; $n++) {
	// $htmlh = '
	// <table style="width:100%;margin-bottom: 10cm;" border="1" cellspacing="0" cellpadding="4">
		// <tr>
			// <td style="border: 1px solid #000;font-size: 10px;">No. Dok: '.$records['DOCUMENTS_NO'].'</td>
			// <td style="border: 1px solid #000;" rowspan="4"><h2 style="text-align: center;">'.$records['DOCUMENTS_TITLE'].'</h3></td>
			// <td style="border: 1px solid #000;" rowspan="4"><div style="float: right;"><img src="'.base_url().'assets/img/hl2.png" alt="logo"></div></td>
		// </tr>
		// <tr>
			// <td style="font-size: 10px;">Versi: '.$string[0].'.'.$string[1].'</td>
		// </tr>
		// <tr style="font-size: 10px;">
			// <td>Hal:  </td>
		// </tr>
		// <tr style="font-size: 10px;">
			// <td>Label: Internal</td>
		// </tr>
	// </table>';
	// // }
	// $htmlf = '
	// <p style="width: 100%;border-bottom: 1px solid #000;"></p>
	// <div style="display: block; width: 100%;"></div>
	// <p style="font-size: 8px;">@HakCipta PT.APLIKANUSA LINTASARTA, Indonesia <br>'.$records['DOCUMENTS_NO'].'  '.$records['DOCUMENTS_TITLE'].'.</p>
	// ';	

	// $pdf->setHtmlHeader($htmlh);
	// $pdf->setHtmlFooter($htmlf);

	// // set header and footer fonts
	// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', '7'));

	// // set default monospaced font
	// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// // set margins
	// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// $pdf->SetFooterMargin(17);

	// // set auto page breaks
	// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-15);

	// // set image scale factor
	// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// // set some language-dependent strings (optional)
	// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		// require_once(dirname(__FILE__).'/lang/eng.php');
		// $pdf->setLanguageArray($l);
	// }

	// // ---------------------------------------------------------
	// $pdf->setListIndentWidth(4);
	// // set default font subsetting mode
	// $pdf->setFontSubsetting(true);
	// // Set font
	// $pdf->SetFont('dejavusans', '', 10, '', true);
	// // Add a page
	// // This method has several options, check the source code documentation for more information.
	// $pdf->AddPage();

	// $pdf->writeHTML('', true, false, true, false, '');

	// $file_header_footer = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/hf_".$this->session->userdata('session_id').".pdf";
	// $pdf->Output($file_header_footer, 'F');   	
	// return $file_header_footer;
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

		$file_header_footer = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/hf_".$this->session->userdata('session_id').".pdf";
		$mpdf->Output($file_header_footer,'F');

		return $file_header_footer;
	}
	
    public function testerM($doc_id) 
	{
		// $doc_id = intval( $this->uri->segment(3) );		
		// if( ! $doc_id )
		// {
			// $this->session->set_flashdata('error', $this->data['config']['msg_no_data']);
			// redirect(404);
		// }

		// $documents = $this->mm_documents->get_detail( $doc_id, $this->data['userInfo']['uID'] );
		
		// $file_dock = $this->create($doc_id);
	
		// $file_header_footer = $this->create_header_footer();
		
		// $file_lampiran = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/example_002.pdf"; // masih static
		// $file_merge = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/merger_".$documents['PK_DOCUMENTS_ID'].".pdf";

		// $PDFMerger = new PDFMerger2();
		// $PDFMerger->addPDF($file_dock);		
		// $PDFMerger->addPDF_header_footer($file_lampiran, 'all', $file_header_footer);				
		// $PDFMerger->merge('file', $file_merge);
		// header('Content-Type: application/pdf');
		// header('Content-Disposition: inline; filename="'.$file_merge.'"');
		// header('Cache-Control: private, max-age=0, must-revalidate');
		// header('Pragma: public'); 
		// readfile($file_merge);
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
		$file_lampiran = dirname($_SERVER['SCRIPT_FILENAME']).UPLOAD_DOKPRO.$records['PK_DOCUMENTS_ID']."/".$files['DOCUMENTS_ATC_SYSTEM']."";	
		$file_merge = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/merger_".$records['PK_DOCUMENTS_ID'].".pdf";
		
		$file_finish = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/dokumen_prosedur/".$records['DOCUMENTS_NO'].".pdf";
		
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
