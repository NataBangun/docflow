<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class signed_pdf extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
    }
 
    public function index($process_id) 
	{
	
		$sql = "select * from t_digital_certificate where fk_documents_process_id = ?";
		$query = $this->db->query($sql, array($process_id));
		$row = $query->row();
		$file_pdf_signed = $row->FILE_PDF_SIGNED;
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.pathinfo($file_pdf_signed, PATHINFO_BASENAME).'"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		
		readfile($file_pdf_signed);
	}
	
}
