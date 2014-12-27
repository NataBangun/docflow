<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(dirname(__FILE__) . "/../libraries/MPDF/mpdf.php");
require_once(dirname(__FILE__) . '/../libraries/fpdf/fpdf.php');
require_once(dirname(__FILE__) . '/../libraries/fpdi/fpdi.php');

class generate_pdf extends CI_Controller {
 
	private $_NO_SURAT;
	private $_LAMPIRAN_RAWNAME;
	private $java_exe;
 
    function __construct()
    {
        parent::__construct();
		$this->load->model('mm_digital_certificate');
		$this->load->library("pdf");
		$this->load->library("PDFMerger"); 
		
		$this->java_exe = JAVA_DIR . '\java.exe';
    }
 
    public function index() 
	{
		echo "Hello ....";
}
 
    private function create_nota_dinas_old($pk_nota_id) 
	{	
		// $sql = "SELECT * FROM V_NOTA WHERE pk_nota_id = ?";
		// $query = $this->db->query($sql, array($pk_nota_id));
		// // var_dump($query);
		// if ($query->num_rows > 0) {
			// $row = $query->row();
		// } else {
			// die("Data not found");
		// }
		// $this->_NO_SURAT = $row->NO_SURAT;
		
		// // create new PDF document
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
	 
		// // set document information
		// $pdf->SetCreator('Lintasarta Document Workflow');
		// $pdf->SetAuthor('Lintasarta Document Workflow');
		// $pdf->SetTitle('Nota Dinas');
		// $pdf->SetSubject('No. '.$this->_NO_SURAT);
		// $pdf->SetKeywords('No. '.$this->_NO_SURAT);   
	 
		// // set default header data
		// $pdf->SetFooterData(array(0,0,0)); 
		// $pdf->SetPrintHeader(false);
		// $pdf->SetPrintFooter(true);
	 
		// // set header and footer fonts
		// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	 
		// // set default monospaced font
		// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
	 
		// // set margins
		// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);    
	 
		// // set auto page breaks
		// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
	 
		// // set image scale factor
		// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  
	 
		// // set some language-dependent strings (optional)
		// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			// require_once(dirname(__FILE__).'/lang/eng.php');
			// $pdf->setLanguageArray($l);
		// }   
	 
		// // set default font subsetting mode
		// $pdf->setFontSubsetting(true);   	 
		// $pdf->SetFont('times', '', 12, '', true);   	 
		// $pdf->AddPage(); 	 
		// // $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));    
	 
		// // Set some content to print
		// $logo_header = dirname($_SERVER['SCRIPT_FILENAME']).'/assets/img/logo-header.png';
		// $deskripsi = ($row->DESKRIPSI != NULL) ? $row->DESKRIPSI->load() : "";
		// $deskripsi = str_replace("../uploads/", dirname($_SERVER['SCRIPT_FILENAME'])."/uploads/", $deskripsi);
		// $show_sah1 = (strlen($row->PENGESAHAN1) > 0) ? "" : "none";
		// $show_sah2 = (strlen($row->PENGESAHAN2) > 0) ? "" : "none";
		// $show_sah3 = (strlen($row->PENGESAHAN3) > 0) ? "" : "none";
		// $arr_tembusan = explode("|", $row->TEMBUSAN);
		// if (count($arr_tembusan) > 1) {
			// $tembusan = "  
				// <p>Tembusan:</p>
				// <ol>
				// <li>".implode("</li><li>", $arr_tembusan)."</li>
				// </ol>";
		// } else {
			// $tembusan = "  
				// <p>Tembusan: $row->TEMBUSAN</p>
				// ";
		// }
		
		// $html = <<<EOD
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
// <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
// <title>Untitled Document</title>
// <style type="text/css">
// <!--
// .style1 {color: #FFFFFF}
// -->
// </style>
// </head>
// <body>
// <img src="$logo_header">
  // <style type="text/css">
      // ol {margin-left:0; padding-left:0; counter-reset:item}
      // ol>li {margin-left:0; padding-left:0; counter-increment:item; list-style:none inside}
      // ol>li:before {content:"(" counter(item) ")"; padding-right:0.5em}
    // </style>
	// <table width="100%" border="0">
  // <tr>
    // <td width="8%" height="38">&nbsp;</td>
    // <td width="8%">&nbsp;</td>
    // <td width="8%">&nbsp;</td>
    // <td width="8%">&nbsp;</td>
    // <td width="8%">&nbsp;</td>
    // <td width="8%">&nbsp;</td>
    // <td width="28%"><span class="style1"></span></td>
    // <td width="24%">
	// <table border="1" border-color="black">
	// <tr>
	// <td><div align="center">$row->KLASIFIKASI</div></td>
	// </tr>
	// </table>	</td>
  // </tr>
// </table>

  // <p align="center"><strong><u>NOTA DINAS</u></strong><br>No. $row->NO_SURAT</p>
// <blockquote>
   // <table width="484" border="0">
    // <tr>
      // <td width="71"><p>Kepada</p>
      // </td>
      // <td width="10"><div align="center">:</div></td>
      // <td width="381" valign="middle"><p> $row->KEPADA </p>
        // <p>&nbsp;</p></td>
    // </tr>
    // <tr>
      // <td height="45">Dari</td>
      // <td><div align="center">:</div></td>
      // <td width="381" valign="middle"><p> $row->DARI </p>
      // </td>
    // </tr>
    // <tr>
      // <td>Hal</td>
      // <td><div align="center">:</div></td>
      // <td width="381" valign="middle"><p><strong> $row->HAL </strong></p>
      // </td>
    // </tr>
    // <tr>
      // <td>&nbsp;</td>
      // <td><div align="center"></div></td>
      // <td valign="middle"><p>&nbsp;</p>
      // </td>
    // </tr>
  // </table>
// </blockquote>
  // <strong>____________________________________________________________________________________</strong>
  // $deskripsi
  // <p>Demikian disampaikan, atas perhatian Bapak kami ucapkan terima kasih.</p>
  
// <table width="100%" border="0" bordercolor="#FFFFFF">
	// <tr>
		// <td width="32%" align="center">
			// <span style="dislay:$show_sah3">
			// <p><br></p>
			// <p>$row->PENGESAHAN3</p>
			// <p>&nbsp;</p>
			// <p><strong><u>$row->PENGESAHAN_NAME3</u></strong></p>
			// <p>NIK $row->PENGESAHAN_NIK3 </p>
			// </span>
		// </td>
		// <td width="32%" align="center">
			// <span style="dislay:$show_sah2">
			// <p><br></p>
			// <p>$row->PENGESAHAN2 </p>
			// <p>&nbsp;</p>
			// <p><strong><u>$row->PENGESAHAN_NAME2</u></strong></p>
			// <p>NIK $row->PENGESAHAN_NIK2 </p>
			// </span>
		// </td>
		// <td width="32%" align="center">
			// <span style="dislay:$show_sah1">
			// <p>$row->TEMPAT, $row->TANGGAL_NOTA</p>
			// <p>$row->PENGESAHAN1 </p>
			// <p>&nbsp;</p>
			// <p><strong><u>$row->PENGESAHAN_NAME1</u></strong></p>
			// <p>NIK $row->PENGESAHAN_NIK1 </p>
			// </span>
		// </td>
	// </tr>
// </table>
  // $tembusan

  // <p>$row->INISIAL</p>
   // </body>
// </html>

   
// EOD;
 
		// // Print text using writeHTMLCell()
		// $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
	 
		// $file_nota_dinas = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/nota_".$this->session->userdata('session_id').".pdf";
		// $pdf->Output($file_nota_dinas, 'F');   
		
		// return $file_nota_dinas;
    }
	
    private function create_nota_dinas($pk_nota_id) 
	{	
		$sql = "SELECT * FROM V_NOTA WHERE pk_nota_id = ?";
		$query = $this->db->query($sql, array($pk_nota_id));
		// var_dump($query);
		if ($query->num_rows > 0) {
			$row = $query->row();
		} else {
			die("Data not found");
		}
		$this->_NO_SURAT = $row->NO_SURAT;
		$this->_LAMPIRAN_RAWNAME = $row->LAMPIRAN_RAWNAME;
		 
		// Set some content to print
		$logo_header = dirname($_SERVER['SCRIPT_FILENAME']).'/assets/img/logo-header.png';
		$deskripsi = ($row->DESKRIPSI != NULL) ? $row->DESKRIPSI->load() : "";
		//$deskripsi = str_replace("../uploads/", dirname($_SERVER['SCRIPT_FILENAME'])."/uploads/", $deskripsi);
		$color_sah1 = (strlen($row->PENGESAHAN1) > 0) ? "black" : "white";
		$color_sah2 = (strlen($row->PENGESAHAN2) > 0) ? "black" : "white";
		$color_sah3 = (strlen($row->PENGESAHAN3) > 0) ? "black" : "white";
		
		$ttd_sah1 = (strlen($row->PENGESAHAN1) > 0 && $row->PROCESS_STATUS == 4) 
			? "<img src=\"".base_url()."uploads/paraf/ttd/{$row->USERS_SIGNATURE1}\" height=\"40px\" alt=\"logo\">" : "<br><br><br><br>";
		$ttd_sah2 = (strlen($row->PENGESAHAN2) > 0 && $row->PROCESS_STATUS == 4) 
			? "<img src=\"".base_url()."uploads/paraf/ttd/{$row->USERS_SIGNATURE2}\" height=\"40px\" alt=\"logo\">" : "<br><br><br><br>";
		$ttd_sah3 = (strlen($row->PENGESAHAN3) > 0 && $row->PROCESS_STATUS == 4) 
			? "<img src=\"".base_url()."uploads/paraf/ttd/{$row->USERS_SIGNATURE3}\" height=\"40px\" alt=\"logo\">" : "<br><br><br><br>";
			
		$arr_kepada = explode(",", $row->KEPADA_TEXT);
		if (count($arr_kepada) > 1) {
			$kepada_text = "-&nbsp;&nbsp;" . implode("<br>-&nbsp;&nbsp;", $arr_kepada);
		} else {
			$kepada_text = $row->KEPADA_TEXT;
		}
		$arr_tembusan = explode(",", $row->TEMBUSAN_TEXT);
		if (count($arr_tembusan) > 1) {
			$tembusan = "
				<p style=\"font-size: 9px; margin-top: 15mm;\">
					Tembusan :
				</p>  
				<ol style=\"font-size: 9px; margin: 0px; text-indent: 4mm;\">
				<li>".implode("</li><li>", $arr_tembusan)."</li>
				</ol>
				";
		} else {
			$tembusan = "	
				<p style=\"font-size: 9px; margin-top: 15mm;\">
					Tembusan :&nbsp;&nbsp;&nbsp;$row->TEMBUSAN_TEXT
				</p>";
		}
		$lampiran = (strlen($row->LAMPIRAN_NAME) > 0) ? $row->LAMPIRAN_NAME : "-";
		if ($row->PROCESS_STATUS == 4) {
			$inisial = str_replace("/", ";", strtoupper($row->INISIAL));
			$arr_inisial = explode(";", $inisial);
			$paraf = "";
			foreach ($arr_inisial as $value) {
				$sql = "SELECT T2.* FROM V_EMPLOYEE T1, T_USERS T2 
					WHERE T1.EMPLOYEE_NO=T2.EMPLOYEE_NO AND T1.USER_NAME=?";
				$query = $this->db->query($sql, array($value));
				$row1 = $query->row_array();
				$paraf = $paraf."<img src=\"".base_url()."uploads/paraf/prf/{$row1['USERS_PARAF']}\" height=\"30px\" alt=\"logo\">&nbsp;";
				// $paraf = $paraf.$row1['USERS_PARAF'];
			}
			// $paraf = $inisial;
		} else {
			$paraf = "";
		}
		
		
		$html = <<<EOD
	<p style="position: absolute; left: 20mm; top: 13mm;">
		<img src="$logo_header" height="25mm">
	</p>
	
	<p style="position:absolute; top: 30mm; right: 40mm; text-align: center; font-size: 14px;
		border: 2px solid black; padding: 2mm 4mm 2mm 4mm; box-shadow: 0.3em 0.3em #888888;">
		<b>$row->KLASIFIKASI</b>
	</p>

	<p style="text-align: center; margin-top: 10mm; margin-bottom: 7mm;">
		<b><u>NOTA DINAS</u></b><br>
		No. $row->NO_SURAT
	</p>
	
	<p>
	<table width="100%" border="0" style="vertical-align: top; border-bottom: 1px solid black; margin-bottom: 5mm;">
    <tr>
      <td width="40mm" style="padding-left: 23mm; padding-bottom:4mm;">Kepada</td>
      <td width="2mm">:</td>
      <td style="padding-left: 6mm;">$kepada_text</td>
    </tr>
    <tr>
      <td style="padding-left: 23mm; padding-bottom:4mm;">Dari</td>
      <td>:</td>
      <td style="padding-left: 6mm;">$row->DARI</td>
    </tr>
    <tr>
      <td style="padding-left: 23mm; padding-bottom:8mm;">Hal</td>
      <td>:</td>
      <td style="padding-left: 6mm;"><b>$row->HAL</b></td>
    </tr>
	</table>
	</p>
	
	$deskripsi
  
	<p>
	<table width="100%" border="0" style="vertical-align: top; margin-top: 13mm;">
	<tr>
		<td width="33%" style="text-align: center;">
			&nbsp;
		</td>
		<td width="33%" style="text-align: center;">
			&nbsp;
		</td>
		<td width="33%" style="text-align: center; padding-bottom: 3mm;">
			$row->TEMPAT, $row->TANGGAL_NOTA
		</td>
	</tr>
	<tr>
		<td width="33%" style="text-align: center; color: $color_sah3;">
			<b>$row->PENGESAHAN3</b>&nbsp;
		</td>
		<td width="33%" style="text-align: center; color: $color_sah2;">
			<b>$row->PENGESAHAN2</b>&nbsp;
		</td>
		<td width="33%" style="text-align: center; color: $color_sah1;">
			<b>$row->PENGESAHAN1</b>&nbsp;
		</td>
	</tr>
	<tr>
		<td width="33%" style="text-align: center; color: $color_sah3;">
			$ttd_sah3
		</td>
		<td width="33%" style="text-align: center; color: $color_sah2;">
			$ttd_sah2
		</td>
		<td width="33%" style="text-align: center; color: $color_sah1;">
			$ttd_sah1
		</td>
	</tr>
	<tr>
		<td width="33%" style="text-align: center; color: $color_sah3;">
			<b><u>$row->PENGESAHAN_NAME3</u></b>&nbsp;<br/>
			<b>NIK: $row->PENGESAHAN_NIK3</b>&nbsp;
		</td>
		<td width="33%" style="text-align: center; color: $color_sah2;">
			<b><u>$row->PENGESAHAN_NAME2</u></b>&nbsp;<br/>
			<b>NIK: $row->PENGESAHAN_NIK2</b>&nbsp;
		</td>
		<td width="33%" style="text-align: center; color: $color_sah1;">
			<b><u>$row->PENGESAHAN_NAME1</u></b>&nbsp;<br/>
			<b>NIK: $row->PENGESAHAN_NIK1</b>&nbsp;
		</td>
	</tr>
	</table>
	</p>
EOD;
	
		$html_tembusan = <<<EOD
		
	$tembusan	
	<p style="font-size: 8px; margin-top: 3mm;">Lampiran: $lampiran</p>	
	<span style="font-size: 8px; margin-top: 3mm; float:left">
		<i>$row->INISIAL</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</span>
		$paraf
EOD;

		$mpdf = new mPDF($mode = 'c', $format = 'A4', $font_size = '10', $font_type = 'Arial',
			$margin_left = 40, $margin_right = 40, $margin_top = 45, $margin_bottom = 40, 
			$margin_header = 0, $margin_footer = 0, $orientation = 'P'); 

		$mpdf->SetDisplayMode('fullpage');

		$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
		$mpdf->WriteHTML($html);

		$mpdf->list_indent_first_level = 1;	// 1 or 0 - whether to indent the first level of a list
		$mpdf->WriteHTML($html_tembusan);

		$file_nota_dinas = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/nota_".$this->session->userdata('session_id').".pdf";
		$mpdf->Output($file_nota_dinas,'F');
 		
		return $file_nota_dinas;
    }
	
	private function create_header_footer_test()
	{
		// // create new PDF document
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);    
	 
		// // set document information
		// $pdf->SetCreator('Lintasarta Document Workflow');
		// $pdf->SetAuthor('Lintasarta Document Workflow');
		// $pdf->SetTitle('Nota Dinas');
		// $pdf->SetSubject('No. '.$this->_NO_SURAT);
		// $pdf->SetKeywords('No. '.$this->_NO_SURAT);   
	 
		// // // set default header data
		// // $pdf->SetFooterData(array(0,0,0)); 
		// $pdf->SetPrintHeader(false); // dibuat false karena kita pake manual html
		// $pdf->SetPrintFooter(false); // dibuat false karena kita pake manual html
	 
		// // // set header and footer fonts
		// // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		// // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	 
		// // set default monospaced font
		// // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
	 
		// // set margins
		// // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
		// // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		// // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);    
	 
		// // set auto page breaks
		// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
	 
		// // set image scale factor
		// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  
	 
		// // set some language-dependent strings (optional)
		// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			// require_once(dirname(__FILE__).'/lang/eng.php');
			// $pdf->setLanguageArray($l);
		// }   
	 
		// // set default font subsetting mode
		// $pdf->setFontSubsetting(true);   	 
		// $pdf->SetFont('times', '', 12, '', true);   	 
		// $pdf->AddPage(); 	 
		// // $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));    
	 
		// // Set some content to print
		// $logo_header = dirname($_SERVER['SCRIPT_FILENAME']).'/assets/img/logo-header.png';
		// $html = <<<EOD
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
	// <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	// <title>Nota Dinas</title>
	// <style type="text/css">
	// </style>
// </head>
// <body>
	// <table>
	// <tr>
		// <td style="border: solid 1px black; text-align: center;">
			// <img src="$logo_header" height="50px">
		// </td>
		// <td style="border: solid 1px black; text-align: center;">
			// No. $this->_NO_SURAT
			// <br>
			// <b>Testing HTML</b>
			// <br>
			// <i>Testing HTML</i>
		// </td>
	// </tr>
	// </table>
// </body>
// </html>
// EOD;
 
		// // Print text using writeHTMLCell()
		// $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);   
	 
		// $file_header_footer = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/header_".$this->session->userdata('session_id').".pdf";
		// $pdf->Output($file_header_footer, 'F');   
		
		// return $file_header_footer;
	}

	public function create_header_footer($kutipan)
	{
		$mpdf = new mPDF($mode = 'c', $format = 'A4', $font_size = '10', $font_type = 'Arial',
			$margin_left = 40, $margin_right = 40, $margin_top = 30, $margin_bottom = 30, 
			$margin_header = 0, $margin_footer = 0, $orientation = 'P'); 

		$mpdf->SetDisplayMode('fullpage');

		// $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

		// Halaman 1
		$html = <<<EOD
			<!-- Ukuran kertas A4: 210mm x 297mm; Width = 210mm (Lebar kertas) - 40mm (margin kiri) - 40mm (margin kanan) = 135mm -->
			<p style="position: absolute; left: 40mm; bottom: 30mm; width: 130mm; text-align: right;">
				$kutipan
			</p>
EOD;
		$mpdf->WriteHTML($html);		
		
		// Halaman 2
		$mpdf->AddPage();
		$html = <<<EOD
			<p style="text-align: right; position: fixed; top: -4mm; right: 0mm; ">
				Halaman 2 dari 2
			</p>
			<p style="border-bottom: 1px solid black; padding-bottom: 6px;">
				Nota Dinas No. $this->_NO_SURAT
			</p>
EOD;
		$mpdf->WriteHTML($html);
		
		// Halaman 3
		$mpdf->AddPage();
		$html = <<<EOD
			<p style="border-bottom: 1px solid black; padding-bottom: 6px;">
				Lampiran Nota Dinas No. $this->_NO_SURAT
			</p>
EOD;
		$mpdf->WriteHTML($html);

		$file_header_footer = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/header_".$this->session->userdata('session_id').".pdf";
		$mpdf->Output($file_header_footer,'F');

		return $file_header_footer;
	}
	
    public function nota($pk_nota_id, $return_pdf = false) 
	{		
		$file_nota_dinas = $this->create_nota_dinas($pk_nota_id);
		$fpdi = new FPDI;
		$count_file_nota_dinas = $fpdi->setSourceFile($file_nota_dinas);
				
		if ($count_file_nota_dinas == 1) {
			$kutipan = "&nbsp;";
		} else {
			$pdfbox_jar = dirname(__FILE__) . '/../libraries/pdfbox-app-1.8.4.jar';
			$cmd = <<<EOD
			"$this->java_exe" -jar "$pdfbox_jar" ExtractText "$file_nota_dinas" -console true -startPage 2 -endPage 2
EOD;

			exec($cmd, $output);
			$kutipan = htmlspecialchars($output[0], ENT_IGNORE);
			$array_kutipan = explode(" ", $kutipan);
			if (isset($array_kutipan[0])) {
				$kutipan = $array_kutipan[0];
				if (strlen($kutipan) <= 3 && isset($array_kutipan[1])) {
					$kutipan .= " " . $array_kutipan[1];
				}
			} else {
				$kutipan = "";
			}
			$kutipan .= "...";
		}
		$file_header_footer = $this->create_header_footer($kutipan);
		
		// $file_lampiran = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/pdf/Lampiran Nota Dinas.pdf"; // masih static
		$file_lampiran = dirname($_SERVER['SCRIPT_FILENAME']).$this->_LAMPIRAN_RAWNAME;
		$file_merge = dirname($_SERVER['SCRIPT_FILENAME'])."/assets/nota_merge_".$this->session->userdata('session_id').".pdf";

		$PDFMerger = new PDFMerger();
		$PDFMerger->addPDF_header_footer($file_nota_dinas, 'all', $file_header_footer);
		if (is_file($file_lampiran)) {
			$PDFMerger->addPDF_header_footer($file_lampiran, 'all', $file_header_footer);		
		}
		$PDFMerger->merge('file', $file_merge);

		// $this->add_page_no($file_merge);
		
		if ($return_pdf == false) {
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.pathinfo($file_merge, PATHINFO_BASENAME).'"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
		
			readfile($file_merge);
		} else {
			return $file_merge;
		}
    }
	
	public function add_page_no($filename)
	{
		$fpdi = new FPDI;
		$count = $fpdi->setSourceFile($filename);
		for($i=1; $i<=$count; $i++)
		{
			$template 	= $fpdi->importPage($i);
			$size 		= $fpdi->getTemplateSize($template);
			
			$fpdi->AddPage('P', array($size['w'], $size['h']));
			$fpdi->useTemplate($template);					

			$fpdi->SetFont('Times', '', 10);
			$fpdi->SetTextColor(0, 0, 0);
			// $fpdi->SetXY(10,10); // posisi atas
			$fpdi->SetXY(10,270); // posisi bawah
			$fpdi->Cell($x=163, $y=5, $txt='Halaman '.$i.' dari '.$count, $border=0, $ln=0, $align='R');
		}
		$fpdi->Output($filename, 'F');
		
	}

    public function sign($pk_nota_id, $process_id) 
	{		
		$file_pdf = $this->nota($pk_nota_id, true);
		
		$sql = "select * from v_nota where pk_nota_id = ?";
		$query = $this->db->query($sql, array($pk_nota_id));
		$row = $query->row();

		$this->mm_digital_certificate->set_process_id($process_id);
		$this->mm_digital_certificate->set_folder($this->session->userdata('session_id'));
		$this->mm_digital_certificate->set_pdf($file_pdf);

		if (strlen($row->PENGESAHAN1) > 0) {	
			$this->mm_digital_certificate->sign($alias = $row->PENGESAHAN_NIK1, 
				$email = $row->PENGESAHAN_EMAIL1, 
				$nama = $row->PENGESAHAN_NAME1, 
				$unit = $row->PENGESAHAN_UNIT1);
		}
		if (strlen($row->PENGESAHAN2) > 0) {	
			$this->mm_digital_certificate->sign($alias = $row->PENGESAHAN_NIK2, 
				$email = $row->PENGESAHAN_EMAIL2, 
				$nama = $row->PENGESAHAN_NAME2, 
				$unit = $row->PENGESAHAN_UNIT2);
		}
		if (strlen($row->PENGESAHAN3) > 0) {	
			$this->mm_digital_certificate->sign($alias = $row->PENGESAHAN_NIK3, 
				$email = $row->PENGESAHAN_EMAIL3, 
				$nama = $row->PENGESAHAN_NAME3, 
				$unit = $row->PENGESAHAN_UNIT3);
		}
			
		$file_pdf_signed = $this->mm_digital_certificate->get_signed_pdf();

		echo "sukses";
		//header('Location: '. $_SERVER['HTTP_REFERER']);
		
    }
}
 
